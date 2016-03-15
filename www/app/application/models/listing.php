<?php
class Listing extends MY_Model
{

    function get_scope()
    {
        return "listing";
    }

    protected static $fields = array(
        'name' => 'string',
        'address' => 'string',
        'hm_guid' => 'string',
        'country' => 'string',
        'state' => 'string',
        'city' => 'string',
        'featured_city_id' => 'int',
        'zipcode' => 'string',
        'is_pet_friendly' => 'int',
        'description' => 'string',
        'description_short' => 'string',
        'latitude' => 'float',
        'longitude' => 'float',
        'bedrooms' => 'int',
        'bathrooms' => 'int',
        'is_published' => 'int',
        'is_featured' => 'int'
    );

    function column_map($col)
    {
        $column_map = array('name', 'address', 'city', 'state', 'is_featured', 'is_published');
        return $column_map[intval($col)];
    }

     function get_major_city($subof, $subofstate)
    {
        $sql = "SELECT cityID FROM submarkets WHERE city = '".$subof."' AND state = '".$subofstate."'";
       $query = $this->db->query($sql);
       return $query->result();
       }

    function get_from_id($city){
       $newid = $city[0]->cityID;
       $sql = "SELECT city FROM city WHERE uuid = '".$newid."'";
       $query = $this->db->query($sql);
        return $query->result();
    }

    function load_by_conversion_id($conversion_id = 0)
    {
        if ($conversion_id) {
            $query = $this->db->get_where($this->get_scope(), array("conversion_id" => $conversion_id));
            return $this->after_load($query->row());
        }
    }

    function load_by_url($url = '')
    {
        if ($url) {
            $query = $this->db->get_where($this->get_scope(), array("url" => $url));
            return $this->after_load($query->row());
        }
    }

    function get_first_for_city($city_id=0) {
        if ($city_id) {
            $query = $this->db->get_where($this->get_scope(), array("featured_city_id" => $city_id));
            return $this->after_load($query->row());
        }
    }

    function add_data()
    {
        $this->load->library('uuid');

        $data = array(
            'uuid' => $this->uuid->v4(),
            'deleted' => 0,
            'created' => timestamp_to_mysqldatetime(now())
        );
        return $data;
    }

    function update_update_data($data, $id=0)
    {
        if(isset($data['name'])) {
            $data['url'] = $this->update_url($data, $id);
        }
        return $data;
    }

    function update_add_data($data)
    {
        if(isset($data['name'])) {
            $data['url'] = $this->update_url($data);
        }
        return $data;
    }

    function update_url($data, $id=0) {
        $url_string = $data['address'];

        $url = strtolower(url_title($url_string));

        $this->db->where('url', $url);
        if($id) {
            $this->db->where('id <>', $id);
        }
        $result = $this->db->get($this->get_scope());
        $row = $result->row();
        if($row) {
            $i=1;
            while($row) {
                $url = strtolower(url_title($url_string))."-".$i++;

                $this->db->where('url', $url);
                if($id) {
                    $this->db->where('id <>', $id);
                }
                $result = $this->db->get($this->get_scope());
                $row = $result->row();
            }
        }
        return $url;
    }

    function update_unit_types($listing_id, $unit_types) {
        $this->db->delete('listing_unit', array("listing_id" => $listing_id));
        foreach($unit_types as $unit_type) {
            $query = $this->db->query($this->db->insert_string('listing_unit', array(
                'listing_id' => $listing_id,
                'unit_type_id' => $unit_type->id,
                'rate' => $unit_type->rate
            )));
        }
    }

    function get_unit_types($listing_id) {
        $this->db->where('listing_id', $listing_id);
        $this->db->join('unit_type', 'unit_type.id = listing_unit.unit_type_id');
        $this->db->select('listing_unit.*, unit_type.name');
        $query = $this->db->get('listing_unit');
        return $query->result();
    }

    function update_amenities($listing_id, $amenities) {
        $this->db->delete('listing_amenity', array("listing_id" => $listing_id));

        foreach($amenities as $amenity_id) {
            if($amenity_id>0) {
                $query = $this->db->query($this->db->insert_string('listing_amenity', array(
                    'listing_id' => $listing_id,
                    'amenity_id' => $amenity_id
                )));
            }
        }
    }

