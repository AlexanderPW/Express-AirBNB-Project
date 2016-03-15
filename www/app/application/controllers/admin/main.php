<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Main extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function _remap($method, $params) {
        $this->load_view($method, $params);
    }

    public function load_view() {
        $this->validate_user();
        /* Setup the page */
        $this->data['page_title'] = 'Dashboard';
        $this->carabiner->js('scripts/lib/jquery/jquery.dataTables.js');
        $this->carabiner->js('scripts/lib/bootstrap-datepicker.js');
        $this->carabiner->css('stylesheets/lib/datepicker.css');

        /* Load Backbone App and Controllers */
        $this->backbone_app(array(
             'scripts/app/routers/Admin.js', 

            'scripts/app/models/Listing.js',
            'scripts/app/models/City.js',
            'scripts/app/models/SubListing.js',

            'scripts/app/views/admin/Admin.js',
            'scripts/app/views/admin/Dashboard.js',
            'scripts/app/views/admin/Listing.js',
            'scripts/app/views/admin/City.js',
            'scripts/app/views/admin/Submarket.js',
            'scripts/app/views/admin/DataTable.js',
            'scripts/app/views/admin/ListingDataTable.js'
        ));

        $this->load->view('includes/admin-header', $this->data);
        $this->load->view('includes/admin-footer', $this->data);
    }

}