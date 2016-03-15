<?

require_once($_SERVER['DOCUMENT_ROOT'].'/app/vendor/autoload.php');
use Mailgun\Mailgun;
/**
 * Sends emails
 */

function notify_get_rates($reservation) {
    $CI = & get_instance();

    $signin_url = $CI->config->item('signin_url');
    include(APPPATH . '/views/emails/reservation.php');
    $subject = "Inquiry Request from website!";

    $mg = new Mailgun($CI->config->item('mailgun_key'));
    $batchMsg = $mg->BatchMessage($CI->config->item('mailgun_domain'));

    $batchMsg->setFromAddress($CI->config->item('notifications_email'), array("full_name"=>$CI->config->item('site_title')));
    $batchMsg->setSubject($subject);
    $batchMsg->setHtmlBody($msg);
    //$batchMsg->addToRecipient('peter@halfslide.com', array());
    $batchMsg->addToRecipient('steve@echemail.com', array());
    $batchMsg->addToRecipient('shelly@echemail.com', array());
    $response = $batchMsg->finalize();

    return $response;
}

function notify_listing($email_addresses = '', $message = '', $listing = '')
{
    $CI = & get_instance();
    $CI->load->helper('email');

    if($listing) {
        $CI->load->model('Listing');
        $photos = $CI->Listing->get_photos($listing->id);
        $photo = $photos[0];
    }

    $mg = new Mailgun($CI->config->item('mailgun_key'));
    $batchMsg = $mg->BatchMessage($CI->config->item('mailgun_domain'));

    $signin_url = $CI->config->item('signin_url');
    include(APPPATH . '/views/emails/listing.php');
    $batchMsg->setFromAddress($CI->config->item('notifications_email'), array("full_name"=>$CI->config->item('site_title')));
    $batchMsg->setSubject('Recommended Apartment from Express Corporate Housing');
    $batchMsg->setHtmlBody($msg);

    $email_addresses = explode(',', $email_addresses);
    foreach ($email_addresses as $email_address) {
        if (valid_email($email_address)) {
            $batchMsg->addToRecipient($email_address, array());
        }
    }
    $response = $batchMsg->finalize();
    return $email_addresses;
    //echo $CI->email->print_debugger();
}

function setup_notification_email()
{
    $CI = & get_instance();

    $config = array();
    //$config['protocol'] = 'smtp';
    $config['mailtype'] = 'html';
    //$config['smtp_host'] = $CI->config->item('smtp_host');
    //$config['smtp_port'] = $CI->config->item('smtp_port');
    //$config['smtp_user'] = $CI->config->item('notifications_user');
    //$config['smtp_pass'] = $CI->config->item('notifications_password');
    //$config['smtp_timeout'] = 5;
    $config['charset'] = 'iso-8859-1';
    $config['wordwrap'] = TRUE;

    $CI->load->library('email', $config);
    $CI->email->set_newline("\r\n");
}

?>