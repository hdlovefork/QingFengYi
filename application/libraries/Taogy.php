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
    protected $CI;
    public function __construct()
    {
        $this->CI = & get_instance();
        $this->CI->load->helper(['http','common']);
    }

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
        log_message('DEBUG', $res);
        return $res;
    }

    /**
     * 获取高佣优惠券地址
     * @param $tbid
     * @param $pid
     * @param $access_token
     * @return bool
     */
    public function get_url($tbid,$pid,$access_token){
        $pid=strtr($pid, '_', ' ');
        $n = sscanf($pid, 'mm %s %s %s', $user_id, $site_id, $adzone_id);
        if ($n === 3) {
            $url = "http://get.buting.cc/?type=gaoyong&action=zhuanlian&APIKey={$this->_appkey}&item_id={$tbid}&adzone_id={$adzone_id}&site_id={$site_id}&access_token={$access_token}";
            $res = curl_get($url);
            $arr = json_to_array($res);
            if(!$arr OR !$arr['result']['data']['coupon_click_url']) return FALSE;
            log_message('DEBUG', 'get_url--->'.$res);
            return $arr['result']['data']['coupon_click_url'];
        }
        return FALSE;
    }
}