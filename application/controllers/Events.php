<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Events extends CI_Controller
{
    public function __construct() {
        parent::__construct();
    }

    /**
     * Events collect
     *
     * @access  public
     * @param   none
     * @return  view
     */
    public function collect()
    {
        if (!$this->session->user_id)
            redirect('signin');

        if ($_SERVER['REQUEST_METHOD'] == "POST" && strlen($this->input->post('url')) > 0) {
            $user_id = $this->session->userdata('user_id');

            $this->load->model('event_grab_engine');
            $grabInfo = $this->event_grab_engine->lastGrab();

            if ($grabInfo) {
                if ($this->event_grab_engine->canGrab()) {
                    $this->event_grab_engine->newGrab();
                } else {
                    redirect($this->input->post('url') . '?ref=event_fail');
                }

            } else {
                $this->event_grab_engine->firstGrab();
            }

            redirect($this->input->post('url'));
        }
        redirect('/home?ref=event_fail');
    }
}
