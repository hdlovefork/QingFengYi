<?php

namespace libraries\huoxing;

use libraries\huoxing\request\Request;

/**
 * 火星来客淘宝联盟超级搜索查询入口
 * @haguo
 */
class Client
{
    /**
     * 请求地址
     * @var string
     */
    protected $reqURI = 'http://www.mapprouter.com';

    /**
     * appkey
     * @var string
     */
    protected $appkey = 'test_key_2017';

    /**
     * appsecret
     * @var string
     */
    protected $appsecret = 'test_secret_2017';

    /**
     * 请求超时时间
     * @var integer
     */
    protected $timeout = 5;

    /**
     * 响应格式。可选值：xml，json。
     * 目前仅支持json
     *
     * @var string
     */
    protected $format = 'json';

    /**
     * 签名的摘要算法，可选值为：hmac，md5。
     * 目前仅支持md5
     *
     * @var string
     */
    protected $sign_method = 'md5';

    /**
     * 初始化
     */
    public function __construct($appkey = '', $appsecret = '')
    {
        if (!empty($appkey))
            $this->appkey = $appkey;

        if (!empty($appsecret))
            $this->appsecret = $appsecret;
    }

    /**
     * 设置appkey
     * @param string $value appkey
     */
    public function setAppkey($value)
    {
        $this->appkey = $value;
        return $this;
    }

    /**
     * 设置appsecret
     * @param [type] $value [description]
     */
    public function setAppsecret($value)
    {
        $this->appsecret = $value;
        return $this;
    }

    /**
     * 设置超时时间
     * @param [type] $value [description]
     */
    public function setTimeout($value)
    {
        $this->timeout = $value;
        return $this;
    }

    /**
     * 请求
     * @param  Request $request [description]
     * @param bool $returnArray 是否转JSON源数据转成ARRAY
     * @return bool|string|array        [返回数组或string或布尔值]
     */
    public function execute($request, $returnArray = TRUE)
    {
        // 获取参数
        $params = array_merge($request->getParams());

        // 生成签名
        //$params['sign'] = $this->generateSign($params);
        $url = $this->reqURI;
        $url .= $request->getMethod();
        $submit = strtolower($request->getSubmitMethod());
        if (empty($submit) || $submit === 'get') {
            // 读取数据
            $json = $this->curl($url, $params);
        } else {
            $json = $this->curl_post($url, $params, $request->getHeader(),$request->getCookies());
        }


        if (!$json)
            return FALSE;

        // 转换成json
        if ($returnArray) {
            $rs = json_decode($json, true);
            if (!$rs)
                return FALSE;
            else
                return $rs;
        }
        return $json;

    }

    /**
     * 公共参数
     * @return [type] [description]
     */
    protected function publicParams()
    {
        return array(
            'app_key' => $this->appkey,
            'timestamp' => date('Y-m-d H:i:s'),
            'format' => $this->format,
            'v' => '2.0',
            'sign_method' => $this->sign_method,
        );
    }

    /**
     * 生成签名
     * @param  array $params 需要签名的参数
     * @return string
     */
    protected function generateSign($params)
    {
        ksort($params);

        $tmps = array();
        foreach ($params as $k => $v) {
            $tmps[] = $k . $v;
        }

        $string = implode('', $tmps) . $this->appsecret;

        return strtoupper(md5($string));
    }

    /**
     * curl get方式请求
     * @param  string $url 请求地址
     * @param  array $params
     * @return string|false
     */

    function curl($url, $params)
    {
        $ch = curl_init();
        if (is_array($params) && 0 < count($params)) {
            $BodyString = "";
            foreach ($params as $k => $v) {
                $BodyString .= "$k=" . urlencode($v) . "&";
            }
            unset($k, $v);
            curl_setopt($ch, CURLOPT_URL, $url . '?' . $BodyString);
        }
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $result = curl_exec($ch);
        //$data = json_decode ( $result );
        curl_close($ch);
        return $result;
    }

    /**
     * @param string $url post请求地址
     * @param array $params
     * @param array $header
     * @param array $cookies
     * @return mixed
     */
    function curl_post($url, array $params = array(), $header = array(),$cookies=array())
    {
        $header_tmp = array_change_key_case($header);
        switch (strtolower($header_tmp['content-type'])) {
            case 'application/json':
                $data_string = json_encode($params);
                break;
            default:
                $data_string = http_build_query($params);
                break;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        $header_new = [];
        if (empty($header)) {
            $header_new[] = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8';
        } else {
            foreach ($header as $k => $v) {
                $header_new[] = "$k: $v";
            }
        }
        curl_setopt(
            $ch, CURLOPT_HTTPHEADER, $header_new
        );
        $set_cookies='';
        foreach ($cookies as $k => $v) {
            $set_cookies.="$k=$v; ";
        }
        if ($set_cookies)
            curl_setopt($ch, CURLOPT_COOKIE, $set_cookies);
        $data = curl_exec($ch);
        curl_close($ch);
        return ($data);

    }
}