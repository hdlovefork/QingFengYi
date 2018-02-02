<?php
/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2018-01-31
 * Time: 20:19
 */

/**
 * 淘宝高佣授权类
 * Class Taogy
 * @package libraries
 */
class Taogy
{
    private $_appkey = 'KqLrG5A5374A1nNmZLvhT';
//    private $_appkey = 'KoRjZ5A53748DtGiZLvhT';

    /**
     * 获得高佣授权地址
     * @return bool
     */
    public function get_auth_url(){
        $url ="http://get.buting.cc/?type=gaoyong&action=login&APIKey={$this->_appkey}";
        $res = curl_get($url);
        $res = convertToUTF8($res);
        $res = substr($res, stripos($res, 'https'));
        if(stripos($res, 'http')===FALSE) return FALSE;
        return $res;
    }
}