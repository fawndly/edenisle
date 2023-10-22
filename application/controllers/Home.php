<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller
{
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

    public function index() {
        if (isset($this->session->userdata['user_id'])) {
            $notifications = $this->db->limit(8)
                                      ->select('id, author_id, username, is_read, target, type, time')
                                      ->order_by('time', 'DESC')
                                      ->where(['yuu_notification.user_id' => $this->system->userdata['user_id']])
                                      ->join('users', 'yuu_notification.author_id = users.user_id AND yuu_notification.dismissed = 0')
                                      ->get('yuu_notification')
                                      ->result_array();

            $unread = 0;
            $now = time();
            foreach($notifications as $k => $row) {
                $notifications[$k]['time'] = $this->getHumanTime($now - strtotime($row['time']));
                if ($row['is_read'] == 0)
                    $unread++;
            }

            if (!$latest_topics = $this->cache->get('dashboard_topics')) {
                $latest_topics = $this->load_recent_topics();
                $this->cache->save('dashboard_topics', $latest_topics, 3);
            }

            $announcements = $this->db->query('SELECT topics.last_post, topics.topic_title, topics.topic_id, topics.forum_id, topics.topic_author, topics.topic_status, topics.topic_time, forums.forum_name
                                              FROM  topics
                                              JOIN forums ON topics.forum_id = forums.forum_id
                                              AND forums.forum_id = 1
                                              ORDER BY topics.topic_id DESC
                                              LIMIT 3')->result_array();

            $this->cache->delete('spotlight_topic');

            if (!$spotlight_topic = $this->cache->get('spotlight_topic')) {
                $spotlight_topic = $this->db->limit(1)
                                            ->join('topics', 'topics.topic_id = spotlight_topics.topic_id')
                                            ->join('users', 'topics.topic_author = users.user_id')
                                            ->order_by('spotlight_topics.timestamp', 'DESC')
                                            ->get('spotlight_topics')
                                            ->row_array();

                $this->cache->save('spotlight_topic', $spotlight_topic, 900);
            }
            $user_id = $this->session->userdata('user_id');
            $token = ($user_id ? $this->generate_drop($user_id) : NULL );
            $view_data = [
                'page_title'       => 'Homepage',
                'page_body'        => 'home',
                'notifications'    => $notifications,
                'latest_topics'    => $latest_topics,
                'announcements'    => $announcements,
                'spotlight_topic'  => $spotlight_topic,
                'total_unread'     => $unread,
                'token'        => $token
            ];

            $this->system->view_data['scripts'][] = '/global/js/home/index.js?4';
            $this->system->quick_parse('home/index', $view_data);
        } else {
            foreach ($_COOKIE as $name => $value)
                delete_cookie($name);

            $this->session->sess_destroy();
            $this->load->view('home/landing_page');
        }
    }

    public function mark_read($id) {
        if (!$id)
            return;

        $this->db->where(['user_id' => $this->system->userdata['user_id'], 'id' => $id])
                 ->update('yuu_notification', ['is_read' => 1]);
    }

    /**
     * New function
     *
     * Description of new function
     *
     * @access  public
     * @param   none
     * @return  output
     **/
    public function load_recent_topics()
    {
        $query = $this->db->select('topics.last_post,
                                    topics.topic_title,
                                    topics.topic_id,
                                    topics.forum_id,
                                    topics.topic_author,
                                    topics.topic_status,
                                    forums.forum_name')
                            ->from('topics')
                            ->join('forums', 'topics.forum_id = forums.forum_id')
                            ->where(array('forums.forum_id !=' => 7, 'forums.staff !=' => 1))
                            ->order_by('topics.last_post', 'DESC')
                            ->limit(7);

        //Display forum games
        if ($this->input->get('games') != 'true') {
            $this->db->where('forums.forum_id !=', 10);
        }

        $latest_topics = $query->get()->result_array();

        if (!$this->input->is_ajax_request()) {
            return $latest_topics;
        } else {
            $html = $this->load->view('partials/dashboard_topics', array('latest_topics' => $latest_topics), TRUE);
            $this->system->parse_json(array('html' => $html));
        }
    }

     private function generate_drop($user_id)
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
