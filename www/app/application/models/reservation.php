<?php
class Reservation extends MY_Model
{

    function get_scope()
    {
        return "reservation";
    }

    protected static $fields = array(
    );

    function add_data()
    {
        $data = array(
            'created' => timestamp_to_mysqldatetime(now())
        );
        return $data;
    }
}
?>