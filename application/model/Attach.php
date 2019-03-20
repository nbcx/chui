<?php
/**
 *
 * User: Collin
 * QQ: 1169986
 * Date: 2018/5/15 下午4:02
 */

namespace model;


use util\Auth;
use nb\Model;
use util\Uploader;

class Attach extends Model {

    public static function archive($uid,$oid,$parent=0,$category='topic') {
        $data = [
            'parent'=>$parent,
            'oid'=>$oid,
            'category'=>$category
        ];
        return self::update($data,'uid=? and oid=0',$uid);
    }

    /**
     * 往数据库中插入数据
     */
    public static function add($data) {
        //fileSize
        //fullName
        //orgfilename
        //ext
        //type
        //ori
        $pre = [
            'uid' => Auth::init()->id,
            'ct'=>time()
        ];
        $data = array_merge($pre,$data);
        return self::insert($data);
    }


    /**
     * 获取指定对象的所属附件，包括当前登录用户未归档的附件
     * @param $oid
     * @param $category
     * @return $this
     */
    public static function usable($uid,$oid,$category) {
        $db = self::dao();
        if($oid) {
            $db->where('((category=? and oid=?) or oid=0) and uid=?', [$category,$oid,$uid]);
        }
        else {
            $db->where('oid=0 and uid=?', [$uid]);
        }

        return $db->fetchs();
    }

    //文件所属对象的访问地址
    public function _objectUrl() {

    }

}