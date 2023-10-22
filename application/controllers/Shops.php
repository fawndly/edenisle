<?php
if(!defined('BASEPATH'))
    exit('No direct script access allowed');

class Shops extends CI_Controller
{
    var $route_navigation = array(
        'index'    => 'Shop Directory',
        'sellback' => 'Sell Items'
    );
    var $ignore_layers = array();
    var $ignore_items = array(
        '1',    // Starter shirt
        '5',    // Base (pale)
        '3',    // Base (normal)
        '8',    // Base (palest)
        '9',    // Base (tan)
        '10',   // Base (dark)
        '12',   // Base (darktan)
        '13',   // Base (darkest)
        '36',   // Standard eyes (grey)
        '37',   // Standard eyes (black)
        '38',   // Standard eyes (blue)
        '39',   // Standard eyes (brown)
        '40',   // Standard eyes (green)
        '46',   // Standard eyes (red)
        '81',   // Mouth (1 dark)
        '82',   // Mouth (1 light)
        '83',   // Mouth (2)
        '84',   // Mouth (3 dark)
        '85',   // Mouth (3 light)
        '86',   // Mouth (4)
        '87',   // Starter Jeans
        '88',   // Starter Jeans (Grey)
        '89',   // Starter Jeans (Black)
        '90',   // Starter Jeans (Blue)
        '113',  // Puffy Hair (black)
        '115',  // Puffy Hair (brown)
        '134',  // Puffy Hair (yellow)
        '136',  // Military Hair (black)
        '138',  // Military Hair (brown)
        '157',  // Military Hair (yellow)
        '159',  // Parted long Hair (black)
        '161',  // Parted long Hair (brown)
        '180',  // Parted long Hair (yellow)
        '368',  // Sports Bra
        '683',  // Basic undies (black)
        '2187', // Nose (small)
        '2331', // De-equip mouth
        '2332', // De-equip nose
        '2333', // De-equip hair
        '7900', //remove eyes
    );

    function __construct() {
        parent::__construct();
    }

    /**
     * Home page
     *
     * Shops main function
     *
     * @access  public
     * @param   none
     * @return  view
     * @route   n/a
     */
    public function index()
    {
        $view_data = array(
            'page_title'        => 'Sapherna Shops',
            'page_body'         => 'shops',
            'routes'            => $this->route_navigation,
            'active_url'        => $this->uri->rsegment(2, 0),
            'navigation_header' => 'Sapherna\'s Shops',
            'shops'             => $this->db->get('shops')->result_array()
        );

        $this->system->quick_parse('shops/index', $view_data);
    }


    /**
     * View
     * Displays the shop items
     *
     * @access  public
     * @param   none
     * @return  output
     */
    public function view($shop_id = 0){
        if (!is_numeric($shop_id))
            show_error('shop_id must be valid');

        $this->cache->delete('shop_total_' . $shop_id);
        if (!$total_rows = $this->cache->get('shop_total_'.$shop_id)) {
            $total_rows = $this->db->select('COUNT(1) as total')
                                   ->where('shop_items.item_parent', 0)
                                   ->get_where('shop_items', array('shop_id' => $shop_id))
                                   ->row()
                                   ->total;

            $this->cache->save('shop_total_'.$shop_id, $total_rows, 2400);
        }

        $this->load->library('pagination');
        $config['base_url'] = '/shops/view/'.$shop_id.'/';
        $config['total_rows'] = $total_rows;
        $config['per_page'] = 36;
        $config['uri_segment'] = 4;
        $this->pagination->initialize($config);

        $shop_items = $this->db->select('shop_items.*, avatar_items.*, avatar_layers.shop_category')
                               ->from('shop_items')
                               ->join('avatar_items', 'shop_items.item_id = avatar_items.item_id')
                               ->join('avatar_layers', 'avatar_items.layer = avatar_layers.id')
                               ->where('shop_items.shop_id', $shop_id)
                               ->where('shop_items.item_parent', 0)
                               ->order_by('shop_items.shop_item_id', 'ASC')
                               ->limit($config['per_page'], $this->uri->segment(4))
                               ->get()
                               ->result_array();

        if (!$shop_data = $this->cache->get('shop_data_'.$shop_id)) {
            $shop_data = $this->db->get_where('shops', array('shop_id' => $shop_id))
                                  ->row_array();

            $this->cache->save('shop_data_'.$shop_id, $shop_data, 2400);
        }

        $view_data = array(
            'page_title'        => $shop_data['shop_name'].' - Shops',
            'page_body'         => 'shops',
            'routes'            => $this->route_navigation,
            'active_url'        => $this->uri->rsegment(2, 0),
            'navigation_header' => $shop_data['shop_name'],
            'shop_data'         => $shop_data,
            'shop_items'        => $shop_items,
            'username'          => $this->system->userdata['username'],
        );

        $this->system->quick_parse('shops/view', $view_data);
    }


