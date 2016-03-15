<?php
class Listing_Amenity extends MY_Model
{

    function get_scope()
    {
        return "listing_amenity";
    }

    protected static $fields = array(
    );

    function delete_by_listing_id($id=0) {
        $this->db->delete($this->get_scope(), array('listing_id' => $id));
    }

    function add_if_not_exists($data)
    {
        $query = $this->db->get_where($this->get_scope(), array(
            "listing_id" => $data['listing_id'],
            "amenity_id" => $data['amenity_id']
        ));
        $row = $query->row();
        if(!$row) {
            $this->add($data);
        }

    }

    function get_amenity_counts($listing_ids) {
        $sql = "SELECT  a.id, IFNULL(listing_amenities.amenity_count, 0) AS cnt FROM amenity a "
        ." LEFT JOIN (SELECT la.amenity_id AS amenity_id, COUNT(distinct(la.listing_id)) AS amenity_count FROM listing_amenity la "
        ." WHERE la.listing_id IN (".implode($listing_ids, ',').") GROUP BY la.amenity_id) AS listing_amenities ON "
        ." listing_amenities.amenity_id = a.id";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        return $query->result();
    }
}
?>