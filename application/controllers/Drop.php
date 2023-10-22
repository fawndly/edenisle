<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Drop extends CI_Controller
{
    var $under_construction = FALSE;
    var $tradeable_currencies = array('Ores', 'Berries');
    var $item_names = array();
    var $ignore_layers = array();
    var $ignore_items = array();

    public function __construct() {
        parent::__construct();

        $this->system->view_data['styles'][] = 'http://fonts.googleapis.com/css?family=Nunito:400,300';

        if (!$this->session->user_id)
            redirect('/auth/signin?r=trades');

        if ($this->under_construction && ! $this->system->is_staff() && $this->system->userdata['user_id'] != 19 && $this->system->userdata['user_id'] != 598)
            show_error('Trades are under construction');
    }

    /**
     * Home page
     * Trades main function
     * @access  public
     * @param   none
     * @return  view
     * @route   n/a
     */
    public function index($token = NULL)
    {

        $user_id = $this->session->userdata['user_id'];

        $this->grab();
    }

    public function grab($token = NULL)
    {
        $this->load->model('event_drop_model');
        $this->load->model('user_engine');
        $user_id = ( array_key_exists('user_id', $this->session->userdata) ?  $this->session->userdata['user_id'] : NULL);
        if($user_id) {
    
            $time_now = time();
            $time_drop = (int)$this->event_drop_model->get_event_drop_timestamp($user_id);
            $time_cooldown = $this->config->item('drop_item')['cooldown'];
            $time_elapsed = $time_now - $time_drop;
    
            $validtoken = ($token == NULL ||
                                    $token != $this->event_drop_model->get_event_drop_token($user_id) ||
                                    $time_elapsed > $time_cooldown ? false : true);
    
            $this->event_drop_model->set_event_drop_token($user_id, NULL);
            $prize_type = "";
            $prize = 0;
            if($validtoken) {
//              $grab = $this->generate_prize();
//              $prize_type = $grab['prize_type'];
//              $prize = $grab['prize'];
                $prize = 1;
//              if($prize_type == "special_currency")
                    $this->user_engine->add('special_currency', $prize);
                
            }
//          $this->event_drop_model->new_event_drop(121);
            $referred_from = $this->session->userdata('referred_from');
    //      redirect($referred_from, 'refresh');

//          echo "<pre>".json_encode($_SERVER['HTTP_REFERER'], JSON_PRETTY_PRINT)."</pre>";
        }
        else
            $validtoken = false;
                $view_data = array(
                        'page_title' => 'You found a leaf!',
                        'page_body'  => 'drop_grab',
            'token' => $token,
            'prize_type' => $prize_type,
            'prize' => $prize,
            'validtoken' => $validtoken
                );
    
                $this->system->quick_parse('event/drop', $view_data);
        }

    private function generate_prize()
    {
        $prize_type = "";
        $prize = 0;
        if(mt_rand(1, 100) <= $this->config->item('drop_item')['special_currency_rate']) {
            $prize_type = "special_currency";
            $prize = 1;
        }
        else {
            $prize_type = "item";
            $prize_index = mt_rand(0, count($this->config->item('item_prize_list'))-1);
            $prize = $this->config->item('item_prize_list')[$prize_index];
            
        }
        return array(
            "prize_type" => $prize_type,
            "prize" => $prize
            );
    }

    public function generate_grab($user_id)
    {
        if(!$this->config->item('drop_item')['enabled'])
            return NULL;
        $this->load->model('event_drop_model');
        $token = $this->event_drop_model->get_event_drop_token($user_id);
        if(count ($this->event_drop_model->get_event_drop($user_id)) == 0)
            $token = $this->event_drop_model->new_event_drop($user_id);
        else {
            $time_now = time();
                    $time_drop = (int) $this->event_drop_model->get_event_drop_timestamp($user_id);
                    $time_cooldown = $this->config->item('drop_item')['cooldown'];
                    $time_elapsed = $time_now - $time_drop;
    
            if ($time_elapsed > $time_cooldown)
                $token =  $this->event_drop_model->new_event_drop($user_id);    
        }
        return $token;
    }
}
