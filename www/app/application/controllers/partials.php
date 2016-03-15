<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Partials extends CI_Controller
{

    private $_no_cache = array( );

    private $_views = array(
        'admin/admin',
        'admin/datatable',
        'admin/datatable-listings',
        'admin/listing',
        'admin/city',
        'admin/submarket',
        'cities-popular',
        'combo-list',
        "listing",
        "listings",
        "submarkets",
        "listings-list",
        "listings-map",
        "listing-map-popup",
        "listings-pager",
        "listings-featured",
        "listings-sidebar",
       "sublistings-sidebar",
        "email-listing",
        "reserve-wizard"
    );

    public function _remap($method, $params) {

        if($method=='all') {
            return $this->load_all_script();
        } else {
            $path = $method;
            if($params) {
                $path.="/".implode("/",$params);
            }
            $this->load_view($path);
        }
    }

    public function load_view($path)
    {
        if(!in_array($path, $this->_no_cache)) {
            //$this->output->cache(1440); /* Cache for a day */
        }
        $this->load->view("partials/".$path);
    }

    private function load_all_script() {
        $this->output->set_content_type('application/javascript');
        foreach($this->_views as $view) {
            $this->output->append_output('app.template_cache.set("'.$view.'", "');
            $data = $this->load->view('partials/'.$view, '', true);
            $data = trim(preg_replace('/\s\s+/', ' ', $data));
            $data = addslashes(preg_replace('~>\s*\n\s*<~', '><',$data));
            $this->output->append_output($data.'");');
        }
    }
}

?>