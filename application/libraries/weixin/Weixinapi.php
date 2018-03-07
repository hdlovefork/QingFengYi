<?php
/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2018-01-17
 * Time: 19:53
 */

class Weixinapi
{
    protected $CI;

    public function __construct()
    {
        $this->CI = get_instance();
        $this->CI->load->driver('cache', ['adapter' => 'apc', 'backup' => 'file']);
        $this->CI->load->helper('http');
        $this->CI->load->config('wx');
    }

    /**
     * 获取令牌
     * @param null $third_appid
     * @param null $third_secret
     * @param null $ticket
     * @return mixed|bool
     */
    public function get_access_token($third_appid = NULL, $third_secret = NULL, $ticket = NULL)
    {
        //1：缓存中是否存在token？
        $token = $this->CI->cache->get('wx_access_token');
        if ($token) return $token;
        if (!isset($third_appid, $third_secret)) {
            //从配置文件中读取设置
            $third_appid = $this->get_third_appid();
            $third_secret = $this->get_third_secret();
        }
        if (!isset($third_appid, $third_secret)) return FALSE;
        //2：缓存中是否存在ticket
        if (!isset($ticket)) {
            $ticket = $this->CI->cache->get('wx_ticket');
            if (!$ticket) return FALSE;
        }
        //3：访问微信服务器获取token
        $params['component_appid'] = $third_appid;
        $params['component_appsecret'] = $third_secret;
        $params['component_verify_ticket'] = $ticket;
        $url = 'https://api.weixin.qq.com/cgi-bin/component/api_component_token';
        $resStr = curl_post($url, $params);
        $res = $this->json_to_array($resStr);
        if (!$res || !$res['component_access_token']) return FALSE;
        //4：保存到缓存
        $this->CI->cache->save('wx_access_token', $res['component_access_token'], 7000);
        return $res['component_access_token'];
    }

    /**
     * 获取授权令牌和刷新令牌
     * @param null $auth_code 授权码，用户授权后通过回调地址中的$_GET参数获取
     * @param null $third_appid 第三方平台app_id
     * @return bool
     */
    public function get_auth_token($auth_code = NULL, $third_appid = NULL)
    {
        if (!$third_appid) {
            //从配置中读出wx_third_app_id
            $third_appid = $this->get_third_appid();
            if (!$third_appid) return FALSE;
        }
        if (!$auth_code) {
            //从缓存中读出wx_auth_token
            $auth_code = $this->CI->cache->get('wx_auth_code');
            if (!$auth_code) return FALSE;
        }
        //1：获取访问令牌
        $access_token = $this->get_access_token();
        if (!$access_token) return FALSE;
        //2：
        $url = "https://api.weixin.qq.com/cgi-bin/component/api_query_auth?component_access_token={$access_token}";
        $post['component_appid'] = $third_appid;
        $post['authorization_code'] = $auth_code;
        $res = curl_post($url, $post);
        log_message('DEBUG', "getAuthToken返回数据：\n" . var_export($res, TRUE));
        $res = $this->json_to_array($res);
        log_message('DEBUG', "转为数组后的数据：\n" . var_export($res, TRUE));
        if (!$res['authorization_info']) {
            return FALSE;
        }
        return $res;
    }

    /**
     * 获取预授权码
     * @param null $appid
     * @return null
     */
    private function get_preauth_code($appid = NULL)
    {
        //1：缓存中是否存在auth_code？
        $auth_code = $this->CI->cache->get('wx_pre_auth_code');
        if ($auth_code) return $auth_code;
        //2：获取令牌
        $token = $this->get_access_token();
        if (!$token) return NULL;
        $url = "https://api.weixin.qq.com/cgi-bin/component/api_create_preauthcode?component_access_token={$token}";
        if (!isset($appid)) {
            //读取配置
            $appid = $this->get_third_appid();
        }
        if (!$appid) return NULL;
        //3：通过令牌获取预授权码
        $params['component_appid'] = $appid;
        $resStr = curl_post($url, $params);
        $res = $this->json_to_array($resStr);
        if (!$res || !$res['pre_auth_code']) return NULL;
        //4：保存到缓存中
        $this->CI->cache->save('wx_pre_auth_code', $res['pre_auth_code'], 500);
        return $res['pre_auth_code'];
    }

    /**
     * 获取授权地址
     * @param $redirect_url
     * @param null $third_appid
     * @return bool|string
     */
    public function get_auth_url($redirect_url, $third_appid = NULL)
    {
        $wx_pre_auth_code = $this->get_preauth_code();
        if (!$wx_pre_auth_code)
            return FALSE;
        if (!isset($third_appid))
            $wx_third_app_id = $this->get_third_appid();
        if (!isset($wx_third_app_id))
            return FALSE;
        $url = "https://mp.weixin.qq.com/cgi-bin/componentloginpage?component_appid={$wx_third_app_id}&pre_auth_code={$wx_pre_auth_code}&redirect_uri={$redirect_url}";
        return $url;
    }

