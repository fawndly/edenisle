<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Games extends CI_Controller {
    
    var $route_navigation = array(
    'index'      => 'Games'
  );
  
  function __construct(){
        parent::__construct();
  }
    public function index()
    {
        $view_data = array(
            'page_title' => 'Games',
            'page_body' => 'games',
            'routes'     => $this->route_navigation,
            'active_url' => $this->uri->rsegment(2, 0),
        );
            $this->system->quick_parse('games/index', $view_data);
    }

}
?>
