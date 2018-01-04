<?php
/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2017-12-28
 * Time: 19:48
 */

/**
 * 页面基类
 * Class Page_model
 */
class Page_model extends CI_Model
{
    protected $service = 'daishu';

    public function to_array($json_str)
    {
        if(is_array($json_str)) return $json_str;
        return !empty($json_str) ? json_decode($json_str, TRUE) : [];
    }
}