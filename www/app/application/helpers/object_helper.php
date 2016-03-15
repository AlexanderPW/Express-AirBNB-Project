<?

function dologin($username='', $password='') {
    $CI =& get_instance();

    /** Do Login **/
    if (!$username) {
        $username = $CI->input->post('username', TRUE);
    }

    if (!$password) {
        $password = $CI->input->post('password', TRUE);
    }

    $user = $CI->User->login($username, $password);

    return $user;
}

function validate_permission($permission_name)
{
    $permissions = array(
        'USER_LOGIN_AS' => 2,
        'USER_SET_COMPANY' => 2,
        'COMPANY_SWITCH' => 2
    );

    $permission_type_id = $permissions[$permission_name];
    return get_user_type_id() >= $permission_type_id;
    return TRUE;
}

function get_user_type_id()
{
    $user_type_id = 0;

    $CI =& get_instance();
    $CI->load->database();
    if ($CI->session->userdata('user_type_id')) {
        return $CI->session->userdata('user_type_id');
    }

    if ($CI->session->userdata('user_id')) {
        $CI->load->model('User');
        $user_id = $CI->session->userdata('user_id');
        $user = $CI->User->load($user_id);
        if ($user) {
            $user_type_id = $user->user_type_id;
            $CI->session->set_userdata('user_type_id', $user_type_id);
        }
    }

    return $user_type_id;
}

function get_user($user_id = 0)
{
    $CI =& get_instance();
    $CI->load->database();

    if ($user_id === 0) {
        $user_id = $CI->session->userdata('user_id');
        if (!$user_id) {
            $user_id = $CI->session->userdata('admin_user_id');
        }
    }

    if ($user_id) {
        $CI->load->model('User');
        $user = $CI->User->load($user_id);
        return $user;
    }
}

function get_stripe_customer($user_id = 0)
{
    $CI =& get_instance();

    include_once(APPPATH . 'libraries/stripe-php-1.8.0/lib/Stripe.php');
    Stripe::setApiKey($CI->config->item('stripe_private_key'));

    $user = get_user($user_id);
    if ($user && $user->stripe_customer_id) {
        $customer = unserialize($CI->session->userdata('stripe_customer'));
        if (!$customer) {
            try {
                $customer = Stripe_Customer::retrieve($user->stripe_customer_id);
                $CI->session->set_userdata('stripe_customer', serialize($customer));
            } catch (Exception $e) {
                log_message('info', '[get_stripe_customer] Stripe_Customer::retrieve Exception: ' . $e->getMessage());
            }
        }
        return $customer;
    }
}

function get_stripe_recipient($user_id = 0)
{
    $CI =& get_instance();

    include_once(APPPATH . 'libraries/stripe-php-1.8.0/lib/Stripe.php');
    Stripe::setApiKey($CI->config->item('stripe_private_key'));

    $user = get_user($user_id);
    if ($user && $user->stripe_customer_id) {
        $recipient = unserialize($CI->session->userdata('stripe_recipient'));
        if (!$recipient) {
            try {
                $recipient = Stripe_Recipient::retrieve($user->stripe_customer_id);
                $CI->session->set_userdata('stripe_recipient', serialize($recipient));
            } catch (Exception $e) {
                log_message('info', '[get_stripe_recipient] Stripe_Customer::retrieve Exception: ' . $e->getMessage());
            }
        }
        return $recipient;
    }
}

function geocode_listing($listing_id=0) {
    $CI =& get_instance();
    $CI->load->model('Listing');

    $listing = $CI->Listing->load($listing_id);
    $address = urlencode($listing->address." ".$listing->city.",".$listing->state.",".$listing->zipcode);
    $url = "https://maps.googleapis.com/maps/api/geocode/json?address=".$address."&sensor=false";
    $json = file_get_contents($url);
    $result = json_decode($json)->results[0];

    $listing->latitude = $result->geometry->location->lat;
    $listing->longitude = $result->geometry->location->lng;

    $CI->Listing->update($listing->id, array('latitude'=>$listing->latitude, 'longitude'=>$listing->longitude));
}

function get_listing_url($listing='') {
    if($listing) {
        return $listing->country."/".$listing->state."/".$listing->city."/".$listing->url;
    }
}

function geocode_city($city_id=0) {
    $CI =& get_instance();
    $CI->load->model('City');

    $city = $CI->City->load($city_id);
    $address = urlencode($city->city.",".$city->state);
    $url = "https://maps.googleapis.com/maps/api/geocode/json?address=".$address."&sensor=false";
    $json = file_get_contents($url);
    $result = json_decode($json)->results[0];

    $city->latitude = $result->geometry->location->lat;
    $city->longitude = $result->geometry->location->lng;

    $CI->City->update($city->id, array('latitude'=>$city->latitude, 'longitude'=>$city->longitude));
}

function geocode_subcity($city_id=0) {
    $CI =& get_instance();
    $CI->load->model('Submarkets');

    $city = $CI->Submarkets->load($city_id);
    $address = urlencode($city->city.",".$city->state);
    $url = "https://maps.googleapis.com/maps/api/geocode/json?address=".$address."&sensor=false";
    $json = file_get_contents($url);
    $result = json_decode($json)->results[0];

    $city->latitude = $result->geometry->location->lat;
    $city->longitude = $result->geometry->location->lng;

    $CI->Submarkets->update($city->id, array('latitude'=>$city->latitude, 'longitude'=>$city->longitude));
}

