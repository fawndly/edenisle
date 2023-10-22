<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Notification Model
 * @author(s) Tyler Diaz
 * @version 1.0
 * @copyright Tyler Diaz 2012
 **/
class Notification extends CI_Model
{
    var $table_name = 'notifications';

    function __construct() {
        parent::__construct();
        $this->load->library('chirp');
    }

    /**
     * New function
     * Description of new function
     * @access  public
     * @param   none
     * @return  output
     */
    public function fetch($limit = 12, $type = 'all', $user_id = 0) {
        if ($user_id === 0 && isset($this->system->userdata['user_id']))
            $user_id = $this->system->userdata['user_id'];

        $where_array['receiver_id'] = $user_id;
        $where_array['active'] = 1;
        if($type !== 'all')
            $where_array['attatchment_type'] = $type;

        $notification_query = $this->db->select($this->table_name.'.*, user_level, username, user_id')
                                       ->limit($limit)
                                       ->order_by($this->table_name.'.timestamp', 'DESC')
                                       ->join('users', $this->table_name.'.sender_id = users.user_id')
                                       ->get_where($this->table_name, $where_array);

        return $notification_query->result_array();
    }

    /**
     * New function
     * Description of new function
     * @access  public
     * @param   none
     * @return  output
     */
    public function clear($type = 'all', $user_id = 0) {
        if ($user_id === 0 && isset($this->system->userdata['user_id']))
            $user_id = $this->system->userdata['user_id'];

        $this->db->set('viewed_at', 'NOW()', false)->where(array('receiver_id' => $user_id, 'attatchment_type' => $type))->update($this->table_name, array('active' => 0));
        // Clear some kind of caching in here?
    }

    /**
     * Broadcast 
     * Broadcasts messages for users/friends
     * @access  public
     * @param   array
     * @param   boolean
     * @return  output
     */
    public function broadcast($notification_data = array(), $friendlist_broadcast = FALSE) {
        return false;
        $new_notification_data = array(
            'sender_id'         => $this->system->userdata['user_id'],
            'sender'            => $this->system->userdata['username'],
            'receiver_id'       => 0,
            'receiver'          => '',
            'notification_text' => '',
            'attachment_id'     => 0,
            'attatchment_type'  => 'undefined',
            'attatchment_url'   => NULL,
            'active'            => 1,
            'staff_topic'       => false
        );

        $notification_data = array_merge($new_notification_data, $notification_data);

        $return_value = FALSE;
        if ($friendlist_broadcast) {
            $friend_notifications = array();
            $friends = $this->system->get_friends();
            
            if (count($friends) > 0) {
                foreach ($friends as $friend) {
                    if ($notification_data['staff_topic'] && ($friend['user_level'] == 'user' || $friend['user_level'] == 'verify'))
                        continue;

                    $friendlist_id[] = $friend['friend_id'];
                    $friend_notifications[] = array(
                        'sender_id'         => $this->system->userdata['user_id'],
                        'sender'            => $this->system->userdata['username'],
                        'receiver_id'       => $friend['friend_id'],
                        'receiver'          => $friend['username'],
                        'notification_text' => $notification_data['notification_text'],
                        'attachment_id'     => $notification_data['attachment_id'],
                        'attatchment_type'  => $notification_data['attatchment_type'],
                        'attatchment_url'   => $notification_data['attatchment_url'],
                        'active'            => 1
                    );
                }

                foreach ($this->system->userdata['online_friends'] as $friend) {
                    if ($notification_data['staff_topic'] && ($friend['user_level'] == 'user' || $friend['user_level'] == 'verify'))
                        continue;

                    $chirp_array = json_encode(array(
                        'from' => $this->system->userdata['username'],
                        'uid'  => $this->system->userdata['user_id'],
                        'text' => $notification_data['notification_text'],
                        'id'   => $notification_data['attachment_id'],
                        'type' => $notification_data['attatchment_type'],
                        'url'  => $notification_data['attatchment_url'],
                        'for'  => NULL
                    ));

                    $this->chirp->broadcast($this->chirp->encrypt_key($friend['friend_id']), $chirp_array);
                }

                if (isset($friendlist_id)) {
                    $this->db->where_in('user_id', array_values($friendlist_id))->set('notification_counter', '(notification_counter+1)')->update('users');
                    $return_value = $this->db->insert_batch($this->table_name, $friend_notifications);
                }
            }
        } else {
            unset($notification_data['staff_topic']);
            $this->db->where('user_id', $notification_data['receiver_id'])->set('notification_counter', '(notification_counter+1)', FALSE)->update('users');
            $return_value = $this->db->insert($this->table_name, $notification_data);

            $chirp_array = json_encode(array(
                'from' => $this->system->userdata['username'],
                'uid'  => $this->system->userdata['user_id'],
                'text' => $notification_data['notification_text'],
                'id'   => $notification_data['attachment_id'],
                'type' => $notification_data['attatchment_type'],
                'url'  => $notification_data['attatchment_url'],
                'for'  => $notification_data['receiver_id']
            ));

            $this->chirp->broadcast($this->chirp->encrypt_key($notification_data['receiver_id']), $chirp_array);
        }

        return $return_value;
    }

    public function yuuNotifyUser($data = []) {
        $return = $this->db->insert('yuu_notification', $data);
        
        return $return;
    }
}


/* End of file notification.php */
/* Location: ./application/models/notification.php */
