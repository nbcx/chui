<?php
/*
 * This file is part of the NB Framework package.
 *
 * Copyright (c) 2018 https://nb.cx All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace controller;

use bin\Config;
use model\Attach;
use nb\Request;
use util\Admin;
use util\Uploader;

/**
 * Upload
 *
 * @package controller
 * @link https://nb.cx
 * @author: collin <collin@nb.cx>
 * @date: 2018/8/10
 */
class Upload  {

    public function index() {
        \nb\Hook::pos('controller\admin\Upload')->index();

        $file = Request::driver()->files['file'];

        $upload = new Uploader(Config::$o->upload);

        $info = [];
        if($upload->upload($file)) {
            $info = [
                'title' => $upload->original,
                'ext'   => $upload->ext,
                'mime'  => $upload->mime,
                'size'  => $upload->size,
                'path'  => $upload->path,
                'width' => $upload->width,
                'height' => $upload->height
            ];
            //fileSize
            //fullName
            //orgfilename
            //ext
            //type
            //ori
            Attach::add($info);
        }

        \nb\Hook::pos('controller\admin\Upload')->trigger($signal)->info($upload);

        $info['url'] = $upload->url;

        if(!$signal) {
            echo json_encode($info);
        }
    }

}