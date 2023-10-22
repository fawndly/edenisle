<?php  if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Event_grab_engine extends CI_Model
{
    var $grabData = array();

    function __construct() {
        parent::__construct();
    }

    function getEventGrabData($user_id = null) {
        if (!$user_id)
            $user_id = $this->system->userdata['user_id'];

        $grab = $this->db->get_where('event_grab', array('user_id' => $user_id));
        $this->grabData = $grab->result_array();

        return $this->grabData;
    }
    
    function canGrab($user_id = null) {
        if (!$user_id)
            $user_id = $this->system->userdata['user_id'];

        $checkTime = $this->lastGrab($user_id)->last_grab;
        
        return (($checkTime + 45) < time()) ? true : false;
    }

    function firstGrab($user_id = null) {
        if (!$user_id)
            $user_id = $this->system->userdata['user_id'];

        $this->db->insert(
            'event_grab',
            array(
                'user_id' => $user_id,
                'last_grab' => time()
            )
        );
    }

    function lastGrab($user_id = null) {
        if (!$user_id)
            $user_id = $this->system->userdata['user_id'];

        return $this->db->where('user_id', $user_id)->limit(1)->get('event_grab')->row();
    }
    
    function newGrab($user_id = null) {
        if (!$user_id)
            $user_id = $this->system->userdata['user_id'];

        return
            $this->db
                ->where('user_id', $user_id)
                ->update('event_grab', array('last_grab' => time()));
    }
}
