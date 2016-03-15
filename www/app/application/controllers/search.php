<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Search extends User_Controller
{
    public function index() {
        $cityzip = trim($this->input->post('cityzip', TRUE));
        $state = trim($this->input->post('state', TRUE));
        $range = trim($this->input->post('range', TRUE));
        $mode = trim($this->input->post('mode', TRUE));
        $zipcode = '';
        $city = '';

        if(is_numeric($cityzip)) {
            $zipcode = $cityzip;
        } else {
            $city = $cityzip;
        }

        $url = site_url('listings');

        /** URL STRUCTURE - /listings/COUNTRY/STATE/CITY/ZIP/RANGE */
        $street_address = $city." ".$state.", ".$zipcode;
        $address = $this->geocode_address($street_address);
        array_print($address);
        if(isset($address->state) && isset($address->city)) {
            $url.='/'.$address->country.'/'.$address->state.'/'.$address->city;

            if($address->zip) {
                $url.="/".$address->zip;
            }

            if($range) {
                $url.='/'.$range;
            }
            if($mode) {
                $url.='/'.$mode;
            }
            redirect($url);

        }
        redirect(site_url('listings'));
    }

    private function geocode_address($address) {
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($address)."&sensor=false";
        $json = file_get_contents($url);
        $result = json_decode($json)->results[0];

        $address = new stdClass;
        foreach($result->address_components as $component) {
            if(in_array('locality', $component->types)) {
                $address->city = $component->long_name;
            } else if(in_array('administrative_area_level_1', $component->types)) {
                $address->state = $component->short_name;
            } else if(in_array('postal_code', $component->types)) {
                $address->zip = $component->short_name;
            } else if(in_array('country', $component->types)) {
                $address->country = $component->short_name;
            }
        }

        //array_print($result);
        //array_print($address);
        //exit;
        return $address;
    }
}?>