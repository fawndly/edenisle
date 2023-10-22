<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Auth extends CI_Controller {
    private $key = 'adj39j0amc892'; // This is arbitrary, it's used for the Encrpytion class.

    var $starting_ores = 50;
    var $hair=array(3600,3567,3516,3371,3337,3400);
    var $text_captcha_key = '264fxitiji3owowogskggkkkwbqim6yw';
    var $starter_equip = array(3648,3,3573,3433,3698,3516,3541);
    var $user_gender = 'male';
    var $starter_items = array(
         '1',    // Base (pale)
    );

    var $signup_options = array(
        'hairs'     => array(3600,3567,3516,3371,3337,3400),
        'eyes'      => array(3573,3546,3444,3492),
        'shirts'    => array(3921,4358),
        'pants'     => array(3968,4429,4466),
        //'underwear' => array(3797,4109,4074),
        'shoes'     => array(3750,4393,4757),
        'accessory' => array(7),
        'base'      => array(1, 3, 2, 4, 5, 6),
    );

    var $default_signup_options = array(
        'hairs'     => 1,
        'eyes'      => 3,
        'shirts'    => 3,
        'pants'     => 3,
        'underwear' => 1,
        'accessory' => 3,
        'base'      => 3,
        'gender'    => 'male',
        'other'     => array(3)
    );

    function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation', 'authentication', 'user_agent'));
        $this->load->helper(array('string',   'email', 'cookie'));
        $this->form_validation->set_error_delimiters('<li>', '</li>');
        $this->load->model('auth_engine');

        $this->system->view_data['styles'][] = 'http://fonts.googleapis.com/css?family=Nunito:400,300';

        $this->form_validation->set_message('required', '<strong>%s</strong> field cannot be empty');

        $query = $this->db->get('avatar_config'); // Getting config options...

        foreach($query->result_array() as $row):
            $this->avatar_config[$row['key']] = $row['value'];
        endforeach;

        $this->user_key = md5($this->input->ip_address());
    }


    /**
     * Auth Signin
     * Allows the users to signin in to their account(s)
     */
    function signin()
    {
        if($this->session->userdata('user_id')) redirect('');

        $this->system->view_data['scripts'][] = '/global/js/auth/signin.js';

        $attempts = $this->db->get_where('login_logs', array('success' => 0, 'log_ip' => $this->input->ip_address(), 'log_time >' => (time()-300)))->num_rows();

        if($attempts > 5):
            $this->system->yield('error', lang('header_login_attemps_max'), lang('login_attemps_max'));
            return;
        endif;

        $this->form_validation->set_rules('username', 'username', 'required|callback_username_verification');
        $this->form_validation->set_rules('password', 'password', 'required');

        if ($this->form_validation->run() == TRUE):
            if($this->authentication->signin($this->input->post('username'), $this->input->post('password'))):
                $this->db->insert('login_logs', array(
                    'log_user' => $this->input->post('username'),
                    'log_ip'   => $this->input->ip_address(),
                    'log_time' => time(),
                    'success'  => 1,
                    'browser'  => $this->agent->agent_string()
                ));

                if($this->input->post('redirect') == '' || stristr($this->input->post('redirect'), 'auth')):
                    redirect('home');
                else:
                    redirect($this->input->post('redirect'));
                endif;
            else:
                $this->db->insert('login_logs', array(
                    'log_user' => $this->input->post('username'),
                    'log_ip'   => $this->input->ip_address(),
                    'log_time' => time(),
                    'success'  => 0,
                    'browser'  => $this->agent->agent_string()
                ));

                $this->load->helper('email');

                // After 2 tries, show them this
                if($attempts >= 1 && $attempts < 3):
                    $error_message = $this->input->post('username').'? Is that you? Did you lock youself out of your avatar? Oh dear, here\'s a handy link to <a href="/auth/forgot_password">reset your password</a> so you can get yourself back in.';
                elseif($attempts > 3):
                    $error_message = $this->input->post('username').'? Is that you? Are you <em>still</em> locked youself out of your avatar? If your email is also having issues, we can go through strict procedures to prove you own the account if you want to email us at <a href="mailto:admin@sapherna.com?subject=\'I need to recover my account\'" target="_window">admin@sapherna.com</a>';
                else:
                    $error_message = 'That password was incorrect.';
                endif;

                if(valid_email($this->input->post('username'))):
                    $user_query = $this->db->get_where('users', array('email' => $this->input->post('username')));
                    if($user_query->num_rows() <= 0):
                        $error_message = 'That email was not found in our system, did you want to <a href="/signup?email='.$this->input->post('username').'">join our community</a> or <a href="mailto:admin@sapherna.com?subject=\'I need help with my account\'" target="_window">contact an admin for some assistance</a>?';
                    endif;
                else:
                    $user_query = $this->db->get_where('users', array('username' => $this->input->post('username')));
                    if($user_query->num_rows() <= 0):
                        $error_message = 'That username was not found in our system, would you like to <a href="/signup?username='.$this->input->post('username').'">join our community</a> or <a href="mailto:admin@sapherna.com?subject=\'I need help with my account\'" target="_window">contact an admin</a> for some assistance?';
                    endif;
                endif;

                $this->system->quick_parse('auth/signin', array('page_title' => lang('signin_title'), 'page_body' => 'signin no_sidebar blue_gloss', 'error_msg' => $error_message));
            endif;

        else:
            $this->system->quick_parse('auth/signin', array('page_title' => lang('signin_title'), 'page_body' => 'signin no_sidebar blue_gloss'));
        endif;

    }

    /**
     * Auth Sigout
     * Allows users to signout of their account and become annonymous
     */
    function signout()
    {
        $this->authentication->signout();

        $this->system->view_data['scripts'][] = '/global/js/auth/signout.js';

        $this->session->userdata = array();
        $this->system->userdata = array();

        $reasons_to_return = array(
            'New things may have been released or updated while you were gone!',
            'Your friends might be having a blast without you.',
            'Your avatar might get feel abandoned all on it\'s own for too long.',
            'Your avatar\'s clothing may have gone out of style.',
            'Someone may have sent you a private message while you were away.',
            'You might be able to find a really good deal on the Marketplace.',
            'You\'ll be able to hunt in the forest again.',
            'Someone may have sent you a really shiney gift.',
            'Your friends could be waiting for you to have some fun.',
            'You could participate on a site event.',
            'You might miss a milestone item giveaway.',
            'Someone could be waiting to complete a trade with you.',
            'New items could have been added to the shops.',
            'You could make new friends and hang out with old ones.',
            'The sapherna zebra could be on the loose. Wouldn\'t want to miss that!',
        );

        $view_data = array(
            'page_title' => 'Goodbye!',
            'page_body' => 'signed_out blue_gloss',
            'reasons' => $reasons_to_return,
            'key' => array_rand($reasons_to_return)
        );

        $this->system->quick_parse('auth/signout', $view_data);
    }

    /**
     * Forgot Password
     * Allow users to retrieve a lost password
     */
    public function forgot_password()
    {
        $this->system->view_data['scripts'][] = '/global/js/auth/forgot_password.js';

        $this->form_validation->set_rules('email', 'email', 'required|valid_email|callback_existing_email');
        $email_sent = FALSE;

        if ($this->form_validation->run()):
            $user_query = $this->db->get_where('users', array('user_email' => $this->input->post('email')));
            if($user_query->num_rows() <= 0) show_error('Email could not be found.');

            $user_data = $user_query->row_array();

            $new_key = random_string('alnum', 32);
            $this->db->insert('password_recovery_keys', array(
                'key' => $new_key,
                'user_id' => $user_data['user_id']
            ));

            $this->load->library('email');
            $this->email->initialize(array(
                'protocol'  => 'smtp',
                'smtp_host' =>  $this->config->item('smtp_host'),
                'smtp_port' => $this->config->item('smtp_port'),
                'smtp_user' => $this->config->item('smtp_user'),
                'smtp_pass' => $this->config->item('smtp_pass'),
                'mailtype'  => 'html',
                'charset'   => 'iso-8859-1',
                'crlf' => "\r\n",
                'newline' => "\r\n"
            ));


            $this->email->from('noreply@sapherna.com', 'Sapherma');
            $this->email->to($this->input->post('email'));
            $this->email->subject('Recover Your Password');

            $this->email->message("Hi ".$user_data['username']."!<br /><br />

We rushed over to send you this email as soon as you told us you forgot your password. Don't worry, we can help you set a new one!<br /><br />

All you need to do to set up your new password is visit the link below:<br />
".$this->config->item('base_url')."auth/redeem_password/".$new_key."/?user=".$user_data['user_id']);

            if ($this->email->send()){
                $email_sent = TRUE;
                log_message('info', "EMAIL SENT!");
            }


            log_message('info', $this->email->print_debugger());

        endif;

        $view_data = array(
            'page_title' => 'Recover your password',
            'page_body'  => 'blue_gloss',
            'sent'       => $email_sent
        );

        $this->system->quick_parse('auth/forgot_password', $view_data);
    }

    // --------------------------------------------------------------------

    /**
     * Existing Email
     * Check if the existing email exists or not
     */
    public function existing_email($email = '')
    {
        $user_query = $this->db->get_where('users', array('user_email' => $email));
        if($user_query->num_rows() > 0):
            return TRUE;
        else:
            $this->form_validation->set_message('existing_email', 'That email is not used on any account... <a href="/signup">sign up</a> and join the fun now!');
            return FALSE;
        endif;
    }

    /**
     * Auth Redeem password
     * Function that is sent on the password recovery email link
     */
    function redeem_password($key = '')
    {
        $this->system->view_data['scripts'][] = '/global/js/auth/redeem_password.js';

        if(ctype_alnum($key) && strlen($key) == 32 && is_numeric($this->input->get('user'))): // [SECURITY] Make sure it's a legit key
            $day = (60*60*24);
            $day_ago = date("Y-m-d H:i:s", (time()-$day));
            $user_query = $this->db->get_where('password_recovery_keys', array('user_id' => $this->input->get('user'), 'key' => $key, 'created_at >' => $day_ago));

            if($user_query->num_rows() <= 0) show_error('Password recovery key could not be found for this user. Please submit a password recovery request <a href="/auth/forgot_password">here</a>');

            $user_data = $user_query->row_array();

            $this->form_validation->set_rules('new_password', '<strong>Password</strong>', 'required|min_length[6]');
            $this->form_validation->set_rules('confirm_new_password', '<strong>Confirm new password</strong>', 'required|min_length[6]|matches[new_password]');

            if ($this->form_validation->run() == FALSE):
                $this->system->quick_parse('auth/redeem_password', array(
                    'page_title' => 'Reset your password',
                    'page_body'  => 'signin reset_password blue_gloss',
                    'form'       => 'pending',
                    'key'        => $key
                ));
            else:
                $update_user_data = array(
                    'user_pass'        => $this->authentication->hash_password($this->input->post('new_password')),
                    'auto_login_token' => 'RESET_'.random_string('alnum', 32),
                    'verified_email'   => 1
                );

                $new_user_id = $this->db->where(array('user_id' => $this->input->get('user')))->update('users', $update_user_data);
                $this->db->limit(1)->delete('password_recovery_keys', array('user_id' => $this->input->get('user'), 'key' => $key));

                $this->system->quick_parse('auth/redeem_password', array(
                    'page_title' => 'Reset your password',
                    'page_body'  => 'signin reset_password blue_gloss',
                    'form'       => 'completed'
                ));
            endif;
        else:
            show_error('Invalid data format type.');
        endif;
    }

    /**
     * VALIDATION: Username exists
     * Make sure a username is not taken
     */
    public function username_exists($val = 0)
    {
        $user_query = $this->db->get_where('users', array('username' => $val));
        if($user_query->num_rows() > 0):
            $this->form_validation->set_message('username_exists', 'The username \"'.$val.'\" has been taken.');
            return FALSE;
        else:
            if( ! preg_match("/^([a-z0-9\s])+$/i", $val)):
                $this->form_validation->set_message('username_exists', 'The username can only have letters, numbers and spaces.');
                return FALSE;
            else:
                return TRUE;
            endif;
        endif;
    }

    /**
     * VALIDATION: Email exists
     * Make sure an email-address is not taken
     */
    public function email_exists($val = 0)
    {
        $user_query = $this->db->get_where('users', array('user_email' => $val));
        if($user_query->num_rows() > 0):
            $user_data = $user_query->row_array();
            $this->form_validation->set_message('email_exists', 'The email \''.$val.'\' has been taken by \''.$user_data['username'].'\'.');

            return FALSE;
        else:
            return TRUE;
        endif;
    }

    /**
     * New function
     * Description of new function
     */
    public function username_verification($username = '')
    {
        $this->load->library('authentication');

        if($this->authentication->username_check($username)):
                return TRUE;
        else:
            $this->form_validation->set_message('username_verification', 'That username was not allowed, make sure to only use letters, numbers and/or spaces in your username! (Right: Username - Wrong: u$3r_n@m5)');

            return FALSE;
        endif;
    }

    /**
     * Signup page
     * Description of new function
     */
    public function signup($avatar_key = '') {
        // show_error('Signup is disabled!');
        $this->system->view_data['scripts'][] = '/global/js/pixi.min.js';
        $this->system->view_data['scripts'][] = '/global/js/auth/signup.js';
        $this->system->view_data['styles'][] = '/global/css/signup.css';

        if ($this->input->is_ajax_request()) {
            $this->load->library('form_validation');

            $this->form_validation->set_rules('username', 'username', 'required|callback_username_exists');
            $this->form_validation->set_rules('password', 'password', 'required|min_length[6]');
            $this->form_validation->set_rules('email', 'email', 'required|valid_email|callback_email_exists');
            
            if ($this->form_validation->run() == TRUE) {
                // Captcha
                $recaptcha = $this->input->post('g-recaptcha-response');
                $response = $this->recaptcha->verifyResponse($recaptcha);
                if (!$response['success']) {
                    return $this->system->parse_json(array(
                        'text' => '<li>Check the Captcha!</li>',
                        'status' => false
                    ));
                }

                $username = $this->input->post('username');
                $password = $this->input->post('password');
                $email    = $this->input->post('email');
                $reffered = $this->input->post('referal');
                $day   = $this->input->post('bdDay');
                $month = $this->input->post('bdMonth');
                $year  = $this->input->post('bdYear');

                $refferalId = 0;
                if ($reffered) {
                    $referalQuery = $this->db->get_where('users', array('username' => $reffered));
                    if ($referalQuery->num_rows() > 0)
                        $refferalId = $referalQuery->row()->user_id;
                }
                
                $new_user_data = array(
                    'username'          => $username,
                    'user_ip'           => $this->input->ip_address(),
                    'user_email'        => $email,
                    'user_pass'         => $password,
                    'user_Ores'         => $this->starting_ores,
                    'last_saved_avatar' => time(),
                    'user_level'        => 'verify',
                    'avatar_gender'     => ($this->input->post('gender') == 'm') ? 'Male' : 'Female',
                    'birthday'          => ($year && $month && $day) ? "{$year}-{$month}-{$day}" : null,
                    'reffered'          => (int) $refferalId
                );

                $user_id = $this->authentication->create_user($new_user_data);
                
                // Give basic items
                $possibleItems = array();
                foreach ($this->signup_options as $cathegory) {
                    foreach ($cathegory as $item) {
                        $possibleItems[$item] = true;
                    }
                }

                // Validates the informed items
                $selectedItems = $this->input->post('items');
                $startItems = array();
                foreach($selectedItems as $item) {
                    if (isset($possibleItems[$item]))
                        $startItems[] = array(
                            'item_id'   => $item,
                            'user_id'   => $user_id,
                            'equipped'  => 1,
                            'trying_on' => 1
                        );
                }

                if (count($startItems) > 0)
                    $this->db->insert_batch('user_items', array_values($startItems));

                $new_key2 = random_string('alnum', 32);
                $this->db->insert('register_keys', array(
                    'key' => $new_key2,
                    'user_id' => $user_id,
                    'created_at' => date('Y-m-d H:i:s'),
                ));

                $this->load->library('email', array(
                    'protocol'  => 'smtp',
                    'smtp_host' => $this->config->item('smtp_host'),
                    'smtp_port' => $this->config->item('smtp_port'),
                    'smtp_crypto' => 'tls',
                    'smtp_user' => $this->config->item('smtp_user'),
                    'smtp_pass' => $this->config->item('smtp_pass'),
                    'mailtype'  => 'html',
                    'charset'   => 'utf-8'
                ));

                $this->email->set_newline("\r\n");

                $link = $this->config->item('base_url'). "auth/redeem_account/?user={$user_id}&key={$new_key2}";
                
                $this->email->from('noreply@sapherna.com', 'Sapherna');
                $this->email->to($this->input->post('email'));
                $this->email->subject('Verify Your Account');
                $this->email->message("Hi {$username},<br/><br/>
                    <strong>Welcome to Sapherna!</strong> In order for us to verify that you are who you say you are, please click the link below. Please remember that you will not be allowed to post until then, so make sure you click it as soon as possible!<br/><br/>
                    All you need to do to start using your new account is to visit the link below:<br/>
                    $link");

                $this->email->send();

                if ($this->input->get('avatar')) {
                    $image = $this->get_preview(TRUE);
                    $path = realpath(BASEPATH.$this->avatar_config['avatar_path']).'/'.$user_id.'.png';

                    if($path) {
                        imagepng($image, $path);
                        $this->headshot($user_id, 'new', true);
                    }
                } else {
                    $default_avatar = realpath(BASEPATH.'../images/avatars/default.png');
                    $default_headshot = realpath(BASEPATH.'../images/avatars/default_headshot.png');

                    $avatar = BASEPATH.'../images/avatars/'.$user_id.'.png';
                    $headshot = BASEPATH.'../images/avatars/'.$user_id.'_headshot.png';

                    copy($default_avatar, $avatar);
                    copy($default_headshot, $headshot);
                }

                $this->cache->delete('tmp_sn_'.$this->user_key);
                $this->cache->delete('question_sn_'.$this->user_key);
                $this->cache->save('remember_sn_'.$this->user_key, 1, 2700);
                $this->load->model('forest_engine');
                $this->forest_engine->new_forester($user_id);

                return $this->system->parse_json(array(
                    'text' => 'Welcome aboard!',
                    'status' => true
                ));
            }

            return $this->system->parse_json(array(
                'text' => validation_errors(),
                'status' => false
            ));
        }

        $signup_options = array();
        foreach ($this->signup_options as $key => $item_ids)
            $signup_options[$key] = $this->db->where_in('item_id', $item_ids)->get('avatar_items')->result_array();

        $view_data = array(
            'page_title'     => 'Create your account',
            'page_body'      => 'auth signup no_sidebar blue_gloss',
            'recaptcha'      => false,
            'signup_options' => $signup_options
        );

        $this->system->quick_parse('auth/signup', $view_data);
    }

    public function resend() {
        $uid = $this->session->userdata('user_id');
        if (!$uid)
            redirect('');

        $usr = $this->db->get_where('users', ['user_id' => $uid])->row();
        
        $key = random_string('alnum', 32);
        $this->db->where('user_id', $uid)
                 ->update('register_keys', ['key' => $key]);

        $this->load->library('email', array(
            'protocol'  => 'smtp',
            'smtp_host' => $this->config->item('smtp_host'),
            'smtp_port' => $this->config->item('smtp_port'),
            'smtp_crypto' => 'tls',
            'smtp_user' => $this->config->item('smtp_user'),
            'smtp_pass' => $this->config->item('smtp_pass'),
            'mailtype'  => 'html',
            'charset'   => '8859-1'
        ));

        $this->email->set_newline("\r\n");
        $this->email->set_crlf( "\r\n" );

        $link = $this->config->item('base_url'). "auth/redeem_account/?user={$uid}&key={$key}";
        
        $this->email->from('noreply@sapherna.com', 'Sapherna');
        $this->email->to($usr->user_email);
        $this->email->subject('Verify Your Account');
        $this->email->message("Hi {$usr->username}<br/><br/>
            <strong>Welcome to Sapherna!</strong> In order for us to verify that you are who you say you are, please click the link below. Please remember that you will not be allowed to post until then, so make sure you click it as soon as possible!<br/><br/>
            All you need to do to start using your new account is to visit the link below:<br/>
            $link");
            
        if ($this->email->send()) {
            redirect('');
        } else {
            echo 'There was a problem sending you a email... Sorry for the incovenience. <a href="/home/">return</a>';
            die;
        }
    }

    /**
     * Avatar signup
     * Allow users to customize their avatar before signing up
     */
    public function avatar_signup() {
        $this->system->view_data['scripts'][] = '/global/js/pixi.min.js';
        $this->system->view_data['scripts'][] = '/global/js/auth/avatar_signup.js';

        $equipped_items = $this->_get_temp_data();
        $signup_options = array();

        foreach ($this->signup_options as $key => $item_ids)
            $signup_options[$key] = $this->db->where_in('item_id', $item_ids)->get('avatar_items')->result_array();

        $view_data = array(
            'page_title'     => 'Create your account',
            'page_body'      => 'auth signup no_sidebar blue_gloss',
            'signup_options' => $signup_options,
            'equipped_items' => $equipped_items,
            'gender'         => $this->user_gender
        );

        $this->system->quick_parse('auth/avatar_signup', $view_data);
    }

    /**
     * Get avatar preview
     * Show pre-signup avatar preview
     */
    public function get_preview($return = false)
    {
        $equipped_items = $this->_get_temp_data();
        $items = $this->_get_subimages($equipped_items);

        $avatar_frame = imagecreatetruecolor($this->avatar_config['width'], $this->avatar_config['height']);
        $avatar_frame = $this->_gd_transparecy($avatar_frame);

        foreach ($items as $item):
            imagecopy($avatar_frame, imagecreatefrompng(realpath(BASEPATH.$this->avatar_config['items_path'].'/'.$item['image_path'])), 0, 0, 0, 0, $this->avatar_config['width'], $this->avatar_config['height']);
        endforeach;

        if ($this->input->get('fade') == 1):
            $this->filter_opacity($avatar_frame, 30);
        endif;

        if ( ! $return):
            header('Content-type: image/png');
            imagepng($avatar_frame);
            imagedestroy($avatar_frame);
        else:
            return $avatar_frame;
        endif;
    }

    /**
     * Filter Opacity
     * Manipulate image opacity
     */
    public function filter_opacity(&$img, $opacity)
    {
        if( ! isset($opacity)) return false;

        $opacity /= 100;

        $w = imagesx( $img );
        $h = imagesy( $img );

        imagealphablending( $img, false );

        $minalpha = 127;
        for($x = 0; $x < $w; $x++):
            for($y = 0; $y < $h; $y++):
              $alpha = ((imagecolorat($img, $x, $y) >> 24) & 0xFF);
              if($alpha < $minalpha) $minalpha = $alpha;
            endfor;
        endfor;

        for( $x = 0; $x < $w; $x++):
        for( $y = 0; $y < $h; $y++):
          $colorxy = imagecolorat($img, $x, $y);
          $alpha = (($colorxy >> 24 ) & 0xFF);

          if( $minalpha !== 127 ):
            $alpha = 127 + 127 * $opacity * ( $alpha - 127 ) / ( 127 - $minalpha );
          else:
            $alpha += 127 * $opacity;
            endif;

          $alphacolorxy = imagecolorallocatealpha($img, ($colorxy >> 16) & 0xFF, ( $colorxy >> 8 ) & 0xFF, $colorxy & 0xFF, $alpha);
          if( ! imagesetpixel($img, $x, $y, $alphacolorxy)) return false;
        endfor;
        endfor;

        return true;
    }

    /**
     * New function
     *
     * Description of new function
     */
    public function swap_equipment($gender = FALSE)
    {
        if ( ! $temp_avatar_data = $this->cache->get('tmp_sn_'.$this->user_key)):
            $temp_avatar_data = $this->default_signup_options;
        endif;

        if ( ! $gender):
            $item_id = $this->input->post('item_id');
            $type = $this->input->post('item_type');

            if(in_array($type, array('other', 'gender'))) show_error('Not allowed to modify');
            if ( ! isset($this->signup_options[$type])) show_error('That type does not exist');
            if ( ! in_array($item_id, $this->signup_options[$type])) show_error('That item is not a starter item!');

            $temp_avatar_data[$type] = $item_id;
        else:
            $temp_avatar_data['gender'] = $this->input->post('value');
        endif;

        $this->cache->delete('tmp_sn_'.$this->user_key);
        $this->cache->save('tmp_sn_'.$this->user_key, $temp_avatar_data, 1800);

        $this->output->set_content_type('application/json')->set_output(json_encode(array('success' => 1), JSON_NUMERIC_CHECK));
    }

    /**
     * New function
     * Description of new function
     */
    public function _get_subimages($equipped_items = array())
    {
        $query = $this->db->select('caip.*, cal.order')
                          ->from('avatar_item_parts caip')
                          ->join('avatar_items cai', 'cai.item_id=caip.item_id')
                          ->join('avatar_layers cal', 'cal.id=caip.layer')
                          ->where_in('caip.item_id', $equipped_items)
                          ->where('caip.gender', $this->user_gender)
                          ->order_by('cal.order', 'ASC')
                          ->get()
                          ->result_array();

        if ( ! empty($query)):
            return $query;
        else:
            return false;
        endif;
    }

    /**
     * New function
     * Description of new function
     */
    public function _get_temp_data()
    {
        // $this->cache->delete('tmp_sn_'.md5($this->input->ip_address()));
        if ( ! $temp_avatar_data = $this->cache->get('tmp_sn_'.md5($this->input->ip_address()))):
            $temp_avatar_data = $this->default_signup_options;
            $this->cache->save('tmp_sn_'.md5($this->input->ip_address()), $temp_avatar_data, 1800);
        endif;

        $equipped_items = array();
        foreach ($temp_avatar_data as $key => $data):
            if ($key == 'gender'):
                $this->user_gender = $data;
            elseif (is_array($data)):
                foreach ($data as $item_id):
                    $equipped_items[] = $item_id;
                endforeach;
            else:
                $equipped_items[] = $data;
            endif;
        endforeach;

        return $equipped_items;
    }

    /**
     * Validate
     * Verify the informed data
     */
    public function validate($method = 'username') {
        switch ($method) {
            case 'email':
                $user_query = $this->db->get_where('users', array('user_email' => $this->input->post('q')));
                if ($user_query->num_rows() > 0) {
                    $user_data = $user_query->row_array();
                    $this->system->parse_json(array('error' => 'The email \''.$this->input->post('q').'\' has been taken.'));
                } else {
                    $this->load->helper('email');
                    if (!valid_email($this->input->post('q'))) {
                        $this->system->parse_json(array('error' => 'That doesn\'t seem like a valid email'));
                    } else {
                        $this->system->parse_json(array('success' => 'Cool email. Thanks!'));
                    }
                }
            break;
            case 'username':
                $user_query = $this->db->get_where('users', array('username' => $this->input->post('q')));
                if ($user_query->num_rows() > 0) {
                    $this->system->parse_json(array('error' => 'The username \''.$this->input->post('q').'\' has been taken.'));
                } else {
                    if (!preg_match("/^([a-z0-9\s])+$/i", $this->input->post('q'))) { 
                        $this->system->parse_json(array('error' => 'The username can only be made of letters, numbers and spaces.'));
                    } else if(strlen($this->input->post('q')) > 20) {
                        $this->system->parse_json(array('error' => 'That username is too long, it can\'t be over 20 characters.'));
                    } else {
                        $this->system->parse_json(array('success' => 'Best username ever! And it\'s avaliable!!'));
                    }
                }
            break;
            case 'referal':
                $user_query = $this->db->get_where('users', array('username' => $this->input->post('q')));
                if ($user_query->num_rows() > 0)
                    return $this->system->parse_json(array('msg' => 'That\'s a valid user!', 'status' => 'success'));

                return $this->system->parse_json(array('msg' => 'Uh ohh... We couldn\'t find that user. Did you type it right?', 'status' => 'error'));
            break;
        }
    }

    /**
     * New function
     * Description of new function
     * @access  public
     * @param   none
     * @return  output
     */
    private function _gd_transparecy($image_obj = array())
    {
        $transcol = imagecolorallocatealpha($image_obj, 255, 0, 255, 127);
        $trans    = imagecolortransparent($image_obj, $transcol);
        imagefill($image_obj, 0, 0, $transcol);
        imagesavealpha($image_obj, true);
        imagealphablending($image_obj, true);

        return $image_obj;
    }

    /**
     * View avatar
     *
     * Allows for viewing the avatar freshly generated or flipped
     */
    function headshot($user_id = NULL, $gen = false)
    {
        $my_id = $this->user_id;
        $initial_path = realpath(BASEPATH.$this->avatar_config['avatar_path']).'/';

        if($gen != false):
            $path1  = $initial_path.(!is_null($user_id) ? $user_id : $my_id).'.png';
            $path2  = $initial_path.(!is_null($user_id) ? $user_id : $my_id).'_'.$this->avatar_config['headshot_ext'].'.png';

            // make the thumb
            $baseimage = imagecreatefrompng($initial_path.'headshot_base.png');
            imagealphablending($baseimage,true);
            imagesavealpha($baseimage, true);

            //add the image on it
            $image  = imagecreatefrompng($path1); // create main graphic
            imagealphablending($image,true);
            imagesavealpha($image, true);

            //save the thumb
            imagecopy($baseimage, $image, 0, 0, 69, 40, 60, 60);
            imagealphablending($baseimage,true);
            imagesavealpha($baseimage, true);
            imagepng($baseimage,$path2);
            imagedestroy($image);  imagedestroy($baseimage);
        else:
            $path1 = $initial_path.( ! is_null($user_id) ? $user_id : $my_id).'_'.$this->avatar_config['headshot_ext'].'.png';

            if( ! $path1):
                $path1  = $initial_path.( ! is_null($user_id) ? $user_id : $my_id).'.png';
                $path2  = $initial_path.( ! is_null($user_id) ? $user_id : $my_id).'_'.$this->avatar_config['headshot_ext'].'.png';
                $baseimage = imagecreatefrompng($initial_path.'headshot_base.png');
                imagealphablending($baseimage,true);
                imagesavealpha($baseimage, true);

                // add the image on it
                $image  = imagecreatefrompng($path1); // create main graphic
                imagealphablending($image,true);
                imagesavealpha($image, true);

                // save the thumb
                imagecopy($baseimage, $image, 0, 0, 69, 40, 60, 60);
                imagealphablending($baseimage,true);
                imagesavealpha($baseimage, true);
                imagepng($baseimage,$path2);
                imagedestroy($image);
                imagedestroy($baseimage);
            else:
                header('content-type: image/png');
                $image = imagecreatefrompng($path1);
                imagealphablending($image,true);
                imagesavealpha($image, true);

                imagepng($image);
                imagedestroy($image);
            endif;
        endif;
    }

    /**
     * Auth Redeem password
     * Function that is sent on the password recovery email link
     */
    function redeem_account($key = '')
    {
//      echo "Key: ".$this->input->get('key');
//      echo "<br/>";
//      echo "User ID: ".$this->input->get('user');

        $key = $this->input->get('key');
        $userid = $this->input->get('user');


        $this->system->view_data['scripts'][] = '/global/js/auth/redeem_password.js';

        if(ctype_alnum($key) && strlen($key) == 32 && is_numeric($userid)): // [SECURITY] Make sure it's a legit key and user id is numerical
            $day = (7*60*60*24);
            $day_ago = date("Y-m-d H:i:s", (time()-$day));
            $user_query = $this->db->get_where('register_keys', array('user_id' => $userid, 'key' => $key, 'created_at >' => $day_ago));

            if($user_query->num_rows() <= 0) show_error('Sorry, this token has already expired, or has already been used to verify your account. If you are still unverified, please use the <strong>Resend Verification</strong> link on your account\'s homepage. If this still doesn\'t work, please submit a Staff Support ticket requesting account verification and reporting that your verification email did not send.');

            $user_data = $user_query->row_array();

                $update_user_data = array(
                    'user_level'        => 'user',
                    'verified_email'   => 1
                );

                $new_user_id = $this->db->where(array('user_id' => $userid))->update('users', $update_user_data);
                $this->db->limit(1)->delete('register_keys', array('user_id' => $userid, 'key' => $key));

                $this->system->quick_parse('auth/redeem_account', array(
                    'page_title' => 'Verify your account',
                    'page_body'  => 'signin reset_password blue_gloss',
                    'form'       => 'completed'
                ));
            endif;
    }
}
