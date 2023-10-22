<?php  if (!defined('BASEPATH'))
        exit('No direct script access allowed');

class Event_drop_model extends CI_Model
{
    function get_event_drop ($user_id)
	{
		 return $this->db->where('user_id', $user_id)
                                   ->from('event_drop_tokens')
                                   ->get()
                                   ->result_array();
	}

        function new_event_drop ($user_id, $timestamp = NULL, $token = NULL)
	{
                if($timestamp == NULL) {
			$timestamp = time();
			$token = md5($timestamp);
		}
		$data = array(
			'user_id' => $user_id,
   			'timestamp' => $timestamp,
   			'token' => $token
			);
		if(count($this->get_event_drop($user_id)) == 0)
			$this->db->insert('event_drop_tokens', $data);
		else
			$this->db->set('timestamp', $timestamp)
					->set('token', $token)
					->where('user_id', $user_id)
					->update('event_drop_tokens');

		return $this->get_event_drop_token($user_id);
        }

	function set_event_drop_token ($user_id, $token = NULL)
	{
		$this->db->set('token', $token)
				->where('user_id', $user_id)
				->update('event_drop_tokens');
	}

	function set_event_drop_timestamp ($user_id, $timestamp = NULL)
        {
		if($timestamp == NULL)
			$timestamp = time();
		$this->db->set('timestamp', $timestamp)
                                ->where('user_id', $user_id)
                                ->update('event_drop_tokens');
        }
	function get_event_drop_token ($user_id)
        {
                $token = $this->db->select('token')
                                ->where('user_id', $user_id)
                                ->from('event_drop_tokens')
				->get()
				->result_array();
		$token = (count($token) > 0 ? $token[0]['token'] : NULL);
		return $token;
        }

        function get_event_drop_timestamp ($user_id)
        {
		$timestamp = $this->db->select('timestamp')
                                ->where('user_id', $user_id)
                                ->from('event_drop_tokens')
                                ->get()
                                ->result_array();
		$timestamp = (count($timestamp) > 0 ? $timestamp[0]['timestamp'] : NULL);
                return $timestamp;

        }

}
