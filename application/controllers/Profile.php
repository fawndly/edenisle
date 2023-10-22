<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Profile extends CI_Controller {
    /**
     * Home page
     * Profile main function
     * @access  public
     * @return  view
     * @route   n/a
     */
    public function index() {
        if (isset($this->system->userdata['user_id'])) $this->view_user($this->system->userdata['username']);
        else $this->view_user('Honeying');
    }

    /**
     * New function
     * Description of new function
     * @access  public
     * @param   string username
     * @return  output
     */
    public function view_user($username = '') {
        if (!isset($this->system->userdata['user_id'])) {
            $this->system->userdata['user_id'] = 0;
            $this->system->userdata['user_level'] = 'user';
        }

        $username = str_replace("_", " ", urldecode($username));
        $user_query = $this->db->get_where('users', ['username' => $username]);

        if ($user_query->num_rows() == 0)
            show_error('user could not be found.');

        $user_data = $user_query->row_array();

        $profile_data = [
            'likes'       => '',
            'dislikes'    => '',
            'profile_bio' => '',
            'profile_css' => '',
            'hobbies'     => ''
        ];

        $this->load->library('parser');
        $this->load->helper('forum');

        $friends = $this->db->select('username, users.user_id, user_email, friend_id, last_action')
                            ->join('users', 'users.user_id = friends.friend_id')
                            ->where('friends.user_id', $user_data['user_id'])
                            ->where('active', 1)
                            ->group_by('username')
                            ->order_by('last_action', 'DESC')
                            ->order_by('friendship_id', 'ASC')
                            ->limit(16)
                            ->get('friends')
                            ->result_array();

        $total_friends = $this->db->select('COUNT(1) as total')->get_where('friends', ['user_id' => $user_data['user_id']])->row()->total;
        $profile_query = $this->db->get_where('user_preferences', ['user_id' => $user_data['user_id']]);

        if ($profile_query->num_rows() > 0)
            $profile_data = array_merge($profile_data, $profile_query->row_array());

        $profile_comments = $this->db->select('profile_comments.*, users.username, users.user_id, users.user_level')
                                     ->select('IF(profile_comments.comment_author = '.$this->system->userdata['user_id'].', 1, 0) as modify', FALSE)
                                     ->join('users', 'users.user_id = profile_comments.comment_author')
                                     ->limit(6)
                                     ->order_by('profile_comments.comment_id', 'DESC')
                                     ->get_where('profile_comments', ['comment_profile' => $user_data['user_id']])
                                     ->result_array();

        if ((isset($this->system->userdata['user_level']) && $this->system->userdata['user_level'] != 'user') ||
            $this->system->userdata['username'] == $username)
            foreach ($profile_comments as $key => $comment)
                $profile_comments[$key]['modify'] = 1;

        $total_wished_items = $this->count_rows('wishlist', 'user_id', $user_data['user_id']);
        $total_comments     = $this->count_rows('profile_comments', 'comment_profile', $user_data['user_id']);
        $total_topics       = $this->count_rows('topics', 'topic_author', $user_data['user_id']);
        $total_posts        = $this->count_rows('topic_posts', 'author_id', $user_data['user_id']);

        $this->parser->parse('profile/view_profile', [
            'page_title'     => $user_data['username']."'s Profile",
            'username'       => $user_data['username'],
            'user_id'        => $user_data['user_id'],
            'total_posts'    => $total_posts,
            'total_topics'   => $total_topics,
            'total_wishes'   => $total_wished_items,
            'profile_data'   => $profile_data,
            'comments'       => $profile_comments,
            'friends'        => $friends,
            'total_comments' => $total_comments,
            'viewer_user_id' => ($this->session->userdata('user_id') ? $this->system->userdata['user_id'] : 0)
        ]);
    }

    /**
     * New function
     * Description of new function
     * @access  public
     * @param   none
     * @return  output
     */
    public function count_rows($table = 0, $key = '', $user_id = 0) {
        return $this->db->select('COUNT(1) as total')->get_where($table, [$key => $user_id])->row()->total;
    }

    /**
     * New function
     * Description of new function
     * @access  public
     * @param   none
     * @return  output
     */
    public function comment($action = 0, $comment_id = 0) {
        if (!$this->session->userdata('user_id'))
            redirect('signin');

        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

        if ($action == "add") {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('comment-text', 'Comment', 'required|htmlentities|addslashes');

            $user_query = $this->db->get_where('users', ['user_id' => $comment_id]);

            if ($user_query->num_rows() > 0) {
                $user = $user_query->row_array();
            } else {
                show_error('user could not be found.');
            }

            if ($this->form_validation->run() == FALSE)
                show_error("comment failed to send");

            $new_profile_comment_id = $this->db->set('comment_time', 'NOW()', FALSE)->insert('profile_comments', [
                'comment_author'  => $this->session->userdata("user_id"),
                'comment_profile' => $comment_id,
                'comment_text'    => $this->input->post('comment-text')
            ]);
            $postTime = date("Y-m-d H:i:s", time());

            if ($this->input->is_ajax_request()) {
                $json_response = [
                    'author_id'       => $this->session->userdata("user_id"),
                    'author_profile'  => site_url('profile/view/'.urlencode($this->session->userdata("username"))),
                    'author_color'    => user_color($this->session->userdata('user_level')),
                    'author_username' => $this->session->userdata("username"),
                    'message'         => stripslashes(nl2br($this->input->post("comment-text")))
                ];
            }

            $string = $this->input->post('comment-text');

            // $this->notification->broadcast([
                // 'receiver'          => $user_data['username'],
                // 'receiver_id'       => $comment_id,
                // 'notification_text' => $this->system->userdata['username'].' just commented on your profile: '.((strlen($string) > 24) ? substr($string, 0, 24).'...' : $string),
                // 'attachment_id'     => $new_profile_comment_id,
                // 'attatchment_type'  => 'comment',
                // 'attatchment_url'   => '/user/'.urlencode($user_data['username']),
            // ], FALSE);

            $safeName = urlencode($user['username']);           
            $this->notification->yuuNotifyUser([
                'user_id'   => $user['user_id'],
                'author_id' => $this->system->userdata['user_id'],
                'time'      => $postTime,
                'type'      => 2,
                'target'    => "/user/{$safeName}",
            ]);

            if ($this->input->is_ajax_request()) {
                $this->system->parse_json($json_response);
            } else {
                redirect("profile/view/{$safeName}");
            }
        } else if ($action == "delete" && strpos($referer,'sapherna')) {
            if ($this->input->is_ajax_request()) {
                $this->db->delete('profile_comments', ['comment_id' => $comment_id]);
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            show_error('No action specified');
        }
    }

    /**
     * New function
     * Description of new function
     * @access  public
     * @param   none
     * @return  output
     */
    public function load_more_comments($user_id = 0, $messages_loaded = 0) {
        // if( ! $this->input->if_ajax()) die("Error: AJAX REQUEST MISSING");

        if (!isset($this->system->userdata['user_id'])) {
            $this->system->userdata['user_id'] = 0;
            $this->system->userdata['user_level'] = 'user';
        }

        $profile_comments = $this->db->select('profile_comments.*, users.username, users.user_id, users.user_level')
                                     ->select('IF(profile_comments.comment_author = '.$this->system->userdata['user_id'].', 1, 0) as modify', FALSE)
                                     ->join('users', 'users.user_id = profile_comments.comment_author')
                                     ->limit(6, $messages_loaded)
                                     ->order_by('profile_comments.comment_id', 'DESC')
                                     ->get_where('profile_comments', ['comment_profile' => $user_id])
                                     ->result_array();

        if ($this->system->userdata['user_level'] != 'user')
            foreach ($profile_comments as $key => $comment)
                $profile_comments[$key]['modify'] = 1;

        foreach ($profile_comments as $comment) {
            $url = site_url('profile/view/' . urlencode($comment['username']));
            $userColor = user_color($comment['user_level']);
            echo "<li id=\"comment-{$comment['comment_id']}\" class=\"{$comment['comment_id']}\">";
            echo "<img src=\"/images/avatars/{$comment['user_id']}_headshot.png\" width=\"64\" height=\"64\" class=\"avatar_thumb\"/>";
            echo "<a href=\"{$url}\" style=\"color: {$userColor}; font-weight: bold;\">{$comment['username']}</a> said: <br />";
            echo "<p>" . stripslashes(nl2br($comment['comment_text'])) . "<small>" . _datadate($comment['comment_time']) . "</small></p>";
            if ($comment['modify']) {
                echo "<a href=\"/profile/comment/delete/{$comment['comment_id']}\" style=\"color:#ff7c7c\" class=\"delete small\"> (Delete)</a>";
            }
            echo "</li>";
        }
    }

    /**
     * New function
     * Description of new function
     * @access  public
     * @param   none
     * @return  output
     */
    /*
    public function delete_comment($comment_id) {
        if (!isset($this->system->userdata['user_id'])) {
            $this->system->userdata['user_id'] = 0;
            $this->system->userdata['user_level'] = 'user';
        }

        // Select all from profile_comments where user_id = comment_profile AND comment_id = $comment_id
        $query = $this->db->get_where('profile_comments', [
                            'comment_profile' => $this->system->userdata['user_id'],
                            'comment_id'      => $comment_id
                        ]);
        if($this->query->num_rows() > 0)
            show_error("I own comment!");
    }
    */

    /**
     * view_posts
     * View posts of selected user
     * @access  public
     * @param   integer userId
     */
    public function view_posts($userId = 0) {

        // check if user is signed in
        if( ! $this->session->userdata('user_id')):
            redirect("/auth/signin/?r=profile/view_posts/$userId");

        else:
        if (!is_numeric($userId))
            show_error('user_id must be valid');

        $user = $this->db->select('username')->get_where('users', ['user_id' => $userId])->row_array();

        if (count($user) == 0)
            show_error('User posts could not be found');

        $this->load->model('forum_engine');
        $this->load->helper('forum_helper');

        if (!$total_posts = $this->cache->get('total_posts_'.$userId)) {
            $total_posts = $this->db->select('COUNT(1) as total')->get_where('topic_posts', ['author_id' => $userId])->row()->total;
            $this->cache->save('total_posts_'.$userId, $total_posts, 60);
        }

        $this->load->library('pagination');
        $config['base_url'] = '/profile/view_posts/'.$userId;
        $config['total_rows'] = $total_posts;
        $config['per_page'] = 14;
        $config['uri_segment'] = 4;

        $this->pagination->initialize($config);

        $posts = $this->db->query("SELECT topics.topic_id as tp_id,
                                forums.staff,
                                topics.topic_title,
                                tp_new.post_id as ps_id,
                                tp_new.topic_post_id as tps_id,
                                tp_new.topic_id,
                                tp_new.author_id,
                                tp_new.post_time,
                                topic_post_text.text as post_body
                        FROM (SELECT post_id FROM topic_posts WHERE topic_posts.author_id = ".$userId." ORDER BY post_id DESC LIMIT ".$this->uri->segment(4, 0).", ".$config['per_page'].") tp_old
                        JOIN topic_posts tp_new ON tp_new.post_id = tp_old.post_id
                        JOIN `topic_post_text` ON `topic_post_text`.`post_id` = `tp_new`.`post_id`
                        JOIN `topics` ON `tp_new`.`topic_id` = `topics`.`topic_id`
                        JOIN `forums` ON `forums`.`forum_id` = `topics`.`forum_id`
                        ORDER BY tp_new.post_id DESC")->result_array();

        $data = [
            'posts'          => $posts,
            'user_id'        => $userId,
            'username'       => $user['username'],
            'user_data'      => $user,
            'total_posts'    => $total_posts,
            'page_body'      => 'forums',
            'page_title'     => $user['username'].'\'s posts',
            'posts_per_page' => $config['per_page']
        ];

        $this->system->quick_parse('profile/view_posts', $data);
        endif;
    }

    /**
     * view_topics
     * View selected user topics
     * @access  public
     * @param   integer userId
     */
    public function view_topics($userId = 0) {
        // check if user is signed in
        if( ! $this->session->userdata('user_id')):
            redirect("/auth/signin/?r=profile/view_topics/$userId");

        else:

        if (!is_numeric($userId))
            show_error('user_id must be valid');

        $user = $this->db->select('username')->get_where('users', ['user_id' => $userId])->row_array();

        if (count($user) == 0) 
            show_error('User posts could not be found');

        if (!$totalTopics = $this->cache->get('total_topics_'.$userId)) {
            $totalTopics = $this->db->select('COUNT(1) as total')->get_where('topics', ['topic_author' => $userId])->row()->total;
            $this->cache->save('total_topics_'.$userId, $totalTopics, 60);
        }

        $this->load->library('pagination');
        $config = [
            'base_url'    => "/profile/view_topics/{$userId}",
            'total_rows'  => $totalTopics,
            'per_page'    => 24,
            'uri_segment' => 4,
        ];

        $this->pagination->initialize($config);

        $user_topics = $this->db->select('*')
                                ->from('topics')
                                ->where('topics.topic_author', $userId)
                                ->where('forums.staff', 0)
                                ->join('forums', 'forums.forum_id = topics.forum_id')
                                ->order_by('topics.topic_id', 'desc')
                                ->limit($config['per_page'], $this->uri->segment(4, 0))
                                ->get()
                                ->result_array();

        $view_data = [
            'page_title' => $user['username'].'\'s topics',
            'page_body'  => 'forums profile',
            'topics'     => $user_topics,
            'user_data'  => $user,
            'username'   => $user['username'],
            'user_id'    => $userId
        ];

        $this->system->quick_parse('profile/view_topics', $view_data);
        endif;
    }
}