    /**
     * New function
     *
     * Description of new function
     *
     * @access  public
     * @param   none
     * @return  output
     */
    public function view_item($item_id = 0)
    {
        if( ! is_numeric($item_id)) show_error('item_id must be valid');

        $shop_item_query = $this->db->join('avatar_items', 'avatar_items.item_id = shop_items.item_id')->get_where('shop_items', array('shop_item_id' => $item_id));

        if($shop_item_query->num_rows() > 0):
            $shop_item_data = $shop_item_query->row_array();
        else:
            show_error('shop_item could not be found.');
        endif;

        $shop_id = $shop_item_data['shop_id'];

        if ( ! $shop_data = $this->cache->get('shop_data_'.$shop_id)):
            $shop_data = $this->db->get_where('shops', array('shop_id' => $shop_id))->row_array();
            $this->cache->save('shop_data_'.$shop_id, $shop_data, 2400);
        endif;

        if($shop_item_data['item_parent'] == 0):
            $sibling_items = $this->db->join('avatar_items', 'avatar_items.item_id = shop_items.item_id')
                                      ->order_by('avatar_items.name', 'ASC')
                                      ->get_where('shop_items', array('item_parent' => $shop_item_data['shop_item_id']))
                                      ->result_array();
        else:
            $sibling_items = $this->db->join('avatar_items', 'avatar_items.item_id = shop_items.item_id')
                                      ->where(array('item_parent' => $shop_item_data['item_parent']))
                                      ->or_where(array('shop_items.shop_item_id' => $shop_item_data['item_parent']))
                                      ->order_by('avatar_items.name', 'ASC')
                                      ->get('shop_items')
                                      ->result_array();
        endif;

        if ($shop_item_data['second_price'] > 0):
            $forest_insect_query = $this->db->get_where('forest_insects', array('id' => $shop_item_data['insect_id']));
            if($forest_insect_query->num_rows() > 0):
                $forest_insect_data = $forest_insect_query->row_array();
                $shop_item_data['insect_name'] = $forest_insect_data['name'];
            else:
                show_error('forest_insect could not be found.');
            endif;
        endif;
        
        

        $owns_item = ($this->db->get_where('user_items', array('user_id' => $this->system->userdata['user_id'], 'item_id' => $shop_item_data['item_id']))->num_rows() > 0);

        $view_data = array(
            'page_title'        => $shop_item_data['name'].' - '.$shop_data['shop_name'].' - Shops',
            'page_body'         => 'shops',
            'routes'            => $this->route_navigation,
            'active_url'        => $this->uri->rsegment(2, 0),
            'navigation_header' => '<a href="/shops/view/'.$shop_data['shop_id'].'">&lsaquo; return</a> '.$shop_data['shop_name'],
            'shop_data'         => $shop_data,
            'item_data'         => $shop_item_data,
            'children'          => $sibling_items,
            'owns_item'         => $owns_item
        );

        $this->system->quick_parse('shops/view_item', $view_data);
    }


