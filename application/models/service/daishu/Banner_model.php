<?php

/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2017-12-30
 * Time: 10:30
 */

include_once 'DaiShu_model.php';

class Banner_model extends DaiShu_model
{
    // [
    //      banner{
    //          string url,  //banner图片地址，一般为alicdn    url = https://img.alicdn.com/imgextra/i3/1879660321/TB2sUu4kdzJ8KJjSspkXXbF7VXa_!!1879660321.jpg
    //          string ym,   //点击图片后跳转的小程序目标页面   ym  = /pages/share/share?x=dtk&ti=4963109&u=1&i=541019774055
    //      }
    // ]
    public function get_all()
    {
        if(isset($this->collection, $this->collection['banner'])){
            return array_values($this->collection['banner'])?:null;
        }
        return null;
    }
}