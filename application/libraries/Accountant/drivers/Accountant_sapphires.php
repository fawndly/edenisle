<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Accountant_sapphires extends CI_Driver {
    private $CI;
    private $owner_id;
    private $column_name = 'user_sapphires';

    public function __construct() {
        $this->CI = &get_instance();
        $this->CI->load->model('users');
    }

    public function setOwner($owner_id) {
        $this->owner_id = $owner_id;
    }

    public function balance() {
        $owner = $this->CI->users->find($this->owner_id, 'user_id, '. $this->column_name);
        return $owner->Ores;
    }

    public function withdraw($amount) {
        $owner = $this->CI->users->find($this->owner_id, 'user_id, '. $this->column_name);
        if ($owner->{$this->column_name} < $amount):
            $this->CI->db->trans_rollback();
            throw new Insufficient_Currency_Exception($this->owner_id, $amount);
        endif;
        $owner->{$this->column_name} -= $amount;
        $this->CI->users->update($owner);
    }

    public function deposit($amount) {
        $owner = $this->CI->users->find($this->owner_id, 'user_id, '. $this->column_name);
        $owner->{$this->column_name} += $amount;
        $this->CI->users->update($owner);
    }
}