    function get_unit_amenities($listing_id) {
        if($listing_id) {
            $this->db->where('listing_id', $listing_id);
        } else {
            /* If this is a new listing, default to at least a few amenities */
            $this->db->where_in('amenity.id', array(2,3,4,7,8,9));
        }
        $this->db->join('amenity', 'amenity.id = listing_amenity.amenity_id');
        $this->db->select('listing_amenity.*, amenity.name');
        $query = $this->db->get('listing_amenity');
        return $query->result();
    }

    function get_listings_geospatial_ids($filter, $amenities = null, $bedrooms = 0, $is_featured = false) {
        $from = '';
        $where = ' WHERE l.is_published = 1 and l.deleted = 0';

        if($amenities) {
            $i = 1;
            foreach($amenities as $amenity) {
                $where.=' and exists (select * from listing_amenity la where la.listing_id=l.id AND la.amenity_id='.intval($amenity).')';
            }
        }

        if($bedrooms) {
            if($bedrooms > 3) {
                $where.=" AND l.bedrooms > 3 ";
            } else {
                $where.=" AND l.bedrooms >= ".$bedrooms;
            }
        }

        if($is_featured) {
            $where.=' AND l.is_featured = TRUE';
        }

        $query = " SELECT l.id, "
            ." 3956 * 2 * ASIN(SQRT( POWER(SIN((? - abs(l.latitude)) * pi()/180 / 2),2) + COS(? * pi()/180 ) * "
            ." COS(abs(l.latitude) *  pi()/180) * POWER(SIN((? - l.longitude) *  pi()/180 / 2), 2) )) "
            ." as distance FROM ".$this->get_scope()." l ".$from.$where
            ." AND 3956 * 2 * ASIN(SQRT( POWER(SIN((? - abs(l.latitude)) * pi()/180 / 2),2) + COS(? * pi()/180 ) * "
            ." COS(abs(l.latitude) *  pi()/180) * POWER(SIN((? - l.longitude) *  pi()/180 / 2), 2) )) < ? ";

        $params = array(
            $filter->latitude,
            $filter->latitude,
            $filter->longitude,
            $filter->latitude,
            $filter->latitude,
            $filter->longitude,
            intval($filter->range)
        );

        $query = $this->db->query($query, $params);
        $ids = array();
        foreach($query->result() as $result) {
            $ids[] = $result->id;
        }
        return $ids;
    }

    function get_listings_geospatial($limit = 999, $offset = 0, $filter, $amenities, $bedrooms = 0, $is_featured = false) {
        $from = '';
        $where = ' WHERE l.is_published = 1 and l.deleted = 0';
        $order_by = "distance ASC";

        if($amenities) {
            $i = 1;
            foreach($amenities as $amenity) {
                $where.=' and exists (select * from listing_amenity la where la.listing_id=l.id AND la.amenity_id='.intval($amenity).')';
            }
        }

        if($bedrooms) {
            if($bedrooms > 3) {
                $where.=" AND l.bedrooms > 3 ";
            } else {
                $where.=" AND l.bedrooms >= ".$bedrooms;
            }
        }

        if($is_featured) {
            $where.=' AND l.is_featured = TRUE';
        }

        if(isset($filter->popular)) {
            $order_by = "is_featured DESC, ". $order_by;
        }

        $query = " SELECT *, "
            ." 3956 * 2 * ASIN(SQRT( POWER(SIN((? - abs(l.latitude)) * pi()/180 / 2),2) + COS(? * pi()/180 ) * "
            ." COS(abs(l.latitude) *  pi()/180) * POWER(SIN((? - l.longitude) *  pi()/180 / 2), 2) )) "
            ." as distance FROM ".$this->get_scope()." l ".$from.$where
            ." AND 3956 * 2 * ASIN(SQRT( POWER(SIN((? - abs(l.latitude)) * pi()/180 / 2),2) + COS(? * pi()/180 ) * "
            ." COS(abs(l.latitude) *  pi()/180) * POWER(SIN((? - l.longitude) *  pi()/180 / 2), 2) ))  < ? "
            ." ORDER BY ".$order_by." LIMIT ?, ?";

        $params = array(
            $filter->latitude,
            $filter->latitude,
            $filter->longitude,
            $filter->latitude,
            $filter->latitude,
            $filter->longitude,
            intval($filter->range),
            intval($offset),
            intval($limit)
        );

        $query = $this->db->query($query, $params);
        return $query->result();
    }

    function add_photo($listing_id, $original_url) {
        $query = $this->db->query($this->db->insert_string('listing_photo', array(
            'listing_id' => $listing_id,
            'original_url' => $original_url
        )));
        $id = $this->db->insert_id();
        return $id;
    }

