<?php

namespace libraries\huoxing;
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
     * @param  [type] $request [description]
     * @param bool $returnArray 是否转JSON源数据转成ARRAY
     * @return bool|string|array        [返回数组或string或布尔值]
     */
    public function execute($request, $returnArray=TRUE)
    {
        // 获取参数
        $params = array_merge($request->getParams());

        // 生成签名
        //$params['sign'] = $this->generateSign($params);
        $url = $this->reqURI;
        $url .= $request->getMethod();
        // 读取数据
        $json = $this->curl($url, $params);

        if (!$json)
            return FALSE;

        // 转换成json
        if($returnArray){
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
}