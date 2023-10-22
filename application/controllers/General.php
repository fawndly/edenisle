<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class General extends CI_Controller
{
    // --------------------------------------------------------------------

    /**
     * Home page
     *
     * General main function
     *
     * @access  public
     * @param   none
     * @return  view
     * @route   n/a
     */

    public function index()
    {
        $view_data = array(
            'page_title' => 'General',
            'page_body' => 'general'
        );

        $this->system->quick_parse('general/index', $view_data);
    }

    public function privacy()
    {
        $view_data = array(
            'page_title' => 'Privacy Policy',
            'page_body' => 'privacy'
        );

        $this->system->quick_parse('general/privacy', $view_data);
    }
    public function bbcode()
    {
        $view_data = array(
            'page_title' => "Sapherna's BBCode",
            'page_body' => 'bbcode'
        );

        $this->system->quick_parse('general/bbcode', $view_data);
    }
    public function credits()
    {
        $view_data = array(
            'page_title' => 'Credits',
            'page_body' => 'credits'
        );

        $this->system->quick_parse('general/credits', $view_data);
    }
    public function tos()
    {
        $view_data = array(
            'page_title' => 'Code of Conduct',
            'page_body' => 'tos'
        );

        $this->system->quick_parse('general/tos', $view_data);
    }

}

/* End of file General.php */
/* Location: ./system/application/controllers/General.php */
