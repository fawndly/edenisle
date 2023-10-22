<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bank extends CI_Controller
{
    var $avatar_config;
    var $avatar_data = NULL;
    var $memcached = FALSE;
    var $cache_durations = array(
        'inventory_cache' => 3600 // 1 hour
    );
    var $item_ids_tried_on = array();
    var $cached_sub_items = array();
    var $cached_sub_item_ids = array();

    function __construct()
    {
        parent::__construct();
        ini_set('memory_limit', '2016M');

        $query = $this->db->get('avatar_config'); // Getting config options...

        foreach($query->result_array() as $row):
            $this->avatar_config[$row['key']] = $row['value'];
        endforeach;

        $this->load->helper('bank'); // Load some helper functions
        $this->load->model('bank_engine'); // Helps make the code more poetic-like

        $this->user_id = $this->system->userdata['user_id'];
        $this->avatar_engine->user_id = $this->user_id;
    }

    // --------------------------------------------------------------------

    /**
     * Avatar index
     *
     * Displays the main inventory with all the users items
     *
     * @access  public
     * @param   none
     * @return  view
     * @route   n/a
     */

    function index()
    {
        if( ! $this->user_id) redirect('/auth/signin');
        $this->system->view_data['scripts'][] = '/global/js/bank/index.js';

        $this->benchmark->mark('inventory_query_start');
        $query = $this->avatar_engine->get_user_inventory($this->user_id);
        $this->benchmark->mark('inventory_query_end');

        $this->benchmark->mark('create_inventory_start');
        $items = $this->_create_inventory_array($query);
        $this->benchmark->mark('create_inventory_end');

        ksort($items);

        $view_data = array(
            'page_title'         => 'My Storage',
            'page_body'          => 'avatar sub-storage',
            'items'              => $items      
            );

        $this->system->quick_parse('bank/index', $view_data);
    }



    // --------------------------------------------------------------------

    /**
     * Generate item thumbnail
     *
     * Allows generating
     *
     * @access  public
     * @param   (int) | (bool) | (bool)
     * @return  img
     * @route   n/a
     */

    function thumbnail($item_id = NULL, $num = 1)
    {
        if( ! is_null($item_id)):
            $image      = imagecreatetruecolor($this->avatar_config['thumbnail_width'], $this->avatar_config['thumbnail_height']);
            $transcol   = imagecolorallocatealpha($image, 255, 0, 255, 127);
            $trans      = imagecolortransparent($image,$transcol);

            imagefill($image, 0, 0, $transcol);
            imagesavealpha($image, true);
            imagealphablending($image, true);

            $query = $this->avatar_engine->get_item_thumbnail($item_id);

            if($query->num_rows() > 0):
                $row    = $query->row_array();
                $path   = realpath(BASEPATH.$this->avatar_config['items_path'].$row['thumb']);

                if($path):
                    $image = merge_layers($image, $path, $this->avatar_config);
                endif;
            endif;

            $font_path = APPPATH.'resources/arial.ttf';

            if( (int) $num != 1):
                $white = imagecolorallocate($image, 255, 255, 255);
                $black  = imagecolorallocate($image, 30, 30, 30);
                $strlen     = (strlen($num) > 1 ? floor(strlen($num))*10 : 10)+30;
                imagettftext($image, 9, 10, 20, 42, $black, $font_path, $num.'x');
                imagettftext($image, 9, 10, 20, 38, $black, $font_path, $num.'x');
                imagettftext($image, 9, 10, 18, 40, $black, $font_path, $num.'x');
                imagettftext($image, 9, 10, 22, 40, $black, $font_path, $num.'x');
                imagettftext($image, 9, 10, 20, 40, $white, $font_path, $num.'x');
            endif;

            header('content-type: image/png'); // PNG FILE
            $transcol   = imagecolorallocatealpha($image, 255, 0, 255, 127);
            $trans      = imagecolortransparent($image,$transcol);

            imagepng($image);
            imagedestroy($image);
        else:
            echo "Invalid Handler";
        endif;
    }

    // --------------------------------------------------------------------

    /**
     * Item Composite check
     *
     * Check if item is composite
     *
     * @access  private
     * @param   n/a
     * @return  array
     * @route   n/a
     */

    function _isComposite($item_id = 0)
    {
        $data  = array('composite' => false, 'layername' => 'Unknown', 'order' => 999999999, 'layerid' => 0);
        $query = $this->avatar_engine->get_composite_item_data($item_id);

        foreach($query->result_array() as $row):
            if($row['layercomposite'] == 1):
                return array(
                        'composite' => true,
                        'layername' => $row['layername'],
                        'order'     => $row['layerorder'],
                        'layerid'   => $row['layerid']
                    );
            else:
                $data = array(
                    'composite' => false,
                    'layername' => $row['layername'],
                    'order'     => $row['layerorder'],
                    'layerid'   => $row['layerid']
                );
            endif;
        endforeach;

        return $data;
    }
}

/* End of file avatar.php */
/* Location: ./system/application/controllers/avatar.php */
