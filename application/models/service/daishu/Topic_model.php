<?php
/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2018-01-08
 * Time: 20:16
 */
include_once 'DaiShu_model.php';

class Topic_model extends DaiShu_model
{

//array (
//  0 =>
//  array (
//    'pages' => '/pages/list/huodong/huodong?get=ershijiu&title=30元优质好货,超值商品推荐',
//    'src' => 'http://public.immmmmm.com/images/topic/i19.jpg',
//  ),
//)
    public function get_all()
    {
        if (isset($this->collection, $this->collection['topic'])) {
            return array_values($this->collection['topic']) ?: null;
        }
        return null;
    }
}