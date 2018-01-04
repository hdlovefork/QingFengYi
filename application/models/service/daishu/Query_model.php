<?php
/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2018-01-04
 * Time: 19:30
 */

include_once 'DaiShu_model.php';

class Query_model extends DaiShu_model
{
    public function get_quan_info($id){
        $url = $this->host_url . "wxapp/dataoke.php?r=p/d&id={$id}";
        $str = curl_get($url);
        return json_decode($str,TRUE);
    }

    function get_host_url()
    {
        return 'https://public.immmmmm.com';
    }


}