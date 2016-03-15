<?
class Cities extends REST_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper('json');
        $this->load->model(array('City','Listing'));
    }

    /**
     * Gets the list of featured listings for the front page.
     */
    function popular_get() {
        $this->load->helper('json');
        $this->load->library('uuid');

        $cities = $this->City->get_list(999, 0, '', array(), 1);

        /* Featured cities need to be a multiple of 3 for the slider */
        if($this->get('slider')) {
            while(sizeof($cities)%3>0) {
                $city = clone $cities[array_rand($cities)];
                /** Need to randomize the uuid or else backbone will treat it as a duplicate and discard it */
                $city->uuid = $this->uuid->v4();
                $cities[] = $city;
            }
        }
        //array_print($cities);
        $listings = $this->decorate_objects($cities, sizeof($cities));
        $this->response($listings);
    }

     function combo_get() {
        $this->load->helper('json');
        $this->load->library('uuid');

        $cities = $this->City->get_list(999, 0, '', array(), 1);


        /* Featured cities need to be a multiple of 3 for the slider */
        if($this->get('slider')) {
            while(sizeof($cities)%3>0) {
                $city = clone $cities[array_rand($cities)];
                /** Need to randomize the uuid or else backbone will treat it as a duplicate and discard it */
                $city->uuid = $this->uuid->v4();
                $cities[] = $city;
            }
        }

        //array_print($cities);
        $listings = $this->combo_decorate_objects($cities, sizeof($cities));
 
        $this->response($listings);
    }

     public function combo_decorate_objects($objects)
    {
        $updated_objects = array();
        foreach ($objects as $object) {

            $object->listing_cout = $this->City->get_listing_count($object->id);

            $listing_features = $this->Listing->get_featured_from_city($object->city);

            $object->listing_feature = $this->decorate_feature_objects($listing_features, sizeof($listing_features));

            if($object->photo_original_url) {
                $object->photo = $this->config->item('cloudfront_url') .
                    'assets/listings/resized/'.$object->photo_original_url;
            } else {
                $listing = $this->Listing->get_first_for_city($object->id);
                if($listing) {
                    $photos = $this->Listing->get_photos($listing->id);
                    if($photos) {
                        $photo = $photos[0];
                        $object->photo = $this->config->item('cloudfront_url') .
                            'assets/listings/'.$photo->original_url;
                    }
                }
            }

            $object->url = 'US/'.$object->state.'/'.$object->city;

            $updated_objects[] = $object;
        }
        return $updated_objects;
    }




    public function decorate_objects($objects)
    {
        $updated_objects = array();
        foreach ($objects as $object) {

            $object->listing_cout = $this->City->get_listing_count($object->id);

            if($object->photo_original_url) {
                $object->photo = $this->config->item('cloudfront_url') .
                    'assets/listings/resized/'.$object->photo_original_url;
            } else {
                $listing = $this->Listing->get_first_for_city($object->id);
                if($listing) {
                    $photos = $this->Listing->get_photos($listing->id);
                    if($photos) {
                        $photo = $photos[0];
                        $object->photo = $this->config->item('cloudfront_url') .
                            'assets/listings/'.$photo->original_url;
                    }
                }
            }

            $object->url = 'US/'.$object->state.'/'.$object->city;

            $updated_objects[] = $object;
        }
        return $updated_objects;
    }



        public function decorate_feature_objects($objects)
    {
        $updated_objects = array();
        foreach ($objects as $object) {
            $object->id = intval($object->id);
            $object->featured_city_id = intval($object->featured_city_id);
            $object->bedrooms = intval($object->bedrooms);
            $object->is_pet_friendly = intval($object->is_pet_friendly);
            $object->is_featured = intval($object->is_featured);
            $object->is_published = intval($object->is_published);
            $object->latitude = floatval($object->latitude);
            $object->longitude = floatval($object->longitude);
            unset($object->conversion_id, $object->deleted);

            if (isset($object->distance)) {
                $object->distance = round($object->distance, 1) . " Miles";
            }

            $photos = $this->Listing->get_photos($object->id);
            $object->photos = array();
            foreach ($photos as $photo) {
                $photo->original_url = $this->config->item('cloudfront_url') .
                    'assets/listings/resized/' . $photo->original_url;
                $object->photos[] = $photo;
            }

            $object->url = get_listing_url($object);

            $updated_objects[] = $object;
        }
        return $updated_objects;
    }


}

?>