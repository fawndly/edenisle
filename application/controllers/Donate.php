<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Donate extends CI_Controller
{
    var $donation_email = '';
    var $currency_value = 0.04;
    var $total_tyis = 4;
    var $currency_name = 'sapphires';
    var $donation_currency = 'user_sapphires';
    var $prices = array(
        'tyi'         => 50,
        'special_net' => 50
    );
    var $discount_bundles = array(
        '2.50' => 35,
        '2.5'  => 35,
        5      => 75,
        10     => 160,
        25     => 415,
        50     => 865
    );

    var $tyi_names = array();
    var $route_navigation = array(
        'index' => 'Thank You Items'
    );

    public function __construct()
    {
        parent::__construct();

        $this->load->driver(array('accountant', 'inventory'));

        if ( ! $this->tyis = $this->cache->get('tyi_cache')):
            $this->tyis = $this->db->order_by('item_id', 'DESC')->limit($this->total_tyis)->get_where('avatar_items', array('type' => 'tyi'))->result_array();

            foreach ($this->tyis as $key => $tyi):
                $this->tyis[$key]['children'] = $this->db->select('child_id as item_id, layer, parent_id, name, thumb')
                                                         ->where('parent_id', $tyi['item_id'])
                                                         ->join('avatar_items', 'avatar_items.item_id = avatar_items_relations.child_id')
                                                         ->order_by('avatar_items_relations.id', 'asc')
                                                         ->get('avatar_items_relations')
                                                         ->result_array();
            endforeach;

            foreach($this->tyis as $id => $tyi)
                array_unshift($this->tyis[$id]['children'], array("item_id"=>$this->tyis[$id]['item_id'], "name"=>$this->tyis[$id]['name'], "thumb"=>$this->tyis[$id]['thumb']));


            $this->cache->save('tyi_cache', $this->tyis, 200);
        endif;

        foreach ($this->tyis as $tyi):
            $this->tyi_ids[] = $tyi['item_id'];
            $this->tyi_names[] = $tyi['name'];
        endforeach;
    }

    // --------------------------------------------------------------------

    /**
     * Home page
     *
     * Donate main function
     *
     * @access  public
     * @param   none
     * @return  view
     */

    public function index()
    {
        $this->system->view_data['scripts'][] = '/global/js/donate/index.js';
        
        $view_data = array(
            'page_title'  => date('F Y') . '\'s Thank You Items',
            'page_body'   => 'donate',
            'items'       => $this->tyis,
            'routes'      => $this->route_navigation,
            'prices'      => $this->prices,
            'active_url'  => $this->uri->rsegment(2, 0),
        );

        $this->system->quick_parse('donate/index', $view_data);
    }

    // --------------------------------------------------------------

    public function ipn()
    {
        // for testing
        // $user_id = 18;
        // $gross_donation = 12.21;
        $user_id = $_POST['custom'];
        $gross_donation = $_POST['mc_gross'];

        if( ! isset($user_id)) show_error('Must have full post data');

        $total_donation_gross = ($gross_donation > 1 ? $gross_donation : round($gross_donation, 1));
        $donation_currency_compensation = round($total_donation_gross / $this->currency_value);

        $donation_rewards = $this->db->select('*')
                                     ->from('donation_rewards')
                                     ->where('threshold <=', $total_donation_gross)
                                     ->get()
                                     ->result_array();

        $this->accountant->setCurrencyType($this->currency_name)->setOwner($user_id);
        $this->inventory->setItemType('avatar_items')->avatar_items->setOwner($user_id);

        foreach ($donation_rewards as $reward) {
            if ($reward['type'] == 'item') {
                $this->inventory->addItem($reward['item']);
            } elseif ($reward['type'] == 'sapphires') {
                $this->accountant->deposit($reward['item']);
            }
        }

        $this->db->set('timestamp', 'NOW()', false);
        $this->db->insert('donation_transaction_log', array(
            'txn_id' => $_POST['txn_id'],
            'processed' => TRUE,
            'items_granted' => 0,
            'payment_gross' => $total_donation_gross,
            'user_id' => $user_id,
            'sapphires_granted' => $donation_currency_compensation,
            'custom' => $user_id
        ));

        $this->accountant->deposit($donation_currency_compensation);
    }

    // --------------------------------------------------------------------

    /**
     * Purchase donation item
     *
     * Purchase a donation item from the donation page for the currency
     *
     * @access  public
     * @param   none
     * @return  redirect
     */

    public function purchase_item()
    {
        if ( ! isset($this->system->userdata['user_id'])):
            redirect('/auth/signin?r=donate');
        endif;

        $item_id = $this->input->post('item_id');
        $total = $this->input->post('total');

        if( ! is_numeric($total)) show_error('total must be valid');
        if( ! is_numeric($item_id)) show_error('total must be valid');

        $avatar_item_query = $this->db->get_where('avatar_items', array('item_id' => $item_id));
        if($avatar_item_query->num_rows() > 0):
            $avatar_item_data = $avatar_item_query->row_array();
            if($avatar_item_data['type'] == 'tyi'):
                if (($this->prices['tyi']*$total) <= $this->system->userdata['user_'.$this->currency_name]):
                    $this->load->model('user_engine');
                    $this->user_engine->remove('user_'.$this->currency_name, ($this->prices['tyi']*$total));
                    $this->user_engine->add_item($item_id, $total);
                    redirect('donate?success=1');
                endif;
            else:
                show_error('You cannot purchase this item');
            endif;
        else:
            show_error('avatar_item could not be found.');
        endif;
    }
}

/* End of file Donate.php */
/* Location: ./system/application/controllers/donate.php */
