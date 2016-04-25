<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Login extends User_Controller
{
    public function index() {
        $this->load->library('form_validation');

        $this->data['page_title'] = 'Login';

        $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');

        $url = $this->session->userdata('pre_login_target');
        if (!$url || $url!='login' || $url!='admin') {
            $url = $this->config->item('site_home');
        }

        if ($this->session->userdata('user_id')) {
            redirect($url);
        }

        if ($this->form_validation->run() != FALSE) {
            $user = dologin();

            if ($user && $user->id) {
                if ($user->inactive > 0) {
                    $this->session->set_flashdata('inactive', $user->uuid);
                    redirect($this->config->item('signin_url'));
                }

                $this->User->record_login($user->id);
                $this->session->unset_userdata('pre_login_target');

                if($user->user_type_id == USER_TYPE_ADMIN) {
                    log_message('info', 'Admin Login - User ID: ' . $user->id . ', Username: ' . $user->username . ', Pre Login Target: ' . $url);
                    $this->session->set_userdata('admin_user_id', $user->id);
                    redirect($this->config->item('admin_home'));
                } else {
                    log_message('info', 'Login - User ID: ' . $user->id . ', Username: ' . $user->username . ', Pre Login Target: ' . $url);
                    $this->session->set_userdata('user_id', $user->id);
                    redirect($url);
                }
            } else {
                $this->session->set_flashdata('error', 'The username/password you have entered are invalid.  Please try again.');
                redirect($this->config->item('signin_url'));
            }
        } else {
            $this->load->view('includes/header', $this->data);
            $this->load->view('login', $this->data);
            $this->load->view('includes/footer', $this->data);
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect($this->config->item('signin_url'));
    }

    public function forgot() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('username_email', 'Username/Email', 'trim|required|xss_clean');

        $this->data['page_title'] = 'Forgot Password';

        if ($this->form_validation->run() != FALSE) {
            $email_username = $this->input->post('username_email');

            /** First try to load the account by username **/
            $user = $this->User->load_by_username($email_username);


            if (!$user) {
                $user = $this->User->load_by_email($email_username);
            }

            if (!$user) {

                $this->session->set_flashdata('error', 'There was no account found with that username/email address.  Please try again.');
                redirect(site_url('login/forgot'));

                log_message('info', '[Forgot Password] Invalid Username/Email: ' . $email_username);
                return;
            }

            $new_password = $this->User->reset_password($user->id);

            $message = "You are receiving this email because you recently requested that your " . $this->config->item('site_title') . " username and "
              . " password be emailed to you.  Your account information is listed below.  To login, go to " . $this->config->item('base_url')
              . "\n\nUsername: " . $user->username . "\nPassword: " . $new_password . "\n\nThank you.";


            $this->setup_email(FALSE);

            $this->email->to($user->email);
            $this->email->from($this->config->item('notifications_user'));

            $subject = sprintf('[%s] Account Information', $this->config->item('site_title'));

            $this->email->from($this->config->item('notifications_email'), $this->config->item('site_title'));
            $this->email->subject($subject);
            $this->email->message($message);
            $this->email->send();

            log_message('info', '[Forgot Password] Sending Username/Password to ' . $user->username . ' <' . $user->email . '>');
            $this->session->set_flashdata('success', 'Your password has been reset and emailed to your email address.  You should receive it shortly.');

            redirect($this->config->item('signin_url'));
        } else {
            $this->load->view('includes/header', $this->data);
            $this->load->view('includes/page-title', $this->data);
            $this->load->view('forgot', $this->data);
            $this->load->view('includes/footer', $this->data);
        }
    }


}
?>
