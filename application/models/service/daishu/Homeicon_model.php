<?php
/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2018-01-08
 * Time: 20:16
 */
include_once 'DaiShu_model.php';

class Homeicon_model extends DaiShu_model
{

//array (
//  0 =>
//  array (
//    'src' => 'https://immmmmm.com/images/icon1.png',
//    'txt' => '聚划算',
//    'ym' => '/pages/list/huodong/huodong?get=calculate&title=【聚划算】无所不能聚',
//  ),
//)
    public function get_all()
    {
        if(isset($this->collection, $this->collection['homeicon'])){
            return array_values($this->collection['homeicon'][0])?:null;
        }
        return null;
    }
}