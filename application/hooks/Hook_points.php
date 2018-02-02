<?php
/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2018-01-28
 * Time: 21:05
 */

class Hook_points
{
    public function refresh_token($params){
       return $params['a'] + $params['b'];
    }
}