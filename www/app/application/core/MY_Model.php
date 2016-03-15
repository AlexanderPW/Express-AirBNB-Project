<?php

class MY_Model extends CI_Model
{

    var $id;
    protected static $fields = array();

    public function __construct()
    {
        parent::__construct();
    }

    public function get_scope()
    {
        return "";
    }

    function get_count()
    {
        return $this->db->count_all($this->get_scope());
    }

    function get_fields()
    {
        return static::$fields;
    }

    function get_ordering($ordering)
    {
        $sort = $ordering['sort'];
        $dir = $ordering['dir'];

        if (is_array($sort)) {
            $order_string = '';
            $i = 0;
            foreach ($sort as $col) {
                if ($i > 0) {
                    $order_string .= ', ';
                }
                $order_string .= $col . ' ' . $dir . ' ';
                $i++;
            }
            return $order_string;
        } else {
            return $ordering['sort'] . " " . $ordering['dir'];
        }
    }

    function load($id = 0)
    {
        if (intval($id)) {
            $query = $this->db->get_where($this->get_scope(), array("id" => $id));
            return $this->after_load($query->row());
        } else {
            return $this->blank();
        }
    }

    function load_by_uuid($uuid = '')
    {
        if ($uuid) {
            $query = $this->db->get_where($this->get_scope(), array("uuid" => $uuid));
            return $this->after_load($query->row());
        } else {
            return $this->blank();
        }
    }

    function load_by_name($name = '')
    {
        $this->db->where('name', trim($name));
        $query = $this->db->get($this->get_scope());
        return $this->after_load($query->row());
    }

    function load_like_name($name = '')
    {
        $this->db->like('name', $name);
        $query = $this->db->get($this->get_scope());
        return $this->after_load($query->row());
    }

    function get_id($uuid = 0)
    {
        $this->db->select('id');
        $query = $this->db->get_where($this->get_scope(), array("uuid" => $uuid));
        $row = $query->row();
        if($row) {
            return $row->id;
        } else {
            return 0;
        }
    }

    function get_uuid($id = 0)
    {
        $this->db->select('uuid');
        $query = $this->db->get_where($this->get_scope(), array("id" => $id));
        $row = $query->row();
        if($row) {
            return $row->uuid;
        } else {
            return NULL;
        }
    }

    function delete($id = 0)
    {
        if (intval($id)) {
            if ($this->db->field_exists('deleted', $this->get_scope())) {
                $this->mark_deleted($this->get_scope(), array('id' => $id));
            } else {
                $this->db->delete($this->get_scope(), array("id" => $id));
            }
            $this->after_delete($id);
        }
    }

    function mark_deleted($table, $where_data)
    {
        $this->db->where($where_data);
        $this->db->update($table, array('deleted' => 1));
    }

    function mark_undeleted($table, $where_data)
    {
        $this->db->where($where_data);
        $this->db->update($table, array('deleted' => 0));
    }

    function count_date($days = 30)
    {
        $min_date = timestamp_to_mysqldate(add_day(-1 * $days));
        $this->db->where('created >=', $min_date);
        $this->db->from($this->get_scope());
        return $this->db->count_all_results();
    }

    function sum_field_date($field = '', $days = 30)
    {
        $min_date = timestamp_to_mysqldate(add_day(-1 * $days));
        $this->db->where('created >=', $min_date);
        $this->db->select_sum($field);
        $query = $this->db->get($this->get_scope());
        $row = $query->row();
        return $row->$field;
    }

    function get_list($limit = 999, $offset = 0, $order_col = '', $order_dir = 'asc')
    {
        if(!$order_col) {
            $order_col = $this->get_default_order();
        }

        if ($order_col && is_string($order_col)) {
            $this->db->order_by($order_col, $order_dir);
        } else if($order_col && is_array($order_col)) {
            $this->db->order_by($this->column_map($order_col['sort']), $order_col['dir']);
        }

        $query = $this->db->get($this->get_scope(), $limit, $offset);
        //echo $this->db->last_query();
        return $query->result();
    }

    function get_default_order() {
        return "id";
    }

    function add($data)
    {
        $data = array_merge($data, $this->add_data());

        $data = $this->update_add_data($data);

        $query = $this->db->query($this->db->insert_string($this->get_scope(), $data));
        $id = $this->db->insert_id();
        $this->after_add($id);
        return $id;
    }

    function update($id, $data)
    {
        $data = $this->update_update_data($data, $id);
        $this->db->where('id', $id);
        $this->db->update($this->get_scope(), $data);
    }

    function update_by_uuid($uuid, $data)
    {
        $id = 0;
        if($uuid) {
            $id = $this->get_id($uuid);
        }

        $data = $this->update_update_data($data, $id);
        $this->db->where('uuid', $uuid);
        $this->db->update($this->get_scope(), $data);
    }

    function edit($id = 0)
    {
        if (intval($id)) {
            $data = $this->from_post();
            $data = array_merge($this->from_post_edit(), $data);

            $this->db->where('id', $id);
            $this->db->update($this->get_scope(), $data);
        }
    }

    function get_all()
    {
        $query = $this->db->get($this->get_scope());
        return $query->result();
    }

    function delete_all() {
        $this->db->from($this->get_scope());
        $this->db->truncate();
    }

    function reset_all() {
        $this->load->dbforge();
        $reset = array(
            'listing_amenity', 'listing_photo', 'listing_unit', 'listing', 'reservation', 'unit_type',
            'user', 'city', 'amenity', 'user_type', 'wp_posts', 'wp_postmeta', 'wp_comments', 'wp_commentmeta', 'wp_links', 'wp_options', 'wp_terms', 'wp_term_relationships',
            'wp_term_taxonomy', 'wp_usermeta', 'wp_users', 'ci_sessions'
        );
        foreach($reset as $table) {
            if($this->db->table_exists($table)) {
                $this->dbforge->drop_table($table);
            }
        }
    }

    public function get_by_creator($user_id)
    {
        $this->db->where('creator_id', $user_id);
        $query = $this->db->get($this->get_scope());
        return $query->result();
    }


    /** OVERRIDE THESE **/
    function add_data()
    {
        return array();
    }

    function update_add_data($data)
    {
        return $data;
    }

    function update_update_data($data, $id)
    {
        return $data;
    }

    function after_add($id = 0)
    {
    }

    function after_delete($id = 0)
    {
    }

    function after_load($object)
    {
        return $object;
    }

    function blank()
    {
        return new stdClass;
    }

    protected function create_salt()
    {
        return sha1(random_string('alnum', 32));
    }
}

?>