<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Combiner extends CI_Controller
{

    public function index()
    {   
    $view_data = array(
            'page_title'  => 'Combiner',
            'page_body'   => 'combiner'
    );
  $this->system->quick_parse('combiner/index', $view_data);
    }

}

/* End of file combiner.php */
/* Location: ./system/application/controllers/combiner.php */
