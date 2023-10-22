<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Accountant_Ores extends CI_Driver {
    private $CI;

    private $owner_id;

    public function __construct() {
        $this->CI = &get_instance();
        $this->CI->load->model('users');
    }

    public function setOwner($owner_id) {
        $this->owner_id = $owner_id;
    }

    public function balance() {
        $owner = $this->CI->users->find($this->owner_id, 'user_id, user_Ores');
        return $owner->Ores;
    }

    public function withdraw($amount) {
        $owner = $this->CI->users->find($this->owner_id, 'user_id, user_Ores');
        if ($owner->user_Ores < $amount):
            $this->CI->db->trans_rollback();
            throw new Insufficient_Currency_Exception($this->owner_id, $amount);
        endif;
        $owner->user_Ores -= $amount;
        $this->CI->users->update($owner);
    }

    public function deposit($amount) {
        $owner = $this->CI->users->find($this->owner_id, 'user_id, user_Ores');
        $owner->user_Ores += $amount;
        $this->CI->users->update($owner);
    }
}