function get_user_json($user_id = 0)
{
    $user = get_user($user_id);
    if (!$user) {
        return '{}';
    } else {
        unset($user->id);
        unset($user->password);
        unset($user->salt);
        unset($user->user_type_id);
        unset($user->created);
        unset($user->deleted);
        return json_encode($user);
    }
}

function get_user_name($user_id = 0)
{

    $user = get_user($user_id);
    if ($user) {
        return $user->firstname . " " . $user->lastname;
    }
}

function get_user_id()
{
    $CI =& get_instance();
    $CI->load->database();

    return $CI->session->userdata('user_id');
}

function get_user_email()
{
    $CI =& get_instance();
    $CI->load->database();
    if ($CI->session->userdata('user_id')) {
        $CI->load->model('User');
        $user_id = $CI->session->userdata('user_id');
        $user = $CI->User->load($user_id);
        return $user->email;
    }
}

function city_list($sort_col = 'city', $sort_order = 'ASC')
{
    $CI =& get_instance();
    $CI->load->database();

    $options = array();
    $CI->db->order_by($sort_col, $sort_order);
    $CI->db->where('deleted', 0);
    $query = $CI->db->get('city');

    $results = array();
    foreach ($query->result() as $result) {
        if (isset($result->enabled) && !$result->enabled)
            continue;

        $results[] = $result;
    }

    return $results;
}

function city_url($city) {
    return strtolower($city->city."corporatehousing");
}

function object_list($table_name, $sort_col = 'id', $sort_order = 'ASC')
{
    $CI =& get_instance();
    $CI->load->database();

    $options = array();
    $CI->db->order_by($sort_col, $sort_order);
    $query = $CI->db->get($table_name);

    $results = array();
    foreach ($query->result() as $result) {
        if (isset($result->enabled) && !$result->enabled)
            continue;

        $results[] = $result;
    }

    return $results;
}

function table_lookup($table_name, $id)
{
    if (intval($id)) {
        $CI =& get_instance();
        $CI->load->database();

        $CI->db->where('id', $id);
        $query = $CI->db->get($table_name);
        $result = $query->row();
        if ($result) {

            if (isset($result->enabled) && !$result->enabled)
                return NULL;

            return $result->name;
        }
    }
}

function table_lookup_reverse($table_name, $name)
{
    if ($name) {
        $CI =& get_instance();
        $CI->load->database();

        $CI->db->where('name', $name);
        $query = $CI->db->get($table_name);
        $result = $query->row();
        if ($result) {

            if (isset($result->enabled) && !$result->enabled)
                return NULL;

            return $result->id;
        }
    }
}

function convert_field($value, $datatype = '')
{
    $value = trim($value);
    if ($datatype == 'phone') {
        $value = phone_format($value);
    } else if ($datatype == 'date') {
        $value = mysql_date($value);
    } else if ($datatype == 'time') {
        $value = mysql_time($value);
    } else if ($datatype == 'int') {
        if($value || $value===0) {
            $value = intval($value);
        } else {
            $value = NULL;
        }
    } else if ($datatype == 'url') {
        $value = prep_url($value);
    }

    if(!$value) {
        $value = NULL;
    }

    return $value;
}

function stateNameToAbbr($name)
{
    $abbr = '';
    $stateArray = array('AL' => 'Alabama', 'AK' => 'Alaska', 'AR' => 'Arkansas',
        'AZ' => 'Arizona', 'CA' => 'California', 'CO' => 'Colorado', 'CT' => 'Connecticut', 'DC' =>
        'District of Columbia', 'DE' => 'Delaware', 'FL' => 'Florida', 'GA' => 'Georgia', 'HI' => 'Hawaii',
        'ID' => 'Idaho', 'IL' => 'Illinois', 'IN' => 'Indiana', 'IA' => 'Iowa', 'KS' => 'Kansas', 'KY' =>
        'Kentucky', 'LA' => 'Louisiana', 'MA' => 'Massachusetts', 'MD' => 'Maryland', 'ME' => 'Maine', 'MI' =>
        'Michigan', 'MN' => 'Minnesota', 'MO' => 'Missouri', 'MS' => 'Mississippi', 'MT' => 'Montana', 'NC' =>
        'North Carolina', 'ND' => 'North Dakota', 'NE' => 'Nebraska', 'NH' => 'New Hampshire', 'NJ' =>
        'New Jersey', 'NM' => 'New Mexico', 'NV' => 'Nevada', 'NY' => 'New York', 'OH' => 'Ohio', 'OK' =>
        'Oklahoma', 'OR' => 'Oregon', 'PA' => 'Pennsylvania', 'RI' => 'Rhode Island', 'SC' =>
        'South Carolina', 'SD' => 'South Dakota', 'TN' => 'Tennessee', 'TX' => 'Texas', 'UT' => 'Utah', 'VA' =>
        'Virginia', 'VT' => 'Vermont', 'WA' => 'Washington', 'WI' => 'Wisconsin', 'WV' => 'West Virginia',
        'WY' => 'Wyoming');

    foreach($stateArray as $key => $value) {
        if($name==$value) {
            $abbr = $key;
            break;
        }
    }
    return $abbr;
}

?>