    /**
     * Purchace item
     * Verify and decreases the currency required for purchacing the item
     * @access  public
     * @param   none
     * @return  output
     */
    public function purchase_item() {
        $itemId = $this->input->post('item_id');

        if (!is_numeric($itemId))
            show_error('shop_item_id must be valid');

        $query = $this->db->join('avatar_items', 'avatar_items.item_id = shop_items.item_id')
                          ->get_where('shop_items', array('shop_item_id' => $itemId));

        if ($query->num_rows() > 0) {
            $itemData = $query->row_array();
        } else {
            show_error('shop_item could not be found.');
        }

        $shop_id = $itemData['shop_id'];

        if (!$shop_data = $this->cache->get('shop_data_'.$shop_id)) {
            $shop_data = $this->db->get_where('shops', array('shop_id' => $shop_id))->row_array();
            $this->cache->save('shop_data_'.$shop_id, $shop_data, 2400);
        }
        
        $this->load->model('user_engine');

        $reqCurr3 = ($itemData['third_price'] > 0);
        $reqCurr2 = ($itemData['second_price'] > 0);
        $reqCurr1 = ($itemData['price'] > 0);
        
        if ($reqCurr3 > 0 && $this->system->userdata['special_currency'] < $itemData['third_price'])
            show_error("Seems like you don't have the required ".$itemData['third_price']." ".
                ($item_data['third_price'] == 1 ? $this->config->item('special_currency')['name']['singular'] : $this->config->item('special_currency')['name']['plural']));

        if ($reqCurr2) {
            $insectQuery = $this->db->get_where('forest_insects', array('id' => $itemData['insect_id']));
            if ($insectQuery->num_rows() > 0) {
                $insectData = $insectQuery->row_array();
                $itemData['insect_name'] = $insectData['name'];

                $insects = $this->db->select('COUNT(1) as total')
                                    ->get_where('forest_user_catches', array('user_id' => $this->system->userdata['user_id'], 'insect_id' => $itemData['insect_id']))
                                    ->row()
                                    ->total;
                
                if ($insects < $itemData['second_price'])
                    show_error("Seems like you don't have the required {$insects} {$insectData['name']}s.");

            } else
                show_error('There is no such a bug in the forest.');
        }

        if ($reqCurr1 && $this->system->userdata['user_Ores'] < $itemData['price'])
            show_error('You cannot afford the ores for this item!');        
        
        if ($reqCurr3)
            $this->user_engine->remove('special_currency', $itemData['third_price']);

        if ($reqCurr2) {
            $this->db->where(array('user_id' => $this->system->userdata['user_id'], 'insect_id' => $itemData['insect_id']))
                     ->limit($itemData['second_price'])
                     ->delete('forest_user_catches');
        }

        $this->user_engine->remove('user_Ores', $itemData['price']);
        $this->user_engine->add_item($itemData['item_id']);

        redirect("/shops/view_item/{$itemId}?purchase=1");
    }

    /**
     * Purchace item family
     * Buy an item and all of its corresponding colors. Only works for ores-currency items.
     * @access  public
     * @param   none
     * @return  output
     */
    public function purchase_item_family() {
        $this->load->driver(array('accountant', 'inventory'));
        $itemId = $this->input->post('item_id');

        if (!is_numeric($itemId)) show_error('shop_item_id must be valid');

        $query = $this->db->join('avatar_items', 'avatar_items.item_id = shop_items.item_id')
                          ->get_where('shop_items', array('shop_item_id' => $itemId));

        if ($query->num_rows() > 0) {
            $itemData = $query->row_array();
        } else {
            show_error('shop_item could not be found.');
        }

        if ($itemData['item_parent'] != 0) show_error('family purchase must start from parental item');
        if ($itemData['sapphire_price'] != 0 || $itemData['second_price'] != 0 || $itemData['third_price'] != 0) show_error('item must be ores only');

        $family_of_items = $this->db->join('avatar_items', 'avatar_items.item_id = shop_items.item_id')
                                  ->where(array('item_parent' => $itemData['shop_item_id']))
                                  ->or_where(array('shop_items.shop_item_id' => $itemData['shop_item_id']))
                                  ->get_where('shop_items')
                                  ->result_array();

        $this->accountant->setCurrencyType('ores')->setOwner($this->system->userdata['user_id']);
        $this->inventory->setItemType('avatar_items')->avatar_items->setOwner($this->system->userdata['user_id']);
        
        foreach ($family_of_items as $shop_item) {
          try {
              if (!$shop_item['price'] > 0) die(print_r($shop_item));
              $this->accountant->withdraw($shop_item['price']);
              $this->inventory->addItem($shop_item['item_id']);
          } catch (Insufficient_Currency_Exception $e) {
              show_error('Ran out of funds');
          }
        }

        $this->session->set_flashdata('success', "You just bought x".count($family_of_items)." different versions of the ".$itemData['name']." item!");

        redirect("/shops/view/{$itemData['shop_id']}");
    }
    
