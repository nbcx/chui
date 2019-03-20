<?php
/**
 *
 * User: Collin
 * QQ: 1169986
 * Date: 2018/5/4 下午12:26
 */

namespace util;

use nb\Cache;

class Model extends \nb\Model {

    /**
     * @param $id
     * @param string $fields
     * @return $this
     */
    public static function findId($id, $object = true) {
        $class = get_called_class();
        return Cache::getx($class.':id:'.$id,function () use ($id,$object){
            return static::dao($object)->findId($id);
        });
    }


    public static function deleteId($id) {
        $class = get_called_class();
        Cache::rm($class.':id:'.$id);
        return static::dao()->deleteId($id);
    }

    /**
     * @param $id
     * @param $data
     * @return int
     */
    public static function updateId($id, $data, $params=[], $filter=false) {
        $row = static::dao()->updateId($id, $data, $params, $filter);
        if($row) {
            $class = get_called_class();
            Cache::rm($class.':id:'.$id);
        }
        return $row;
    }

}