    public function json_to_array($json)
    {
        $res = json_decode($json, TRUE);
        if ($res === NULL) {
            //强制返回NULL，用于404状态码
            $json = self::format_ErrorJson($json);
            $res = json_decode($json, TRUE);
            if ($res === FALSE)
                return NULL;
        }
        return $res;
    }

    public function format_ErrorJson($checkLogin)
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

    /**
     * 刷新第三方访问客户小程序API的令牌
     * @param $third_appid
     * @param $auth_appid
     * @param $refresh_token
     * @return bool|array
     */
    public function refresh_token($auth_appid, $refresh_token, $third_appid = NULL)
    {
        $access_token = $this->get_access_token();
        if (!$access_token)
            return FALSE;
        if (!isset($third_appid))
            $third_appid = $this->get_third_appid();
        if (!$third_appid) return FALSE;
        // 请求微信服务器刷新令牌
        $url = "https://api.weixin.qq.com/cgi-bin/component/api_authorizer_token?component_access_token={$access_token}";
        $params['component_appid'] = $third_appid;
        $params['authorizer_appid'] = $auth_appid;
        $params['authorizer_refresh_token'] = $refresh_token;

        $res = curl_post($url, $params);
        $auth_info = $this->json_to_array($res);
        if (!$auth_appid) return FALSE;
        return $auth_info;
    }

    /**
     * 获取已授权小程序的信息
     * @param $third_appid string 第三方服务APPID
     * @param $auth_appid string 小程序APPID
     * @return bool|mixed|null
     */
    public function get_auth_info($auth_appid, $third_appid = NULL)
    {
        $access_token = $this->get_access_token();
        if (!$access_token) return FALSE;
        $url = "https://api.weixin.qq.com/cgi-bin/component/api_get_authorizer_info?component_access_token={$access_token}";
        if (!isset($third_appid))
            $third_appid = $this->get_third_appid();
        if (!$third_appid)
            return FALSE;

        $params['component_appid'] = $third_appid;
        $params['authorizer_appid'] = $auth_appid;

        $json_str = curl_post($url, $params);
        $res = $this->json_to_array($json_str);
        return $res;
    }

    /**
     * 为代理小程序设置第三方服务器域名
     * @param $authorizer_access_token
     * @param string $action 动作包括:add添加, delete删除, set覆盖, get获取。当参数是get时不需要填四个域名字段。
     * @return bool 成功返回TRUE，否则返回FALSE
     */
    public function set_domain($authorizer_access_token, $action = 'set')
    {
        if (empty($authorizer_access_token))
            return FALSE;
        $url = "https://api.weixin.qq.com/wxa/modify_domain?access_token={$authorizer_access_token}";
        $domains = ['https://wx.tztfanli.com', 'https://hws.m.taobao.com'];
        $params['action'] = $action;
        $params['requestdomain'] = $domains;
        $params['wsrequestdomain'] = $domains;
        $params['uploaddomain'] = $domains;
        $params['downloaddomain'] = $domains;
        $res_json = curl_post($url, $params);
        $res = $this->json_to_array($res_json);
        if (!$res OR $res['errcode'] != 0) {
            log_message('debug', 'set_domain--->' . PHP_EOL . var_export($res, TRUE));
            return FALSE;
        }
        return TRUE;
    }

    /**
     * 为授权的小程序帐号上传小程序代码
     * @param $access_token string 请使用第三方平台获取到的该小程序授权的authorizer_access_token
     * @param $template_id string 代码库中的代码模版ID
     * @param $ext_json string 第三方自定义的配置
     * @param $user_version string 代码版本号，开发者可自定义
     * @param $user_desc string 代码描述，开发者可自定义
     * @link https://open.weixin.qq.com/cgi-bin/showdocument?action=dir_list&t=resource/res_list&verify=1&id=open1489140610_Uavc4&token=&lang=
     * @return bool
     */
    public function commit_code($access_token, $template_id, $ext_json, $user_version, $user_desc)
    {
        $url = "https://api.weixin.qq.com/wxa/commit?access_token={$access_token}";
        if (is_array($ext_json)) {
            $ext_json = json_encode($ext_json);
        }
        $params['template_id'] = $template_id;
        $params['ext_json'] = $ext_json;
        $params['user_version'] = $user_version;
        $params['user_desc'] = $user_desc;
        $res = curl_post($url, $params);
        if (!$res OR $res['errcode'] != 0) {
            log_message('DEBUG', 'Weixinapi->commit_code()--->' . PHP_EOL . $res);
            return FALSE;
        }
        return TRUE;
    }


    /**
     * 获取体验小程序的体验二维码
     * @param $access_token string 请使用第三方平台获取到的该小程序授权的authorizer_access_token
     * @return string 返回二维码下载地址
     */
    public function get_qrcode_url($access_token){
        return "https://api.weixin.qq.com/wxa/get_qrcode?access_token={$access_token}";
    }

    /**
     * 获取第三方APPID
     * @return string
     */
    protected function get_third_appid()
    {
        //从配置文件中读取设置
        return $this->CI->config->item('wx_third_app_id');
    }

    /**
     * 获取第三方SECRET
     * @return mixed
     */
    protected function get_third_secret()
    {
        return $this->CI->config->item('wx_third_app_secret');
    }
}