    function get_photos($listing_id) {
        $this->db->where('listing_id', $listing_id);
        $query = $this->db->get('listing_photo');
        return $query->result();
    }

    function get_front_ids($amenities = '', $bedrooms = 0, $state = '', $featured_city_id = 0, $is_featured = false)
    {
        $query_params = array();

        $sql = 'select distinct(l.id) ';

        $where = ' WHERE l.is_published = 1 and l.deleted = 0';
        $from = ' from ' . $this->get_scope() . ' l';

        if($state) {
            $where.=' AND l.state = ?';
            $query_params[] = $state;
        }

        if($featured_city_id) {
            $where.=' AND l.featured_city_id = ?';
            $query_params[] = $featured_city_id;
        }

        if($is_featured) {
            $where.=' AND l.is_featured = ?';
            $query_params[] = TRUE;
        }

        if($bedrooms) {
            if($bedrooms > 3) {
                $where.=" AND l.bedrooms > 3 ";
            } else {
                $where.=" AND l.bedrooms >= ".$bedrooms;
            }
        }

        if($amenities) {
            foreach($amenities as $amenity) {
                $where.=' and exists (select * from listing_amenity la where la.listing_id=l.id AND la.amenity_id='.intval($amenity).')';
            }
        }

        $sql .= ' ' . $from . ' ' . $where;

        $query = $this->db->query($sql, $query_params);
        $ids = array();
        foreach($query->result() as $result) {
            $ids[] = $result->id;
        }
        return $ids;
    }

    function get_front_list($limit = 999, $offset = 0, $amenities = '', $bedrooms = 0, $state = '', $featured_city_id = 0, $is_featured = false)
    {
        $query_params = array();

        $sql = "SELECT l.* ";

        $where = ' WHERE l.is_published = 1 and l.deleted = 0';
        $from = ' from ' . $this->get_scope() . ' l';

        if($state) {
            $where.=' AND l.state = ?';
            $query_params[] = $state;
        }

        if($featured_city_id) {
            $where.=' AND l.featured_city_id = ?';
            $query_params[] = $featured_city_id;
        }

        if($is_featured) {
            $where.=' AND l.is_featured = ?';
            $query_params[] = TRUE;
        }

        if($bedrooms) {
            if($bedrooms > 3) {
                $where.=" AND l.bedrooms > 3 ";
            } else {
                $where.=" AND l.bedrooms >= ".$bedrooms;
            }
        }

        if($amenities) {
            foreach($amenities as $amenity) {
                $where.=' and exists (select * from listing_amenity la where la.listing_id=l.id AND la.amenity_id='.intval($amenity).')';
            }
        }

        $query_params[] = intval($offset);
        $query_params[] = intval($limit);

        $sql .= ' ' . $from . ' ' . $where . " ORDER BY is_featured DESC, name ASC LIMIT ?, ? ";

        $query = $this->db->query($sql, $query_params);
        //echo $this->db->last_query();
        return $query->result();
    }

    function get_count($filter = '', $city_id = 0)
    {
        $sql = 'select count(l.id) as cnt ';

        $where = 'WHERE l.deleted = 0';
        $from = ' from ' . $this->get_scope() . ' l';
        $query_params = array();

        if ($filter) {
            $where .= ' AND (l.name like ? OR l.address like ? OR l.city like ?)';
            array_unshift($query_params, '%'.$filter.'%');
            array_unshift($query_params, '%'.$filter . '%');
            array_unshift($query_params, '%'.$filter . '%');
        }

        if($city_id) {
            $where.=' AND featured_city_id = ?';
            $query_params[] = $city_id;
        }

        $sql .= ' ' . $from . ' ' . $where;

        $query = $this->db->query($sql, $query_params);
        $row = $query->row();
        return $row->cnt;
    }

    function get_list($limit = 999, $offset = 0, $ordering = '', $filter = '', $city_id = 0)
    {
        if (!$ordering) {
            $ordering = array('sort' => 'name', 'dir' => 'ASC');
        } else {
            $ordering['sort'] = $this->column_map($ordering['sort']);
        }

        $query_params = array();

        $sql = "SELECT l.* ";

        $where = ' WHERE l.deleted = 0';
        $from = ' from ' . $this->get_scope() . ' l';

        if ($filter) {
            $where .= ' AND (l.name like ? OR l.address like ? OR l.city like ?)';
            array_unshift($query_params, '%'.$filter . '%');
            array_unshift($query_params, '%'.$filter . '%');
            array_unshift($query_params, '%'.$filter . '%');
        }

        if($city_id) {
            $where.=' AND featured_city_id = ?';
            $query_params[] = $city_id;
        }

        $query_params[] = $offset;
        $query_params[] = $limit;

        $sql .= ' ' . $from . ' ' . $where . " ORDER BY " . $this->get_ordering($ordering) . " LIMIT ?, ? ";

        $query = $this->db->query($sql, $query_params);
        //echo $this->db->last_query();
        return $query->result();
    }

