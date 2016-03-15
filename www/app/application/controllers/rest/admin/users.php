<?
class Users extends REST_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->validate_admin();
        $this->load->helper('json');
    }

    function change_password_email_post() {
        $email = $this->post('email');
        $password = $this->post('password');
        $username = $this->post('username');

        $user = get_user();

        if($username) {
            $this->User->update($user->id, array('username'=>$username));
        }

        if($email) {
            $this->User->update($user->id, array('email'=>$email));
        }

        if($password) {
            $this->User->change_password($user->id, $password);
        }
        json_success("Password updated successfully");
    }
}

?>