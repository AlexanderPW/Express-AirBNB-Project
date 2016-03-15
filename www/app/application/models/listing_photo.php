<?php
class Listing_Photo extends MY_Model
{

    function get_scope()
    {
        return "listing_photo";
    }

    protected static $fields = array(
    );

    function delete_by_listing_id($id=0) {
        $this->db->delete($this->get_scope(), array('listing_id' => $id));
    }

    function load_by_photo_url($url='')
    {
        if ($url) {
            $query = $this->db->get_where($this->get_scope(), array("original_url" => $url));
            return $this->after_load($query->row());
        } else {
            return $this->blank();
        }
    }
}
?>