<?
class Reservations extends REST_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper('json');
        $this->load->model(array('Listing','Reservation'));
    }

    function index_post()
    {
        $location_uuid = $this->post('location_uuid');
        $listing = $this->Listing->load_by_uuid($location_uuid);

        $reservation = array(
            'name' => $this->post('name'),
            'name' => $this->post('name'),
            'budget' => $this->post('budget'),
            'email' => $this->post('email'),
            'location' => $this->post('location'),
            'phone' => $this->post('phone'),
            'notes' => $this->post('notes'),
            'housekeeping' => $this->post('housekeeping'),
            'move_in_date' => $this->post('move_in_date'),
            'move_out_date' => $this->post('move_out_date'),
            'pets' => $this->post('pets'),
            'number_of_apartments' => $this->post('number_of_apartments'),
            'total_guests' => $this->post('total_guests'),
            'number_of_bedrooms' => $this->post('number_of_bedrooms'),
            'number_of_bathrooms' => $this->post('number_of_bathrooms'),
            'government' => $this->post('government')
        );
        $listing_id = 0;

        if ($listing && $listing->id) {
            $subject = "Get Rates Request from website!";
            $reservation['hm_guid'] = $listing->hm_guid;
            $reservation['url'] = site_url('listing/'.get_listing_url($listing));
            $listing_id = $listing->id;
        }
        $id = $this->Reservation->add(array('listing_id' => $listing_id, 'reservation_data' => json_encode($reservation)));
        $reservation['id'] = $id;

        $this->load->helper('notification');
        $response = notify_get_rates($reservation);

        json_success('Email Sent Successfully', array('response' => $response));

    }
}

?>