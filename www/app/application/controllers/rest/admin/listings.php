<?
class Listings extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->validate_admin();
        $this->load->model(array('Listing', 'City'));
        $this->load->helper('json');
    }

    public function index_post() {
        $this->load->helper('json');
        $city_id = intval($this->post('featured_city_id'));
        $count = $this->Listing->get_count($this->get_grid_filter(), $city_id);
        $objects = $this->Listing->get_list($this->get_grid_limit(), $this->get_grid_offset(), $this->get_grid_ordering(), $this->get_grid_filter(), $city_id);
        $objects = $this->decorate_objects($objects);
        $result = grid_result($count, $objects);
        echo $result;
    }

    public function listing_get($uuid='') {

        if($uuid) {
            $listing = $this->Listing->load_by_uuid($uuid);
        } else {
            $listing = $this->Listing->blank();
        }
        /* Unset things in the json that aren't necessary */
        $listing = $this->decorate_object($listing);
        $this->response($listing);
    }

    public function listing_put($uuid = 0) {
        $this->Listing->update_by_uuid($uuid, $this->get_put_fields($this->Listing->get_fields()));
        $id = $this->Listing->get_id($uuid);

        /* Update the unit types */
        $unit_types = $this->put('unit_type');
        $updated_types = array();
        foreach($unit_types as $unit_type) {
            if($unit_type) {
                $type = new stdClass;
                $type->id = $unit_type;
                $type->rate = $this->put('unit_type_'.$unit_type.'_rate');
                $updated_types[] = $type;
            }
        }
        $this->Listing->update_unit_types($id, $updated_types);

        $this->Listing->update_amenities($id, $this->put('amenity'));

        geocode_listing($id);

        json_success('The listing has been updated successfully');
    }

    public function picture_delete_get($id=0) {
        $this->load->model('Listing_Photo');

        $this->Listing_Photo->delete($id);
        json_success('The photo has been deleted successfully');
    }

    public function picture_post() {
        $this->load->library(array('uuid', 'image_moo'));
        $uuid = $this->post('uuid');
        $photo_id = 0;

        $config['upload_path'] = $this->config->item('upload_dir');
        $config['allowed_types'] = $this->config->item('upload_types');
        $config['max_size'] = $this->config->item('max_file_upload_size');
        $this->load->library('upload', $config);

        $file_url = '';

        if ($this->upload->do_upload('picture')) {
            $data = $this->upload->data();
            $file_url = $this->uuid->v4().$data['file_ext'];

            try {
                rename($data['full_path'], $this->config->item('image_dir').$file_url);
                $photo_id = $this->Listing->add_photo($this->Listing->get_id($uuid), $file_url);

                $full_url = $this->create_thumbnails($data, $file_url);

            } catch (Exception $e) {
                $error = array('error' => 'We experience an error while trying to upload your file.  Please try again');
                log_message('info', '[File Add] putObject Exception: ' . $e->getMessage());
                json_error($error);
                return;
            }
            //unlink($data['full_path']);
        } else {
            return json_error($this->upload->display_errors());
        }
        return json_success("Picture Uploaded Successfully", array("picture_url"=> $full_url, "photo_id" => $photo_id));
    }

    public function listing_post() {
        $id = $this->Listing->add($this->get_post_fields($this->Listing->get_fields()));

        /* Update the unit types */
        $unit_types = $this->post('unit_type');
        $updated_types = array();
        foreach($unit_types as $unit_type) {
            if($unit_type) {
                $type = new stdClass;
                $type->id = $unit_type;
                $type->rate = $this->post('unit_type_'.$unit_type.'_rate');
                $updated_types[] = $type;
            }
        }
        $this->Listing->update_unit_types($id, $updated_types);

        $this->Listing->update_amenities($id, $this->post('amenity'));
        geocode_listing($id);
        json_success('The listing you created has been saved successfully.', array('uuid'=>$this->Listing->get_uuid($id)));
    }

    public function decorate_objects($objects)
    {
        $updated_objects = array();
        foreach ($objects as $object) {
            $object->DT_RowId = $object->uuid;
            $object->published = "No";
            if($object->is_published) {
                $object->published = "Yes";
            }
            $object->featured = "No";
            if($object->is_featured) {
                $object->featured = "Yes";
            }
            $updated_objects[] = $object;
        }
        return $updated_objects;
    }

    public function decorate_object($object) {
        $object->unit_types = $this->Listing->get_unit_types($object->id);
        $object->amenities = $this->Listing->get_unit_amenities($object->id);
        $photos = $this->Listing->get_photos($object->id);
        $object->photos = array();
        foreach($photos as $photo) {
             $photo->original_url = site_url('assets/listings/resized/'.$photo->original_url);
            $object->photos[] = $photo;
        }
        return $object;
    }

    public function delete_post() {
        $uuid = $this->post('uuid');
        if($uuid) {
            $listing = $this->Listing->load_by_uuid($uuid);
            $this->Listing->delete($listing->id);
            json_success('The listing has been permanently deleted successfully.', array('uuid'=>$uuid));
        }
    }

    private function create_thumbnails($data, $file_url) {
        /* Resize/Crop the various sizes */
        $image = $this->config->item('image_dir').$file_url;
        $thumbnail = $this->config->item('image_resize_dir').$file_url;
        $this->image_moo->load($image)->resize_crop(IMG_SIZE_WIDTH,IMG_SIZE_HEIGHT)->save($thumbnail, TRUE);

        return '/assets/listings/resized/'.$file_url;
    }
}?>