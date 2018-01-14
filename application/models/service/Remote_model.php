<?php
/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2017-12-30
 * Time: 10:32
 */

abstract class Remote_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('http');
        $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file', 'key_prefix' => 'wx_'));
    }

    function format_ErrorJson($checkLogin)
    {
        for ($i = 0; $i <= 31; ++$i) {
            $checkLogin = str_replace(chr($i), '', $checkLogin);
        }
        $checkLogin = str_replace(chr(127), '', $checkLogin);

// This is the most common part
// Some file begins with 'efbbbf' to mark the beginning of the file. (binary level)
// here we detect it and we remove it, basically it's the first 3 characters
        if (0 === strpos(bin2hex($checkLogin), 'efbbbf')) {
            $checkLogin = substr($checkLogin, 3);
        }
        return $checkLogin;
    }

    public function json_to_array($json)
    {
        $res = json_decode($json, TRUE);
        if ($res === NULL) {
            //强制返回NULL，用于404状态码
            $json = $this->format_ErrorJson($json);
            $res = json_decode($json, TRUE);
            if ($res === FALSE)
                return NULL;
        }
        return $res;
    }

    public function add_protocol($images)
    {
        $res = [];
        foreach ($images as $img) {
            if (strpos($img, 'http') === 0) {
                $res[] = $img;
            } else {
                $res[] = 'https:' . $img;
            }
        }
        return $res;
    }

    /**
     * 获得二合一链接
     * @param $data ['activity','pid','tbid']
     * @return string http://uland.taobao.com/coupon/edetail?activityId=bc8d0f083018474f9b7876c3691acf88&pid=mm_40490058_11180430_56318467&itemId=523753473100
     */
    public function get_two_one($data)
    {
        $url = "https://uland.taobao.com/coupon/edetail?activityId={$data['activity']}&pid={$data['pid']}&itemId={$data['tbid']}";
        return $url;
    }
}