<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Market extends CI_Controller
{
    private $market_npc_id = 6;
    private $total_items_per_page = 18;
    private $sale_time_limit_in_hrs = 24;
    private $bid_time_limit_in_mins = 5;
    private $simoltanous_item_limit = 4;
    private $listing_fee_percent = 0; 
    private $ignore_layers = array(23, 30);
    private $ignore_items = array();
    public $route_navigation = array(
        'index'  => 'The Market',
        'your_listings'  => 'Your listings',
        'sell'  => 'Sell an item'
    );

    public $bid_time_options_in_mins = array(
        '5 mins'=> 5,
        '30 mins'=> 30,
        '1 hr'=> 60,
        '4 hrs'=> 240,
        '12 hrs'=> 720,
    );

    public function __construct() 
    {
        parent::__construct();
        $this->load->driver(array('accountant', 'inventory'));
        $this->load->helper(array('form'));

        $this->system->view_data['scripts'][] = '/global/js/jquery.countdown.js';
        $this->system->view_data['scripts'][] = '/global/js/avatar/jquery.lazy.js';
        $this->system->view_data['scripts'][] = '/global/js/market.js';

        if (!$this->session->user_id) redirect('/auth/signin?r=market');
       
        $this->_close_expired_offers();
    }

    public function index() 
    {
        $view_data = [
            'page_title' => $this->route_navigation[$this->uri->rsegment(2, 0)],
            'page_body' => 'market',
            'routes' => $this->route_navigation,
            'active_url' => $this->uri->rsegment(2, 0),
            'items' => $this->_marketplace_items([
                'finishes_at >' => date('Y-m-d H:i:s', time()),
                'purchased' => 0,
                'cancled' => 0
            ], $this->input->get('search'))
        ];

        $this->system->quick_parse('market/index', $view_data);
    }

    public function your_listings() 
    {
        $view_data = [
            'page_title' => $this->route_navigation[$this->uri->rsegment(2, 0)],
            'page_body' => 'market',
            'routes' => $this->route_navigation,
            'active_url' => $this->uri->rsegment(2, 0),
            'items' => $this->_marketplace_items([ 
                'marketplace_items.user_id' => $this->system->userdata['user_id']
            ], $this->input->get('search'))
        ];

        $this->system->quick_parse('market/index', $view_data);
    }

    public function sell() 
    {
        $view_data = [
            'page_title' => $this->route_navigation[$this->uri->rsegment(2, 0)],
            'page_body' => 'market sell',
            'routes' => $this->route_navigation,
            'bid_time_options_in_mins' => $this->bid_time_options_in_mins,
            'active_url' => $this->uri->rsegment(2, 0),
            'inventory' => $this->_get_inventory($this->session->user_id)
        ];

        $this->system->quick_parse('market/sell', $view_data);
    }

    public function purchase_item()
    {
        # Basic checks
        if($_SERVER['REQUEST_METHOD'] !== "POST") show_error('wrong req format');
        if( ! is_numeric($offer_id = $this->input->post('offer_id'))) show_error('offer id must be valid');

        # Data loading 
        $marketplace_item = $this->db->where(array('id' => $offer_id))->get('marketplace_items');

        if($marketplace_item->num_rows() > 0):
            $offer_data = $marketplace_item->row_array();
        else:
            show_error('This item could not be found!');
        endif;

        # Extra checks
        if($offer_data['sell_type'] !== 'buynow') show_error('This item is not available for buying');
        if($offer_data['user_id'] == $this->system->userdata['user_id']) show_error('You cannot purchase your own items');
        if($offer_data['purchased'] == 1 || $offer_data['cancled'] == 1) redirect('marketplace/?just_missed_it=1');

        # Process the transaction
        $this->accountant->setCurrencyType('ores');

        $listing_fee = round($offer_data['ore_price'] * $this->listing_fee_percent);
        $received_total = round($offer_data['ore_price'] - $listing_fee);

        try {
            $this->accountant->setOwner($this->system->userdata['user_id'])->withdraw($received_total + $listing_fee);
        } catch (Insufficient_Currency_Exception $e) {
            show_error('You do not have enough funds');
        }

        $this->accountant->setOwner($offer_data['user_id'])->deposit($received_total);

        $this->inventory->setItemType('avatar_items');
        $this->inventory->avatar_items->setOwner($this->system->userdata['user_id']);
        $this->inventory->addItem($offer_data['item_id']);

        # remove the item from the market and set the purchased data
        $marketplace_items = array(
            'purchased'    => 1,
            'purchased_by' => $this->system->userdata['user_id'],
        );

        $this->db->set('completed_at', 'NOW()', false)->where('id', $offer_id)->update('marketplace_items', $marketplace_items);


        $this->_send_inbox(
            $offer_data["user_id"],
            sprintf(
                $this->lang->line("market_sold_message"), 
                $offer_data["username"], 
                $offer_data["item_name"], 
                urlencode($this->system->userdata['username']), 
                $this->system->userdata['username']
            )
        );

        # extras
        $random_quotes = array(
            'bask in its glory',
            'sport it out',
            'enjoy it',
            'try it on',
            'add it to your stylish avatar',
            'give it a try',
            'have fun with it',
        );

        $this->session->set_flashdata('success', 'You\'ve bought the "'.$offer_data['item_name'].'" for a total of '.$offer_data['ore_price'].' ores. You should check out your inventory and '.$random_quotes[array_rand($random_quotes)].'!');

        redirect('market/index/'.$this->input->post('page'));
    }

    public function bid_on_item()
    {
        # Basic checks
        if($_SERVER['REQUEST_METHOD'] !== "POST") show_error('wrong req format');
        if( ! is_numeric($offer_id = $this->input->post('offer_id'))) show_error('offer id must be valid');

        # Data loading 
        $marketplace_item = $this->db->where(array('id' => $offer_id))->get('marketplace_items');

        if($marketplace_item->num_rows() > 0):
            $offer_data = $marketplace_item->row_array();
        else:
            show_error('This item could not be found!');
        endif;

        # Extra checks
        if($this->input->post('offer_price') > $this->system->userdata['user_Ores']) show_error('Insufficient ores');
        if($offer_data['sell_type'] !== 'bid') show_error('This item is not available for bidding');
        if($offer_data['user_id'] == $this->system->userdata['user_id']) show_error('You cannot bid on your own items');
        if($offer_data['purchased'] == 1 || $offer_data['cancled'] == 1) redirect('marketplace/?just_missed_it=1');
        if( ! is_numeric($this->input->post('offer_price')) || $this->input->post('offer_price') < $offer_data['ore_price'] + $offer_data['bid_increment']) show_error('bid must be valid');

        # remove the item from the market and set the purchased data
        $marketplace_items = array(
            'ore_price'    => $this->input->post('offer_price'),
            'last_bidder_id' => $this->system->userdata['user_id'],
        );

        $this->db->where('id', $offer_id)->update('marketplace_items', $marketplace_items);

        redirect('market/index/'.$this->input->post('page'));
    }

    public function retract_item()
    {
        if($_SERVER['REQUEST_METHOD'] !== "POST") show_error('wrong req format');
        $marketplace_item = $this->db->where(array('id' => $this->input->post('offer_id')))->get('marketplace_items');

        if($marketplace_item->num_rows() > 0):
            $offer_data = $marketplace_item->row_array();
        else:
            show_error('This item could not be found!');
        endif;

        if($offer_data['user_id'] != $this->system->userdata['user_id']) show_error('You cannot cancel other ations!');
        if($offer_data['purchased'] == 1 || $offer_data['cancled'] == 1) show_error('You cannot cancel completed ations!');
        
        $this->db->where('id', $offer_data['id'])->update('marketplace_items', array('cancled' => 1));

        $this->inventory->setItemType('avatar_items');
        $this->inventory->avatar_items->setOwner($offer_data['user_id']);
        $this->inventory->addItem($offer_data['item_id']);

        redirect('market/index/'.$this->input->post('page'));
    }

    public function post_sell() {
        $item_id = $this->input->post('item_id');

        if($_SERVER['REQUEST_METHOD'] !== "POST") show_error('wrong req format');
        if( ! is_numeric($item_id)) show_error('item_id must be a valid number');
        if( ! is_numeric($this->input->post('ore_price'))) show_error('price must be a valid number');
        if($this->_total_current_items_for_sale($this->system->userdata['user_id']) >= $this->simoltanous_item_limit) show_error("You are limited to having $this->simoltanous_item_limit items for sale at a time");

        $avatar_item_query = $this->db->select('name, thumb, item_id')->where(array('item_id' => $item_id))->get('avatar_items');

        if($avatar_item_query->num_rows() > 0):
            $item_data = $avatar_item_query->row_array();
        else:
            show_error('Item could not be found!');
        endif;

        $this->inventory->setItemType('avatar_items');
        $this->inventory->avatar_items->setOwner($this->system->userdata['user_id']);
        $this->inventory->removeItem($item_id);

        if ($this->input->post('listing_type') == 'bid') {
            $bid_time_in_mins = $this->bid_time_options_in_mins[$this->input->post('bid_duration')];

            $this->db->set('published_at', 'NOW()', false)->insert('marketplace_items', [
                'user_id' => $this->system->userdata['user_id'],
                'username' => $this->system->userdata['username'],
                'item_id' => $this->input->post('item_id'),
                'sell_type' => 'bid',
                'item_thumbnail' => $item_data['thumb'],
                'item_name' => $item_data['name'],
                'ore_price' => $this->input->post('ore_price'),
                'bid_increment' => $this->input->post('min_bid_increase'),
                'finishes_at' => date('Y-m-d H:i:s', (time()+($bid_time_in_mins*60)))
            ]);
        } else {
            $this->db->set('published_at', 'NOW()', false)->insert('marketplace_items', [
                'user_id' => $this->system->userdata['user_id'],
                'username' => $this->system->userdata['username'],
                'item_id' => $this->input->post('item_id'),
                'item_thumbnail' => $item_data['thumb'],
                'item_name' => $item_data['name'],
                'sell_type' => 'buynow',
                'ore_price' => $this->input->post('ore_price'),
                'finishes_at' => date('Y-m-d H:i:s', (time()+($this->sale_time_limit_in_hrs*60*60)))
            ]);
        }

        redirect('market');
    }

    private function _marketplace_items($where_conditions = array(), $search_params = array()) 
    {
        $this->load->library('pagination');

        # escape ALL the values!
        $search_params = array_map(function($obj){ return $this->db->escape_str($obj); }, $search_params ?: []);

        if (isset($search_params['offer_type']) && strlen($search_params['offer_type']) > 1) {
            $where_conditions['sell_type'] = $search_params['offer_type'];
        }

        if (isset($search_params['item_name'])) $this->db->where("marketplace_items.item_name LIKE '%".$search_params['item_name']."%'");
        $total_rows = $this->db->get_where('marketplace_items', $where_conditions);

        $this->pagination->initialize([
            'base_url' => site_url('market/'.$this->uri->rsegment(2, 0)),
            'total_rows' => $total_rows->num_rows(),
            'per_page' => $this->total_items_per_page,
            'uri_segment' => 3
        ]);

        if (isset($search_params['item_name'])) $this->db->where("marketplace_items.item_name LIKE '%".$search_params['item_name']."%'");
        return $this->db->select('marketplace_items.*, purchaser.username as purchaser_username, bidder.username as bidder_username')
                        ->limit($this->total_items_per_page, $this->uri->segment(3, 0))
                        ->order_by('id', 'DESC')
                        ->join('users purchaser', 'purchaser.user_id = marketplace_items.purchased_by', 'left')
                        ->join('users bidder', 'bidder.user_id = marketplace_items.last_bidder_id', 'left')
                        ->get_where('marketplace_items', $where_conditions)
                        ->result_array();
    }

    private function _get_inventory($user_id = 0) 
    {
        $this->load->model('avatar_engine');

        $equipped_items = array_keys($this->avatar_engine->get_equiped_items($user_id));
        $inventory_query = $this->avatar_engine->get_user_inventory($user_id);

        foreach($inventory_query as $row) {
            if (
                in_array($row['layer_id'], $this->ignore_layers) || #excluded layers
                in_array($row['item_id'], $this->ignore_items) || # excluded items
                in_array($row['main_id'], $equipped_items)) # equipped items
                continue;

            if ( isset($items[$row['tab']][$row['item_id']]) ) {
                $items[$row['tab']][$row['item_id']]['amount'] += 1;
                $items[$row['tab']][$row['item_id']]['total'] += 1;

                continue;
            }

            $attr_array = [
                'data-key'    => $row['item_id'],
                'data-tab'    => strtolower($row['tab']),
                'data-type'   => 'inventory',
                'data-format' => 'item',
                'title'       => $row['itemname'],
                'class'       => 'magicTip',
            ];

            $href_attributes = '';
            foreach ($attr_array as $key => $value)
                $href_attributes .= ' '.$key.'="'.$value.'"';

            $this->item_names[$row['itemname']] = $row['item_id'];
            $items[$row['tab']][$row['item_id']] = [
                'name'     => $row['itemname'],
                'item_key' => $row['item_id'],
                'item_id'  => $row['item_id'],
                'thumb'    => $row['thumb'],
                'amount'   => $row['num'],
                'total'    => $row['num'],
                'type'     => 'item',
                'element'  => anchor('#', lazy_item_thumb('/images/items/'.$row['thumb']), $href_attributes)
            ];
        }

        return $items;
    }

    private function _total_current_items_for_sale($user_id = 0) {
        return $this->db->where('user_id =', $user_id)
                               ->where('cancled =', '0')
                               ->where('purchased =', '0')
                               ->get('marketplace_items')
                               ->num_rows();
    }

    private function _close_expired_offers()
    {
        $expired_items = $this->db->get_where('marketplace_items', array(
            'finishes_at <=' => date('Y-m-d H:i:s', time()),
            'purchased'  => 0,
            'cancled'    => 0
        ))->result_array();

        foreach ($expired_items as $offer_data):
            $this->inventory->setItemType('avatar_items');
            $this->accountant->setCurrencyType('ores');
    
            if ($offer_data['sell_type'] == 'buynow') {
                $this->db->where('id', $offer_data['id'])->update('marketplace_items', array('cancled' => 1));

                $this->inventory->setItemType('avatar_items');
                $this->inventory->setOwner($offer_data['user_id']);
                $this->inventory->avatar_items->setOwner($offer_data['user_id']);
                $this->inventory->addItem($offer_data['item_id']);

                $this->_send_inbox(
                    $offer_data["user_id"],
                    sprintf(
                        $this->lang->line("market_expired_message"), 
                        $offer_data["username"], 
                        $offer_data["item_name"]
                    )
                );
            } elseif ($offer_data['sell_type'] == 'bid') {
                $listing_fee = round($offer_data['ore_price'] * $this->listing_fee_percent);
                $received_total = round($offer_data['ore_price'] - $listing_fee);

                try {
                    $this->accountant->setOwner($offer_data['last_bidder_id'])->withdraw($received_total + $listing_fee);
                    $this->accountant->setOwner($offer_data['user_id'])->deposit($received_total);
                    $this->inventory->setOwner($offer_data['last_bidder_id']);
                    $this->inventory->avatar_items->setOwner($offer_data['last_bidder_id']);
                    $this->inventory->addItem($offer_data['item_id']);
                    $this->db->where('id', $offer_data['id'])->update('marketplace_items', array('purchased' => 1, 'purchased_by' => $offer_data['last_bidder_id']));

                    $bidder_info = $this->db->get_where('users', [ 'user_id' => $offer_data['last_bidder_id']])->row_array();

                    $this->_send_inbox(
                        $offer_data["user_id"],
                        sprintf(
                            $this->lang->line("market_sold_message"), 
                            $offer_data["username"], 
                            $offer_data["item_name"], 
                            urlencode($bidder_info), 
                            $bidder_info["username"]
                        )
                    );
                } catch (Exception $e) { // e.g: if the bidder doesn't have the money, cancel it
                    $this->db->where('id', $offer_data['id'])->update('marketplace_items', array('cancled' => 1));
                    $this->inventory->avatar_items->setOwner($offer_data['user_id']);
                    $this->inventory->addItem($offer_data['item_id']);

                    $this->_send_inbox(
                        $offer_data["user_id"],
                        sprintf(
                            $this->lang->line("market_expired_message"), 
                            $offer_data["username"], 
                            $offer_data["item_name"]
                        )
                    );
                }
            }
        endforeach;
    }

    private function _send_inbox($user_id = 0, $message='')
    {
        $this->db->set('date', 'NOW()', false)->insert('mail', array(
          'sender'           => $this->market_npc_id,
          'receiver'         => $user_id,
          'subject'          => "Notification from the Market",
          'message'          => $message,
          'status'           => 0,
          'included_users'   => json_encode([$user_id])
        ));

        $this->cache->delete('total_mail_'.$user_id);
    }
}
