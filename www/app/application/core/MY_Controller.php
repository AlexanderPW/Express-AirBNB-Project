<?php

class MY_Controller extends CI_Controller
{

    var $data;
    var $user_id;
    var $user;

    public function __construct()
    {
        parent::__construct();

        $this->load->library('carabiner');

        /* On production, we use the minified, concated files produced by the packager ruby script.  If you
        * add anything here, make sure you add it to /assets/scripts/static-footer.js */
        if (false && !IS_TEST) {

        } else {

            $this->carabiner->css('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css');
            $this->carabiner->css('https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css');
            $this->carabiner->css('stylesheets/lib/datepicker.css');
            $this->carabiner->css('stylesheets/lib/fullcalendar.css');
            $this->carabiner->css('stylesheets/lib/lightbox.css');
            $this->carabiner->css('stylesheets/screen.css');

            $this->carabiner->js('scripts/lib/jquery/jquery.dateFormat-1.0.js');
            $this->carabiner->js('https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.14.0/jquery.validate.js');
            $this->carabiner->js('https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.14.0/additional-methods.js');
            $this->carabiner->js('scripts/lib/accounting.js');
            $this->carabiner->js('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.js');
            $this->carabiner->js('https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.js');
            $this->carabiner->js('https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.6.0/underscore.js');
            $this->carabiner->js('https://cdnjs.cloudflare.com/ajax/libs/backbone.js/1.2.3/backbone.js');
            $this->carabiner->js('scripts/lib/jquery/jquery.dataTables.js');
            $this->carabiner->js('scripts/lib/jquery/fullcalendar.js');
            $this->carabiner->js('scripts/lib/jquery/jquery.dateFormat-1.0.js');
            $this->carabiner->js('scripts/lib/jquery/jquery.iframe-transport.js');
            $this->carabiner->js('scripts/lib/jquery/lightbox-2.6.min.js');
            $this->carabiner->js('scripts/lib/bootstrap-datepicker.js');
            $this->carabiner->js('scripts/app/util/template.cache.js');
            $this->carabiner->js('scripts/app/util/serialize.object.js');
            $this->carabiner->js('scripts/app/routers/Base.js');
            $this->carabiner->js('scripts/app/collections/Base.js');
            $this->carabiner->js('scripts/app/models/Base.js');
            $this->carabiner->js('scripts/app/views/Base.js');
            $this->carabiner->js('scripts/app/views/Modal.js');
            $this->carabiner->js('scripts/app/views/BaseForm.js');
            $this->carabiner->js('scripts/app/views/BaseList.js');

            $header_js = array(
                array('scripts/lib/jquery/jquery-1.10.2.min.js'),
                array('scripts/app.js')
            );
        }
        $this->carabiner->js(site_detect_url('app/partials/all'));
        $this->carabiner->group('header_js', array('js'=>$header_js));

        $this->data['flash_success'] = $this->session->flashdata('success');
        $this->data['flash_info'] = $this->session->flashdata('info');
        $this->data['flash_error'] = $this->session->flashdata('error');
    }

    /** OVERRIDE THESE **/
    protected function decorate_objects($objects)
    {
        return $objects;
    }

    protected function backbone_app($scripts, $minified_script = '') {

        /* On production, we use the minified, concated files produced by the packager ruby script.  If you
        * add anything here, make sure you add it to $minified_script in /assets/scripts/ */
        if(!IS_TEST && $minified_script) {
            $this->carabiner->js($minified_script);
        } else {
            /* Add all of the views */
            foreach ($scripts as $script) {
                $this->carabiner->js($script);
            }
        }
    }

    protected function validate()
    {
        $this->load->library('form_validation');
    }

    function setup_email($is_html = TRUE)
    {
        $config = array();
        if ($is_html)
            $config['mailtype'] = 'html';

        $config['protocol'] = 'smtp';
        $config['smtp_host'] = $this->config->item('smtp_host');
        $config['smtp_port'] = $this->config->item('smtp_port');
        $config['smtp_user'] = $this->config->item('notifications_user');
        $config['smtp_pass'] = $this->config->item('notifications_password');
        $config['smtp_timeout'] = 5;
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;

        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");
    }

}

?>