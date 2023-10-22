<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Notifications extends CI_Controller {
    var $route_navigation = array(
        'index' => 'notifications'
    );

    function __construct() {
        parent::__construct();
    }

    public function index() {
        $data = [
            'page_title' => 'Notifications',
            'page_body'  => 'notifications',
            'routes'     => $this->route_navigation,
            'active_url' => $this->uri->rsegment(2, 0),
        ];

        $this->system->quick_parse('notifications/index', $data);
    }

    function getHumanTime($s) {
        $m = $s / 60;
        $h = $s / 3600;
        $d = $s / 86400;
        if ($m > 1) {
            if ($h > 1) {
                if ($d > 1) {
                    return (int)$d.' day(s) ago';
                } else {
                    return (int)$h.' hour(s) ago';
                }
            } else {
                return (int)$m.' minute(s) ago';
            }
        } else {
            return (int)$s.' second(s) ago';
        }
    }

    public function get_notifications() {
        if ($this->system->userdata['user_id']) {
            $data = $this->db->limit(8)
                             ->select('id, author_id, username, is_read, target, type, time')
                             ->order_by('time', 'DESC')
                             ->where(['yuu_notification.user_id' => $this->system->userdata['user_id']])
                             ->join('users', 'yuu_notification.author_id = users.user_id AND yuu_notification.dismissed = 0')
                             ->get('yuu_notification')
                             ->result_array();

            $now = time();
            foreach ($data as $k => $row)
                $data[$k]['time'] = $this->getHumanTime($now - strtotime($row['time']));

            return $this->system->parse_json($data);
        }

        return $this->system->parse_json(['error' => -1]);
    }

    public function set_read($id = null) {
        $id = $id ?: $this->input->get('id');
        if (!$id)
            return;

        $this->db->where(['user_id' => $this->system->userdata['user_id'], 'id' => $id])
                 ->update('yuu_notification', ['is_read' => 1]);

        if ($this->input->is_ajax_request())
            return $this->system->parse_json(['success' => 1]);
    }

    public function read_all() {
        $this->db->where(['user_id' => $this->system->userdata['user_id']])
                 ->update('yuu_notification', ['is_read' => 1]);

        if ($this->input->is_ajax_request())
            return $this->system->parse_json(['success' => 1]);
    }

    public function dismiss($id = null) {
        $id = $id ?: $this->input->get('id');
        if (!$id)
            return;

        $this->db->where(['user_id' => $this->system->userdata['user_id'], 'id' => $id])
                 ->update('yuu_notification', ['dismissed' => 1]);

        if ($this->input->is_ajax_request())
            return $this->system->parse_json(['success' => 1]);
    }
}
?>
