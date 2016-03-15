<?
class Cities extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->validate_admin();
        $this->load->model(array('Submarkets', 'City'));
        $this->load->helper('json');
    }

    public function index_post() {
        $this->load->helper('json');
        $count = $this->City->get_count($this->get_grid_filter());
        $objects = $this->City->get_list($this->get_grid_limit(), $this->get_grid_offset(), $this->get_grid_ordering(), $this->get_grid_filter());
        $objects = $this->decorate_objects($objects);
        $result = grid_result($count, $objects);
        echo $result;
    }

    public function city_get($uuid='') {

        if($uuid) {
            $city = $this->City->load_by_uuid($uuid);
            $city = $this->add_submarkets($city);
        } else {
            $city = $this->City->blank();
        }
        /* Unset things in the json that aren't necessary */
        $city = $this->decorate_object($city);
        $this->response($city);
    }

    public function add_submarkets($object) {
        
            $object->submarket_list = $this->Submarkets->get_local_submarkets($object->uuid);
        
        return $object;
    }

    public function city_put($uuid = 0) {
        $this->City->update_by_uuid($uuid, $this->get_put_fields($this->City->get_fields()));
        $id = $this->City->get_id($uuid);
        geocode_city($id);

        json_success('The city has been updated successfully');
    }

     public function sub_get($uuid='') {

        
            $city = $this->Submarkets->blank($uuid);
       
        /* Unset things in the json that aren't necessary */
        $city = $this->decorate_object($city);
        $city = $this->fetch_city($city, $uuid);
        $this->response($city);
    }

    public function editsub_get($uuid='') {

         $city = $this->Submarkets->load_submarket($uuid);
        $city = $this->fetch_subcity($city, $city->submarket[0]->cityID);
         $this->response($city);
    }

      public function sub_put($uuid = 0) {
        $this->Submarkets->update_by_uuid($uuid, $this->get_put_fields($this->Submarkets->get_fields()));
        $id = $this->Submarkets->get_id($uuid);
        geocode_city($id);

        json_success('The city has been updated successfully');
    }

     public function sub_post() {
        $id = $this->Submarkets->add($this->get_post_fields($this->Submarkets->get_fields()));

        geocode_subcity($id);
        json_success('The Submarket you added has been saved successfully.', array('uuid'=>$this->Submarkets->get_uuid($id)));
    }

    public function picture_post() {
        $this->load->library(array('uuid', 'image_moo'));
        $uuid = $this->post('uuid');

        $config['upload_path'] = $this->config->item('upload_dir');
        $config['allowed_types'] = $this->config->item('upload_types');
        $config['max_size'] = $this->config->item('max_file_upload_size');
        $this->load->library('upload', $config);

        $file_url = '';

        if ($this->upload->do_upload('picture')) {
            $data = $this->upload->data();
            $file_url = $this->uuid->v4().$data['file_ext'];

            try {
                $city_id = $this->City->get_id($uuid);
                rename($data['full_path'], $this->config->item('image_dir').$file_url);
                $this->City->update($city_id, array('photo_original_url'=>$file_url));
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
        return json_success("Picture Uploaded Successfully", array("picture_url"=> $full_url));
    }

    public function picture_delete_get($uuid='') {
        $city_id = $this->City->get_id($uuid);
        $this->City->update($city_id, array('photo_original_url'=>NULL));
        return json_success("Picture Deleted Successfully");
    }

    public function city_post() {
        $id = $this->City->add($this->get_post_fields($this->City->get_fields()));

        geocode_city($id);
        json_success('The city you created has been saved successfully.', array('uuid'=>$this->City->get_uuid($id)));
    }

    public function decorate_objects($objects)
    {
        $updated_objects = array();
        foreach ($objects as $object) {
            $object->DT_RowId = $object->uuid;
            $object->published = "No";
            if($object->is_popular) {
                $object->is_popular = "Yes";
            }

            $object->listing_count = $this->City->get_listing_count($object->id);

            $updated_objects[] = $object;
        }
        return $updated_objects;
    }

    public function decorate_object($object) {
        if($object->photo_original_url) {
            $object->photo_original_url = site_url('assets/listings/resized/'.$object->photo_original_url);
        }
        return $object;
    }
     public function fetch_city($object, $uuid) {
        $object->orig_city = $this->City->load_by_uuid($uuid);
        $object->state = $object->orig_city->state;
        return $object;
    }

    public function fetch_subcity($object, $uuid) {
        $object->orig_city = $this->City->load_by_uuid($uuid);
        $object->state = $object->orig_city->state;
        $object->city = '';
        $object->newuuid = '';
        $object->id = 0;
        $object->is_popular = false;
        $object->photo_original_url = '';
        $object->deleted = 0;
        return $object;
   
}


     


    public function delete_post() {
        $uuid = $this->post('uuid');
        if($uuid) {
            $city = $this->City->load_by_uuid($uuid);
            $this->City->delete($city->id);
            json_success('The city has been permanently deleted successfully.', array('uuid'=>$uuid));
        }
    }

        public function sub_delete_post() {
        $uuid = $this->post('uuid');
        if($uuid) {
            $city = $this->Submarkets->load_submarket($uuid);
            $this->Submarkets->delete($city->submarket[0]->id);
            json_success('The city has been permanently deleted successfully.', array('uuid'=>$uuid));
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