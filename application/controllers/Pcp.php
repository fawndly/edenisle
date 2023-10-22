<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pcp extends CI_Controller
{
    var $items_path = (BASEPATH.'../images/items/');
    var $route_navigation = [
        'index'      => 'List Items',
        'createitem' => 'Install Item',
        'index'      => 'List Items',
    ];

    function __construct() {
        parent::__construct();
        if (!$this->system->is_staff())
            show_error('You are not allowed in here');
    }
    

    function index() {
        if (!$total_rows = $this->cache->get('total_avatar_items')) {
            $total_rows = $this->db->count_all('avatar_items');
            $this->cache->save('total_avatar_items', $total_rows, 200);
        }

        $this->load->library('pagination');
        $config['base_url'] = '/pcp/index/';
        $config['total_rows'] = $total_rows;
        $config['per_page'] = 12;
        $config['uri_segment'] = 3;
        $this->pagination->initialize($config);

        $items = $this->db->select('avatar_items.*, avatar_layers.name as layer_name')
                          ->order_by('item_id', 'DESC')
                          ->limit($config['per_page'], $this->uri->segment(3, 0))
                          ->join('avatar_layers', 'avatar_items.layer = avatar_layers.id')
                          ->get('avatar_items')
                          ->result_array();

        $view_data = [
            'page_title' => 'Modify items',
            'page_body'  => 'pcp',
            'items'      => $items,
            'routes'     => $this->route_navigation,
            'active_url' => $this->uri->rsegment(2, 0),
        ];

        $this->system->quick_parse('pcp/index', $view_data);
    }

    /**
     * New function
     * Description of new function
     * @access  public
     * @param   none
     * @return  output
     */
    public function edit_item($item_id = 0) {
        $item_data = $this->db->select('avatar_items.*, avatar_layers.name as layer_name')
                              ->join('avatar_layers', 'avatar_items.layer = avatar_layers.id')
                              ->get_where('avatar_items', ['item_id' => $item_id])
                              ->row_array();

        $layers = [];

        foreach($this->db->get('avatar_layers')->result_array() as $row) {
            $layers[$row['order']] = ["name" => $row['name'], "id" => $row['id']];
        }

        ksort($layers);

        $item_children = [];
        $item_children = $this->db->select('avatar_item_parts.*, avatar_layers.name as layer_name')
                              ->join('avatar_layers', 'avatar_item_parts.layer = avatar_layers.id')
                              ->get_where('avatar_item_parts', ['item_id' => $item_id])
                              ->result_array();

        $view_data = [
            'page_title' => 'Modify item',
            'page_body'  => 'pcp',
            'item'       => $item_data,
            'routes'     => $this->route_navigation,
            'active_url' => $this->uri->rsegment(2, 0),
            'layers'     => $layers,
            'children'   => $item_children
        ];

        $this->system->quick_parse('pcp/edit_item', $view_data);
    }

    /**
     * New page
     * New page description
     * @access  public
     * @param   none
     * @return  redirect
     * @route   n/a
     */
    function item_info($id) {
        $item = $this->db->select('avatar_items.name, avatar_items.thumb, avatar_layers.name as l_name')
                 ->from('avatar_items')
                 ->join('avatar_layers', 'avatar_items.layer = avatar_layers.id')
                 ->where('item_id', $id)
                 ->get()
                 ->row_array();

        echo json_encode($item);
    }

    function multi_pose() {
         $data = [
           'page_title' => 'multi pose',
           'page_body'  => 'pcp'
        ];

        $this->system->quick_parse('pcp/multi_pose', $data);
    }

    function shops() {
         $data = [
           'page_title' => 'multi pose',
           'page_body'  => 'pcp'
        ];

        $this->system->quick_parse('pcp/shops', $data);
    }

    function uploaditems() {
        //Upload regular item images
        $this->system->quick_parse('pcp/uploadfull', []);
    }

    function uploadthumbs() {
        //Upload thunb item images
    }

    /**
     * New page
     * New page description
     * @access  public
     * @param   none
     * @return  redirect
     * @route   n/a
     */
    function user_info($name = 0) {
        $name = str_replace('_', ' ', $name);
        $item = $this->db->select('username, user_id, reffered')
                         ->from('users')
                         ->where('username', $name)
                         ->get()
                         ->row_array();

        echo json_encode($item);
    }

    function grant_prizes() {
        $user = $this->db->select('username, user_ores, user_id, reffered')
                 ->from('users')
                 ->where('username', $this->input->post('username'))
                 ->get()
                 ->row_array();

        $this->load->model('user_engine');
        $this->user_engine->add_ores($this->input->post('pallaas'), $user['user_id']);

        foreach($this->input->post('prizes') as $prize) {
            switch($prize):
                case 'p_1':
                    $this->user_engine->add_ores('150', $user['user_id']);
                    $response[] = "Palla granted!";
                break;
                case 'p_2':
                    $this->user_engine->add_item(5393, 1, $user['user_id']);
                    $response[] = "Belt granted!";
                break;
                case 'p_3':
                    $this->user_engine->add_items([3323, 3324, 3324], $user['user_id']);
                    $response[] = "Nets granted!";
                break;
                case 'p_4':
                    $this->user_engine->add_item(5395, 1, $user['user_id']);
                    $response[] = "Shirt granted!";
                break;
            endswitch;
        }
        echo json_encode($response);
    }

    /* ITEM CREATION */
    function createitem() {
        $this->system->view_data['scripts'][] = '/global/js/pcp/index.js';
        $query = $this->db->get('avatar_layers');
        $layers = [];
        foreach($query->result_array() as $row) {
            $layers[$row['order']] = ["name"=>$row['name'], "id"=>$row['id']];
        }
        ksort($layers);
        $query1 = $this->db->query("SELECT name, item_id FROM avatar_items ORDER BY item_id DESC LIMIT 15");

        $latest = $query1->result_array();

        $colors_query = $this->db->query("SELECT id, name FROM avatar_items_recolor_table")->result_array();

        $data = [
            'layers'    => $layers,
            'colors'    => $colors_query,
            'latest'    => $latest,
            'page_body' => 'pcp'
        ];

        $this->system->quick_parse('pcp/additem', $data);
    }

    function docreate() 
    {
        $thumbnail_file_name = $this->_getUniqueCode().".png";
        $item_post_data = $_POST['item'];
        $item_file_data = $_FILES['item'];

        if($this->_isValidImageUpload($item_file_data, 'thumbnail')) {
            $move_uploaded_file_res = move_uploaded_file(
                $item_file_data['tmp_name']['thumbnail'], 
                realpath($this->items_path) . "/" . $thumbnail_file_name
            );

            # now for each recolor
            if(!$move_uploaded_file_res) show_error("An error happened while uploading the thumbnail. Reach out to a dev or admin.");
        } else {
            show_error("An error happened while uploading the thumbnail. Reach out to a dev or admin.");
        }

        $this->db->insert('avatar_items', [
            'name' => $item_post_data['name'],
            'gender' => $item_post_data['gender'],
            'order' => 0,
            'layer' => $item_post_data['layer'],
            'composite' => (isset($item_post_data['composite']) ? $item_post_data['composite'] : 0),
            'default' => (isset($item_post_data['default']) ? $item_post_data['default'] : 0),
            'thumb' => $thumbnail_file_name,
        ]);

        $item_id = $this->db->insert_id();

        $parts_data = $_FILES['part'];
        foreach($parts_data['name']['path'] as $k => $v) {
            $item_part_data = [
                'name' => $_POST['item']['name'],
                'image_path' => $this->_getUniqueCode().".png",
                'item_id' => $item_id,
                'gender' => $_POST['part']['gender'][$k],
                'layer' => $_POST['part']['layer'][$k],
            ];

            $item_image_part_data = [
                'name' => $v,
                'temp' => $parts_data['tmp_name']['path'][$k],
                'error' => $parts_data['error']['path'][$k],
            ];

            if($item_image_part_data['error'] == 0 && substr($item_image_part_data['name'],-3) == 'png') {
                $move_uploaded_file_res = move_uploaded_file(
                    $item_image_part_data['temp'], 
                    realpath(BASEPATH.'../images/items/').'/'.$item_part_data['image_path']
                );

                if($move_uploaded_file_res) {
                    if($item_part_data['gender'] == 'Male' || $item_part_data['gender'] == 'Female') {
                        $this->db->insert('avatar_item_parts', $item_part_data);
                    } else {
                        $item_part_data['gender'] = 'Male';
                        $this->db->insert('avatar_item_parts',$item_part_data);
                        $item_part_data['gender'] = 'Female';
                        $this->db->insert('avatar_item_parts',$item_part_data);
                    }
                }
            }
        }

        if(isset($_POST['item']['colors'])) {
            $enabled_colors = $this->db->where_in('id', array_keys($_POST['item']['colors']))->get('avatar_items_recolor_table')->result_array();

            foreach ($enabled_colors as $colors) {
                $this->generate_color_variation($item_id, $colors['id'], TRUE);
            }
        }

        redirect('pcp/createitem');
    }

    function generate_color_variation($item_id = 0, $color_id = 0, $direct = FALSE) {
        $color_data = $this->db->get_where('avatar_items_recolor_table', [ 'id' => $color_id ])->row_array();
        $avatar_item = $this->db->get_where('avatar_items', [ 'item_id' => $item_id ])->row_array();
        $avatar_item_parts = $this->db->get_where('avatar_item_parts', [ 'item_id' => $item_id ])->result_array();
        
        $avatar_image_directory_path = realpath($this->items_path) . "/";
        $gradient_image_directory_path = realpath(BASEPATH . '../images/gradient_maps/') . "/";

        $thumb_img_path = $avatar_image_directory_path . $avatar_item['thumb'];
        $gradient_map_img_path = $gradient_image_directory_path . $color_data['gradient_map_image'];
        $new_thumb_img = $this->_getUniqueCode().".png";

        # Image magick stuff
        system("/usr/bin/convert $thumb_img_path +contrast $gradient_map_img_path -clut ".$avatar_image_directory_path . $new_thumb_img);

        unset($avatar_item['item_id']);
        $avatar_item['name'] = preg_replace('/\((.*?)\)/U' , "(".$color_data['name'].")", $avatar_item['name']);
        $avatar_item['thumb'] = $new_thumb_img;

        $this->db->insert('avatar_items', $avatar_item);

        $new_item_id = $this->db->insert_id();

        foreach ($avatar_item_parts as $item_part) {
            $item_part_img_path = $avatar_image_directory_path . $item_part['image_path'];
            $new_part_img = $this->_getUniqueCode().".png";

            # more image magick stuff
            system("/usr/bin/convert $item_part_img_path +contrast $gradient_map_img_path -clut ".$avatar_image_directory_path . $new_part_img);

            unset($item_part['id']);
            $item_part['image_path'] = $new_part_img;
            $item_part['name'] = $avatar_item['name'];
            $item_part['item_id'] = $new_item_id;

            $this->db->insert('avatar_item_parts', $item_part);
        }

       if(!$direct) redirect('pcp');
    }

    function _isValidImageUpload($file_obj, $key)
    {
        return $file_obj['error'][$key] == 0 && substr($file_obj['name'][$key],-3) == 'png';
    }

    /* LAYERING GUIDE */
    function layers() {
        $this->load->helper('form');
        $layers = $this->db->query(
            "SELECT * FROM avatar_layers
             ORDER BY `order` asc"
        )->result_array();

        $data = [
            'main_layers' => $layers,
            'page_body'   => 'pcp',
            'page_title'  => 'View layering'
        ];

        $this->system->quick_parse('pcp/layering_info', $data);
    }

    function get_items_on_layer($layer_id) {
        $items = $this->db->distinct()
                          ->select('ai.*')
                          ->join('avatar_items ai', 'ai.item_id = aip.item_id')
                          ->get_where('avatar_item_parts aip', ['aip.layer' => $layer_id])
                          //->oder_by('ai.item_name')
                          //->group_by("replace(name, substr(name, locate('(', name) ), '')")
                          ->result_array();
        
        // $items = $this->db->query("SELECT * FROM `avatar_items` where name!='' and layer=".mysql_real_escape_string($layer_id)." group by replace(name, substr(name, locate('(', name) ), '') order by name")
                                    // ->result_array();
        
        if ($items) {
            foreach($items as $item)
                echo "<img id=\"i".$item['item_id']."\" src=\"".site_url('images/items/'.$item['thumb'])."\" onclick=\"get_subs(".$item['item_id'].")\" height=\"42\", width=\"42\" alt=\"".$item['name']."\" title=\"".$item['name']."\"/>";
        } else {
            echo 'no items found for this layer';
        }
    }

    function get_sub_layers($item_id) {
        $items = $this->db->query("SELECT a.*, l.name as layername, l.order FROM `avatar_item_parts` a, avatar_layers l where a.layer=l.id and a.name!='' and a.item_id=".mysql_real_escape_string($item_id)." group by a.item_id, a.layer")
                          ->result_array();

        if ($items) {
            echo '<a href="'.site_url('item_manager/item_id/'.$items[0]['item_id']).'" target="_blank">';
            echo $items[0]['name']."</a><br/>";
            foreach($items as $item)
                echo "<div><span>{$item['order']} - {$item['layername']}</span>".'<img src="'.site_url("images/items/{$item['image_path']}").'" alt="'.$item['name'].'" title="'.$item['name'].'"/></div>';
        } else {
            echo 'no sub-layers found';
        }
    }

    function get_other_items($item_id) {
        $item = $this->db->query("SELECT replace(name, substr(name, locate('(', name) ), '') as name, layer FROM `avatar_items` where item_id=".mysql_real_escape_string($item_id))
                                ->result_array();
        $items = $this->db->query("SELECT * FROM `avatar_items` where item_id!=".mysql_real_escape_string($item_id)." and layer=".mysql_real_escape_string($item[0]['layer'])." and replace(name, substr(name, locate('(', name) ), '')='".mysql_real_escape_string($item[0]['name'])."' ")
                                ->result_array();
        if (!empty($items)):
            echo "<h4>other colors:</h4>";
            foreach($items as $i):
                echo "<a href=\"".site_url('item_manager/item_id/'.$i['item_id'])."\" target=\"_blank\"><img src=\"".site_url('images/items/'.$i['thumb'])."\" alt=\"".$i['name']."\" title=\"".$i['name']." - manage\"/></a> ";
            endforeach;
        endif;
        $poses = $this->db->query("SELECT * FROM `avatar_items` where item_id!=".mysql_real_escape_string($item_id)." and layer!=".mysql_real_escape_string($item[0]['layer'])." and replace(name, substr(name, locate('(', name) ), '')='".mysql_real_escape_string($item[0]['name'])."' ")
                                ->result_array();
        if (!empty($poses)):
            echo "<h4>other poses:</h4>";
            foreach($poses as $item):
                echo "<a href=\"".site_url('item_manager/item_id/'.$item['item_id'])."\" target=\"_blank\">";
                echo "<img src=\"".site_url('images/items/'.$item['thumb'])."\" alt=\"".$item['name']."\" title=\"".$item['name']." - manage\"/></a>";
                echo "<span class=\"pointer spanlink\" onclick=\"get_subs(".$item['item_id'].")\">view</span><br/>";
            endforeach;
        endif;

    }

    function add_bug_item() {
        //$data['insets'] = $this->forest_engine->get_bugs();
        if ($_POST) {
            $inset_array = [
                'item_id'       => $this->input->post('item_id'),
                'shop_id'       => 6,
                'item_parent'   => 0,
                'price'         => $this->input->post('item_price'),
                'item_currency' => 'Bugs',
                'insect_id'     => $this->input->post('insect_id'),
                'second_price'  => $this->input->post('second_price')
            ];

            $this->db->insert('shop_items', $inset_array);
        }

        $data = ['page_body' => 'pcp'];
        $this->system->quick_parse('pcp/add_bug_item', $data);
    }

    function navigation() {
        $nav = $this->db->order_by('group asc, order asc')->get('site_navigation')->result_array();
        $data = [
            'page_title' => 'navigation',
            'page_body'  => 'pcp',
            'navigation' => $nav
        ];

        $this->system->quick_parse('pcp/navigation', $data);
    }

    function update_navigation() {
        $array  = $_POST['listids'];

        if ($_POST['update'] == "update") {
            $count = 1;
            foreach ($array as $idval) {
                $update_array = ['order' => $count];
                $this->db->where('nav_id', $idval)->update('site_navigation', $update_array);
                $count ++;
            }

            echo 'All saved! refresh the page to see the changes';
        }
    }

    function _getUniqueCode($length = "") {
        $code = md5(uniqid(rand(), true));
        if ($length != "") return substr($code, 0, $length);
        else return $code;
    }

    function _fileUploadErrorMessage($error_code) {
        switch ($error_code) {
            case UPLOAD_ERR_INI_SIZE:
                return 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
            case UPLOAD_ERR_FORM_SIZE:
                return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
            case UPLOAD_ERR_PARTIAL:
                return 'The uploaded file was only partially uploaded';
            case UPLOAD_ERR_NO_FILE:
                return 'No file was uploaded';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Missing a temporary folder';
            case UPLOAD_ERR_CANT_WRITE:
                return 'Failed to write file to disk';
            case UPLOAD_ERR_EXTENSION:
                return 'File upload stopped by extension';
            default:
                return 'Unknown upload error';
        }
    }
}