    function get_featured()
    {
        $ordering = array('sort' => 'name', 'dir' => 'ASC');

        $query_params = array();

        $sql = "SELECT l.* ";

        $where = ' WHERE l.is_published = 1 and l.is_featured = 1';
        $from = ' from ' . $this->get_scope() . ' l';
        $sql .= ' ' . $from . ' ' . $where . " ORDER BY " . $this->get_ordering($ordering);

        $query = $this->db->query($sql, $query_params);
        //echo $this->db->last_query();
        return $query->result();
    }

        function get_featured_from_city($data)
    {
        $ordering = array('sort' => 'name', 'dir' => 'ASC');

        $city = $data;

        $query_params = array();

        $sql = "SELECT l.* ";

        $where = " WHERE l.is_published = 1 and l.is_featured = 1 and l.city = '$city'";
        $from = ' from ' . $this->get_scope() . ' l';
        $sql .= ' ' . $from . ' ' . $where . " ORDER BY " . $this->get_ordering($ordering) .' LIMIT 2';

        $query = $this->db->query($sql, $query_params);
        //echo $this->db->last_query();
        return $query->result();
    }

    function mock() {
        $listing = new stdClass;
        $listing->title = ucfirst(random_pronounceable_word())." ".ucfirst(random_pronounceable_word())
            ." ".ucfirst(random_pronounceable_word());
        $listing->dog_friendly = 1;
        $listing->phone = "888.888.8888";

        $this->load->library('uuid');
        $listing->uuid = $this->uuid->v4();


        $listing->address = rand(100,999)." ".ucfirst(random_pronounceable_word())." ".ucfirst(random_pronounceable_word())." St";
        $listing->city = "Dallas";
        $listing->state = "TX";
        $listing->zipcode = "73733";
        $levels = array();
        $level = new stdClass;
        $level->type = 'Studio';
        $level->price = 'Call for pricing';
        $level->available = rand(0,25);
        $levels[] = $level;

        $level = new stdClass;
        $level->type = '1 Bedroom';
        $level->price = '$1,500/mth';
        $level->available = rand(0,25);
        $levels[] = $level;

        $level = new stdClass;
        $level->type = '2 Bedrooms';
        $level->price = 'Call for pricing';
        $level->available = 0;
        $levels[] = $level;

        $listing->levels = $levels;

        $listing->distance = rand(0,10)." miles";

        $description = '';
        $length = rand(50, 100);
        for($i=0; $i<$length; $i++) {
            $description.=random_pronounceable_word(rand(3,10))." ";
        }
        $listing->description = $description;

        $listing->short_description = word_limiter($listing->description, 20);

        $amenities = array('Pool', 'Close to schools', 'Dish washer', 'Microwave', 'Flat Screen TV', 'High-Speed Internet',
            'Hard-wood Floors', 'Extended Channels Lineup', 'Washer/Dryer', 'Assigned Parking', 'Covered Patio');
        $amenity_ids = array_unique(array_rand($amenities, rand(5,11)));
        shuffle($amenity_ids);
        $listing->amenities = array();

        foreach($amenity_ids as $id) {
            $listing->amenities[] = $amenities[$id];
        }

        $listing->walkable_score = rand(55,100);
        $listing->walkable_descr = "Very Walkable";

        $listing->beds = "1-2";
        $listing->baths = "2";


        return $listing;
    }

    public function blank() {
        $listing = new stdClass;
        $listing->id = 0;
        $listing->uuid = NULL;
        $listing->hm_guid = NULL;
        $listing->name = '';
        $listing->address = '';
        $listing->city = '';
        $listing->state = '';
        $listing->zipcode = '';
        $listing->country = 'US';
        $listing->description = '';
        $listing->latitude = '';
        $listing->longitude = '';
        $listing->featured_city_id = 0;
        $listing->description_short = '';
        $listing->bedrooms = 0;
        $listing->bathrooms = 0;

        $listing->is_pet_friendly = false;
        $listing->is_published = false;
        $listing->is_featured = false;
        $listing->is_popular = false;

        return $listing;
    }
}
?>