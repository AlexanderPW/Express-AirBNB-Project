<?php
class City extends MY_Model
{

    function get_scope()
    {
        return "city";
    }

    protected static $fields = array(
        'state' => 'string',
        'city' => 'string',
        'is_popular' => 'int'
    );

    function load_by_url($url = '')
    {
        if ($url) {
            $query = $this->db->get_where($this->get_scope(), array("url" => $url));
            return $this->after_load($query->row());
        }
    }

    function load_by_city_state($city = '', $state = '') {
        if ($city && $state) {
            $query = $this->db->get_where($this->get_scope(), array("city" => $city, "state" => $state));
            return $this->after_load($query->row());
        }

    }

    function get_city_id($listings) {
       $sql = "SELECT uuid, state FROM city WHERE city = ?";
        $query = $this->db->query($sql, $listings);
        $listing = $query->result();
        return $listing;
    }

function get_city_submarkets($newid)
    {
        $id = $newid->uuid;

      $sql = "SELECT * FROM submarkets WHERE cityID = ? AND deleted = 0";
    $query = $this->db->query($sql, $id);
    $listing = new stdClass;
    $listing->state = $newid->state;
      $listing = $query->result();
      $type = gettype($id);
        return $listing;
    }



    function add_data()
    {
        $this->load->library('uuid');

        $data = array(
            'uuid' => $this->uuid->v4(),
            'created' => timestamp_to_mysqldatetime(now())
        );
        return $data;
    }

    function update_update_data($data, $id=0)
    {
        if(isset($data['city'])) {
            $data['url'] = $this->update_url($data, $id);
        }
        return $data;
    }

    function update_add_data($data)
    {
        if(isset($data['city'])) {
            $data['url'] = $this->update_url($data);
        }
        return $data;
    }

    function update_url($data, $id=0) {
        $url = strtolower(str_replace(" ", "", $data['city'])).'corporatehousing';
        return $url;
    }

    function get_listing_count($city_id=0)
    {
        $query_params = array();

        $sql = 'select count(l.id) as cnt from listing l where l.featured_city_id = ?';

        $query = $this->db->query($sql, $city_id);
        $row = $query->row();
        return $row->cnt;
    }

    function get_count($filter = array())
    {
        $query_params = array();

        $sql = 'select count(c.id) as cnt ';

        $where = ' WHERE c.deleted = 0';
        $from = ' from ' . $this->get_scope() . ' c';
        if ($filter) {
            $where .= ' AND (c.city like ?)';
            array_unshift($query_params, '%'.$filter.'%');
        }

        $sql .= ' ' . $from . ' ' . $where;

        $query = $this->db->query($sql, $query_params);
        $row = $query->row();
        return $row->cnt;
    }

    function get_list($limit = 999, $offset = 0, $ordering = '', $filter = array(), $is_popular = 0)
    {
        $ordering = array('sort' => 'city', 'dir' => 'ASC');

        $query_params = array();

        $sql = "SELECT c.* ";

        $where = ' WHERE c.deleted = 0';
        $from = ' from ' . $this->get_scope() . ' c';
        if ($filter) {
            $where .= ' AND (c.city like ?)';
            array_unshift($query_params, '%'.$filter.'%');
        }

        if($is_popular) {
            $where.=' AND c.is_popular = 1';
        }

        $query_params[] = $offset;
        $query_params[] = $limit;

        $sql .= ' ' . $from . ' ' . $where . " ORDER BY " . $this->get_ordering($ordering) . " LIMIT ?, ? ";

        $query = $this->db->query($sql, $query_params);
        //echo $this->db->last_query();
        return $query->result();
    }

    public function blank() {
        $listing = new stdClass;
        $listing->id = 0;
        $listing->uuid = NULL;
        $listing->city = '';
        $listing->state = '';
        $listing->is_popular = false;
        $listing->photo_original_url = '';
        $listing->deleted = 0;

        return $listing;
    }
}
?>