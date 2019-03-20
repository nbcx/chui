<?php
namespace model;

use nb\Model;

class Group extends Model {

    protected static function __config() {
        return ['group g','id'];
    }

    public static function all() {
        return self::fetchs();
        //return $this->fets('*','gid asc');
        /*
        $query = $this->db->order_by('gid')->get('user_groups');
        if ($query->num_rows > 0) {
            return $query->result_array();
        }
        else {
            return false;
        }
        */
    }

}
