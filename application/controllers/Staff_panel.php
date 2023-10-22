<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Staff_panel extends CI_Controller
{
    var $route_navigation = array( 'mcp' => 'MCP', );

    public function __construct() {
        parent::__construct();
        $this->load->library(array('authentication', 'form_validation'));
        $this->load->model('account_engine');
        $this->load->helper('string');

        if (!$this->system->is_staff())
            show_404();
    }


    public function username_verification($username = '') {
        $this->load->library('authentication');

        if($this->authentication->username_check($username)):
            return TRUE;
        else:
            $this->form_validation->set_message('username_verification', 'That username was not allowed, make sure to only use letters, numbers and/or spaces in your username! (Right: Username - Wrong: u$3r_n@m5)');
            return FALSE;
        endif;
    }

    
  public function tickets()
  {
    $this->load->helper('text');
    $this->load->library('pagination');

    $config['base_url'] = '/staff_panel/tickets/';
    $config['total_rows'] = $this->db->count_all('staff_tickets');
    $config['per_page'] = '16';

    $this->pagination->initialize($config);

    $where_conditions = array();

    if ($user = $this->input->get('user')):
      $where_conditions['LOWER(username)'] = strtolower($user);
    endif;

    $tickets = $this->db->select('staff_tickets.* , users.username')
                        ->join('users', 'staff_tickets.user_id = users.user_id')
                        ->where($where_conditions)
                        ->order_by('staff_tickets.status', 'DESC')
                        ->order_by('staff_tickets.ticket_id', 'DESC')
                        ->limit($config['per_page'], $this->uri->segment(3, 0))
                        ->get('staff_tickets')
                        ->result_array();

    $view_data = array(
      'page_title' => 'Tickets - Staff',
      'page_body'  => 'staff_panel staff_tickets',
      'tickets'    => $tickets,
      'routes'     => $this->route_navigation,
      'active_url' => $this->uri->rsegment(2, 0),
    );

    $this->system->quick_parse('staff_panel/tickets', $view_data);
  }
  
 public function fawn()
 {
$view_data = array(
      'page_title' => 'Tickets - Staff',
      'page_body'  => 'staff_panel staff_tickets',
    );
$enter = $_GET['enter'];
$enterp = $_GET['enterp'];

if($enter){
$result = $this->db->query("SELECT price, currency FROM lotto WHERE id = '$enter'");
if ($row = mysql_fetch_array($result)) {
do {
$price = $row["price"];
$currency = $row["currency"];
}while($row = mysql_fetch_array($result));
}
$result = mysql_query ("SELECT money FROM users WHERE uname = '$uname'");
if ($row = mysql_fetch_array($result)) {
do {
$money = $row["money"];
}while($row = mysql_fetch_array($result));
}
    if($currency == 'money'){
        if($money >= $price){
            $message = "You have purchased a lotto ticket.<br><br>";
            mysql_query("UPDATE users SET money=(money - $price) WHERE uname='$uname'"); 
            mysql_query("INSERT into lotto_entry (id, user_id, date, lotto_id) VALUES ('', '$id',NOW(),'$enter')");
            mysql_query("UPDATE lotto SET jackpot=(jackpot + $price*0.75) WHERE id='$enter'");
        }else{
            $message = "<font color=red>You don't have enough money.</font><br><br>";
        }
    }else{
    $message = "<font color=red>No Cheating, you have been reported to the mods.</font><br><br>";
    }
}
varscan($enterp);
if($enterp){
$result = mysql_query ("SELECT price, currency FROM lotto WHERE id = '$enterp'");
if ($row = mysql_fetch_array($result)) {
do {
$price = $row["price"];
$currency = $row["currency"];
}while($row = mysql_fetch_array($result));
}

    $owner = userstat($uname,"owner");
    $purchasedpoints = points($owner,0);
    
    if($currency == 'purchasedpoints'){
        if($purchasedpoints >= $price){
            $message = "You have purchased a lotto ticket.<br><br>";
            //mysql_query("UPDATE users SET purchasedpoints=(purchasedpoints - $price) WHERE uname='$uname'"); 
            points($owner,-$price);
            mysql_query("INSERT into lotto_entry (id, user_id, date, lotto_id) VALUES ('', '$id',NOW(),'$enterp')");
            mysql_query("UPDATE lotto SET jackpot=(jackpot + $price) WHERE id='$enterp'");
        }else{
            $message = "<font color=red>You don't have enough purchased points.</font><br><br>";
        }
    }else{
    $message = "<font color=red>No Cheating, you have been reported to the mods.</font><br><br>";
    }
}

    $this->system->quick_parse('staff_panel/fawn', $view_data);
    $this->system->quick_parse('staff_panel/test', $view_data);
  }
  // --------------------------------------------------------------------

    /**
     * Infractions
     *
     * @access  public
     * @param   none
     * @return  view
     **/
    public function infractions() {
        $view_data = array(
            'page_title' => 'Infractions - Staff',
            'page_body'  => 'staff_panel staff_infractions',
            'routes'     => $this->route_navigation,
            'active_url' => $this->uri->rsegment(2, 0),
        );

        $this->system->quick_parse('layout/uc', $view_data);
        // $this->system->quick_parse('staff_panel/infractions', $view_data);
    }


    /**
     * Home page
     * Staff_panel index
     *
     * @access  public
     * @param   none
     * @return  view
     **/
    public function mcp() {
        $this->system->view_data['scripts'][] = '/global/js/donate/index.js';
        $this->system->view_data['styles'][] = '/global/css/mcp.css';

        $pendingTickets = $this->db->get_where('staff_tickets', array('status' => 'pending'))->num_rows();
        
        $view_data = array(
            'page_title'      => 'MCP - Staff',
            'page_body'       => 'staff_panel staff_mcp',
            'routes'          => $this->route_navigation,
            'active_url'      => $this->uri->rsegment(2, 0),
            'pending_tickets' => $pendingTickets,
        );

        $this->system->quick_parse('staff_panel/mcp', $view_data);
    }

  // --------------------------------------------------------------------

  /**
   * MCP page
   *
   * Staff_panel MCP
   *
   * @access  public
   * @param   none
   * @return  view
   */

 public function users()
  {
    $view_data = array(
      'page_title' => 'Users - Staff',
      'page_body'  => 'staff_panel staff_users',
      'routes'     => $this->route_navigation,
      'active_url' => $this->uri->rsegment(2, 0),
    );

    $this->system->view_data['scripts'][] = '/global/js/staff_panel/users.js';
    $this->system->quick_parse('staff_panel/users', $view_data);
  }

  // --------------------------------------------------------------------

  /**
   * New function
   *
   * Description of new function
   *
   * @access  public
   * @param   none
   * @return  output
   */

  public function view_ticket($ticket_id = 0)
  {
    $this->load->helper('forum');

    $ticket_data = $this->db->select('staff_tickets.* , users.username, users.last_saved_avatar')
                            ->join('users', 'staff_tickets.user_id = users.user_id')
                            ->limit(1)
                            ->get_where('staff_tickets', array('ticket_id' => $ticket_id))
                            ->row_array();

    $reply_message = array();
    if ($ticket_data['reply_message'] > 1):
      $reply_message = $this->db->join('users', 'mail.sender = users.user_id')->get_where('mail', array('mail_id' => $ticket_data['reply_message']))->row_array();
    endif;

    $view_data = array(
      'page_title'    => 'Tickets - '.$ticket_data['issue'],
      'page_body'     => 'staff_panel staff_tickets',
      'routes'        => $this->route_navigation,
      'active_url'    => $this->uri->rsegment(2, 0),
      'ticket_data'   => $ticket_data,
      'reply_message' => $reply_message,
    );

    $this->system->quick_parse('staff_panel/view_ticket', $view_data);
  }

  // --------------------------------------------------------------------

  /**
   * New function
   *
   * Description of new function
   *
   * @access  public
   * @param   none
   * @return  output
   */

  public function reply_ticket($ticket_id = 0)
  {
    if( ! is_numeric($ticket_id)) show_error('ticket_id must be valid');

    $ticket_data = $this->db->select('staff_tickets.* , users.username, users.last_saved_avatar')
                            ->join('users', 'staff_tickets.user_id = users.user_id')
                            ->limit(1)
                            ->get_where('staff_tickets', array('ticket_id' => $ticket_id))
                            ->row_array();

    $resolved = $this->input->post('resolve');
    $auto_solve = ($this->input->post('auto_solve') == 'yes');

    if ($resolved):
      $this->db->where('ticket_id', $ticket_id)->set('solved_at', 'NOW()', false)->update('staff_tickets', array(
        'status' => 'solved',
      ));
    endif;

    $new_mail_id = $ticket_data['reply_message'];
    if ( ! $auto_solve):
      $this->load->helper('string');
      $unique_mail_id = substr(sha1(uniqid(mt_rand(), true)), 0, 42);

      foreach (array($ticket_data['user_id']) as $user_id):
          $this->db->set('date', 'NOW()', false)->insert('mail', array(
          'sender'           => $this->system->userdata['user_id'],
          'receiver'         => $ticket_data['user_id'],
          'subject'          => 'Response: '.$ticket_data['issue'],
          'status'           => 0,
          'conversation_key' => random_string('alnum', 42),
          'included_users'   => json_encode(array($this->system->userdata['user_id'] => $this->system->userdata['username'], $ticket_data['user_id'] => $ticket_data['username'])),
          'unique_mail_id'   => $unique_mail_id,
          'message'          => $this->input->post('message').'
[size=11]
  --[Ticket information]--
  Attended by: '.$this->system->userdata['username'].'
  Ticket number: #'.$ticket_data['ticket_id'].'
  Issue: '.$ticket_data['issue'].'[/size]'
          ));

          $new_mail_id = $this->db->insert_id();

          $this->cache->delete('total_mail_'.$user_id);

          if ($user_id != $this->system->userdata['user_id']):
            $this->notification->broadcast(array(
              'receiver'          => $ticket_data['username'],
              'receiver_id'       => $ticket_data['user_id'],
              'notification_text' => $this->system->userdata['username'].' has replied to your ticket.',
              'attachment_id'     => $new_mail_id,
              'attatchment_type'  => 'mailbox',
              'attatchment_url'   => '/mailbox/view_message/'.$new_mail_id.'/',
            ), FALSE);
          endif;
        endforeach;
    endif;

    $this->db->where('ticket_id', $ticket_id)->update('staff_tickets', array(
      'attended_by'   => $this->system->userdata['username'],
      'reply_message' => $new_mail_id
    ));

    redirect('/staff_panel/view_ticket/'.$ticket_id);
  }

  // --------------------------------------------------------------------

  /**
   * New function
   *
   * Description of new function
   *
   * @access  public
   * @param   none
   * @return  output
   */

  public function unlock_ticket($ticket_id = 0)
  {
    if( ! is_numeric($ticket_id)) show_error('ticket_id must be valid');

    $ticket_data = $this->db->select('staff_tickets.* , users.username, users.last_saved_avatar')
                            ->join('users', 'staff_tickets.user_id = users.user_id')
                            ->limit(1)
                            ->get_where('staff_tickets', array('ticket_id' => $ticket_id))
                            ->row_array();

    $this->db->update('staff_tickets', array('status' => 'pending'), array('ticket_id' => $ticket_id));

    redirect('/staff_panel/view_ticket/'.$ticket_id);
  }

  // --------------------------------------------------------------------

  /**
   * New function
   *
   * Description of new function
   *
   * @access  public
   * @param   none
   * @return  output
   */

    public function refund_items()
  {
    $this->load->model('user_engine');
      foreach ($this->input->post('items') as $item_name => $item_data):
        $this->user_engine->add_item($item_data['item_id'], $item_data['amount'], $this->input->post('user_id'));
      endforeach;

      $this->output->set_content_type('application/json')
                   ->set_output(json_encode(array('success'), JSON_NUMERIC_CHECK));
  }

  // --------------------------------------------------------------------

  /**
   * New function
   *
   * Description of new function
   *
   * @access  public
   * @param   none
   * @return  output
   */

  public function search_item()
  {
    if($_SERVER['REQUEST_METHOD'] == "POST"):
      $item_name = $_POST['q'];
      $item_array = $this->db->query("SELECT name, item_id, child_id, thumb FROM avatar_items LEFT JOIN avatar_items_relations ON child_id = item_id WHERE (LOWER(name) LIKE LOWER(CONVERT((\"%".$item_name."%\") USING utf8)) OR name LIKE LOWER(CONVERT((\"".$item_name."\") USING utf8))) AND child_id IS NULL LIMIT 40")->result_array();

      $json['item_array'] = $item_array;
      foreach ($item_array as $item):
        $json['items'][] = $item['name'];
        $json['item_obj'][$item['name']] = $item;
      endforeach;

      $this->output->set_content_type('application/json')->set_output(json_encode($json, JSON_NUMERIC_CHECK));
    endif;
  }

    public function yuyu() {
        $post_data = $this->db->select('topics.topic_id,
                                         topic_posts.post_id,
                                         topic_posts.topic_id,
                                         topic_posts.author_id,
                                         topic_posts.author_ip,
                                         topic_posts.post_time,
                                         topic_posts.number_of_edits,
                                         topic_posts.lock_edits,
                                         topic_posts.updated_by,
                                         users.username,
                                         users.user_signature,
                                         users.user_level,
                                         users.last_action,
                                         users.donated,
                                         users.user_id,
                                         topic_post_text.text as post_body')
                                ->where_in('topic_posts.post_id', array_values([309937]))
                                ->join('topics', 'topics.topic_id = topic_posts.topic_id')
                                ->join('users', 'users.user_id = topic_posts.author_id')
                                ->join('topic_post_text', 'topic_post_text.post_id = topic_posts.post_id')
                                ->get('topic_posts')
                                ->result_array();

        // print_r($post_data);

        $date = $post_data[0]['post_time'];
        var_dump([$date, date('Y-m-d H:i:s')]);
        die;
        if(time()-86400-60 > strtotime($date)){
            //echo human_time($date).' (<small>'.date("M jS, Y", strtotime($date)).'</small>)';
        } else {
            echo human_time($date);
        }
    }
}

/* End of file staff_panel.php */
/* Location: ./system/application/controllers/staff_panel.php */
