<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Conversion extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function resize()
    {
        $this->load->model(array('City', 'Listing', 'Listing_Photo', 'Listing_Amenity'));
        $this->load->library(array('uuid', 'image_moo'));
        $cities = $this->City->get_all();
        foreach ($cities as $city) {
            $this->create_thumbnails($city->photo_original_url);
        }

        $listings = $this->Listing->get_all();
        foreach ($listings as $listing) {
            $photos = $this->Listing->get_photos($listing->id);
            foreach ($photos as $photo) {
                $this->create_thumbnails($photo->original_url);
            }
        }
    }

    private function create_thumbnails($file_url)
    {
        /* Resize/Crop the various sizes */
        if ($file_url && !file_exists($this->config->item('image_resize_dir') . $file_url)) {
            $image = $this->config->item('image_dir') . $file_url;
            $thumbnail = $this->config->item('image_resize_dir') . $file_url;
            $this->image_moo->load($image)->resize_crop(IMG_SIZE_WIDTH, IMG_SIZE_HEIGHT)->save($thumbnail, TRUE);
        }

        return '/assets/listings/resized/' . $file_url;
    }

    public function index()
    {
        return false;

        $amenities = array(
            'pool' => 1,
            'extra2' => 2,
            'extra4' => 3,
            'extra3' => 4,
            'extra5' => 5,
            //'laundry' => 6,
            'extra6' => 7,
            'extra7' => 8,
            'extra8' => 9,
            'extra13' => 10,
            'extra14' => 11,
            'extra15' => 12,
            'extra16' => 13,
            'extra18' => 14,
            'extra19' => 15,
            //'parking garage' => 16,
            //'open parking' => 17,
            //'reserved parking' => 18,
            //'garages available' => 19,
            //'fitness center' => 20,
            //'business center' => 21,
            'extra12' => 22,
            //'concierge' => 23,
            //'high-rise' => 24,
            //'jogging' => 25
        );

        $this->load->model(array('Listing', 'Listing_Photo', 'Listing_Amenity'));
        $this->load->library(array('uuid', 'image_moo'));
        $row = 1;
        if (($handle = fopen(APPPATH . "controllers/util/Properties.csv", "r")) !== FALSE) {
            $headers = array();
            $values = array();
            while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
                $column_count = count($data);
                if ($row == 1) {
                    $column_index = 0;
                    foreach ($data as $column) {
                        $column = strtolower($column);
                        $headers[] = $column;
                        $column_index++;
                    }
                } else {
                    $row_values = array();
                    $column_index = 0;
                    foreach ($data as $column) {
                        $row_values[$headers[$column_index]] = $column;
                        $column_index++;
                    }
                    $values[] = $row_values;
                }
                $row++;
            }
            fclose($handle);

            if (true) {
                foreach ($values as $row) {

                    $conversion_id = intval($row['id']);
                    $name = trim(ucwords($row['alias']));
                    $description = htmlentities(trim(clean_smart_quotes($row['propdesc'])));
                    $description_short = htmlentities(trim(clean_smart_quotes($row['smalldesc'])));
                    $address = trim($row['street_num'] . " " . $row['address2']);

                    $url = trim($address);

                    if ($conversion_id) {
                        $data = array(
                            'conversion_id' => intval($row['id']),
                            'uuid' => $this->uuid->v4(),
                            'created' => timestamp_to_mysqldatetime(now()),
                            'name' => $name,
                            'address' => $address,
                            'country' => $row['country'],
                            'state' => stateNameToAbbr($row['state']),
                            'city' => trim($row['locality']),
                            'zipcode' => $row['postcode'],
                            'is_pet_friendly' => intval($row['pets']),
                            'latitude' => $row['declat'],
                            'longitude' => $row['declong'],
                            'bedrooms' => intval($row['bedrooms']),
                            'description' => $description,
                            'description_short' => $description_short,
                            'is_published' => intval($row['published']),
                            'url' => strtolower(url_title($url, '-'))
                        );

                        $existing = $this->Listing->load_by_conversion_id($conversion_id);

                        if ($existing) {
                            $this->Listing->update($existing->id, $data);
                        } else {
                            $this->Listing->add($data);
                            $existing = $this->Listing->load_by_conversion_id($conversion_id);
                        }
                        $this->Listing_Photo->delete_by_listing_id($existing->id);

                        /** Add Photos */
                        for ($i = 1; $i < 13; $i++) {
                            $image_url = $row['image' . $i];

                            /* Resize the image */
                            $file_url = $this->config->item('image_dir') . $image_url;
                            $resize_url = $this->config->item('image_resize_dir') . $image_url;
                            if ($image_url) {
                                unlink($resize_url);
                                $this->image_moo->load($file_url)->resize_crop(IMG_SIZE_WIDTH, IMG_SIZE_HEIGHT)->save($resize_url, TRUE);

                                $photo = $this->Listing_Photo->load_by_photo_url($image_url);
                                if (!$photo) {
                                    $this->Listing_Photo->add(array(
                                        'listing_id' => $existing->id,
                                        'original_url' => $image_url
                                    ));
                                }
                            }
                        }

                        $this->Listing_Amenity->delete_by_listing_id($existing->id);
                        /** Add Amenities */
                        foreach ($amenities as $amenity => $amenity_id) {
                            if (intval($row[$amenity])) {
                                //echo $amenity." ".$amenity_id."<br/>";
                                $this->Listing_Amenity->add_if_not_exists(array('listing_id' => $existing->id, 'amenity_id' => $amenity_id));
                            }
                        }
                    }
                }
            }
            $this->load->model('City');
            $cities = $this->City->get_list();
            foreach ($cities as $city) {
                $city->range = 50;
                $listings = $this->Listing->get_listings_geospatial_ids($city);
                foreach ($listings as $listing) {
                    $this->Listing->update($listing, array('featured_city_id' => $city->id));
                }
            }
        }
    }

    function reset_conversion($conversion_key = '')
    {
        $this->load->model(array('Listing', 'Listing_Photo', 'Listing_Amenity'));
        if ($conversion_key == '138SmPKlj6JK') {
            $this->Listing->reset_all();
        }
    }
}