    public function purchase_item_sapphire() {
        $itemId = $this->input->post('item_id');

        if (!is_numeric($itemId))
            show_error('shop_item_id must be valid');

        $query = $this->db->join('avatar_items', 'avatar_items.item_id = shop_items.item_id')
                          ->get_where('shop_items', array('shop_item_id' => $itemId));

        if ($query->num_rows() > 0) {
            $itemData = $query->row_array();
        } else {
            show_error('shop_item could not be found.');
        }

        $this->load->model('user_engine');
        if ($this->system->userdata['user_sapphires'] < $itemData['sapphire_price']) {
            return redirect("/shops/view_item/{$itemId}?error=2");
        }

        $this->user_engine->remove('user_sapphires', $itemData['sapphire_price']);
        $this->user_engine->add_item($itemData['item_id']);

        redirect("/shops/view_item/{$itemId}?purchase=1");
    }

    /**
     * New function
     *
     * Description of new function
     *
     * @access  public
     * @param   none
     * @return  output
     */
    public function sellback()
    {
        $this->system->view_data['scripts'][] = '/global/js/shops/sellback.js';
        if($_SERVER['REQUEST_METHOD'] == "POST"):
            $item_id = $this->input->post('item_id');

            $this->load->model('user_engine');

            $item_data = $this->db->select('user_items.item_id, shop_items.price, avatar_items.layer')
                                  ->from('user_items')
                                  ->join('avatar_items', 'user_items.item_id = avatar_items.item_id')
                                  ->join('shop_items', 'avatar_items.item_id = shop_items.item_id')
                                  ->where('user_items.user_id', $this->system->userdata['user_id'])
                                  ->where('user_items.item_id', $item_id)
                                  ->where_not_in('avatar_items.item_id', $this->ignore_items)
                                  ->where_not_in('user_items.equipped', 1) //temporarily remove equipped items.
                                  ->order_by('price', 'DESC')
                                  ->limit(1)
                                  ->get()
                                  ->row_array();

            if (count($item_data) > 1):
                $this->user_engine->remove_item($item_id, 1);
                $this->user_engine->add('user_Ores', $item_data['price'] >> 1);
            endif;

            if($this->input->get('json')):
                $this->system->parse_json(array('success' => 1, 'amount' => $item_data['price'] >> 1));
            else:
                redirect('shops/sellback');
            endif;
        else:
            $this->db->select('user_items.id, user_items.equipped, user_items.user_id, user_items.item_id, avatar_items.item_id, avatar_items.thumb, shop_items.item_id, shop_items.price, avatar_items.name, avatar_items.layer');
            $this->db->from('user_items');
            $this->db->join('avatar_items', 'user_items.item_id = avatar_items.item_id');
            $this->db->join('shop_items', 'avatar_items.item_id = shop_items.item_id');
            $this->db->where('user_items.user_id', $this->system->userdata['user_id']);
            $this->db->where('user_items.soft_deleted', 0);
            $this->db->where_not_in('avatar_items.item_id', $this->ignore_items);
            $this->db->where_not_in('user_items.equipped', 1); //temporarily remove equipped items.;

            switch ($this->input->get('sort_by')):
                case 'price':
                    $this->db->order_by('price', 'ASC');
                break;
                case 'name':
                    $this->db->order_by('avatar_items.name', 'ASC');
                break;
                case 'date':
                    $this->db->order_by('user_items.added_on', 'ASC');
                break;
                default:
                    $this->db->order_by('price', 'DESC');
                break;
            endswitch;

            $items = $this->db->get()->result_array();

            $view_data = array(
                'page_title'        => 'Sellback items - Shops',
                'page_body'         => 'shops',
                'routes'            => $this->route_navigation,
                'active_url'        => $this->uri->rsegment(2, 0),
                'navigation_header' => 'Sellback shop items',
                'items'             => $items
            );

            $this->system->quick_parse('shops/sellback', $view_data);
        endif;
    }
}

/* End of file Shops.php */
/* Location: ./system/application/controllers/Shops.php */
