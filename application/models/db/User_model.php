<?php

/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2017-12-11
 * Time: 16:18
 */

include_once 'DB_model.php';

class User_model extends DB_model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('token');
        $this->load->helper('http');
        $this->load->model('db/app_model');
    }

    /**
     * 获取格式化后的微信登录URL，用$code码换取令牌
     * @param $wx_key
     * @param $wx_secret
     * @param $code
     * @return string
     */
    private function _get_wx_request_uri($wx_key, $wx_secret, $code)
    {
        return sprintf(
            $this->config->item('wx_login_url'),
            $wx_key, $wx_secret, $code);
    }

    /**
     * 获取格式化后的微信登录URL，用$code码换取令牌
     * @param $wx_key string 代理小程序APPID
     * @param $code string 小程序登录code
     * @return string
     */
    private function _get_wx_request_uri_for_third($wx_key, $code)
    {
        $this->load->library('weixin/weixinapi');

        $component_access_token = $this->weixinapi->get_access_token();
        $third_appkey  = $this->config->item('wx_third_app_id');

        return sprintf(
            $this->config->item('wx_login_url'),
            $wx_key, $code, $third_appkey, $component_access_token);
    }

    /**
     * 获取令牌（适用于非第三方开发者）
     * key:    token
     * value:  uid
     *         tb_pid
     *         app_key
     *         wx_key
     *         wx_secret
     *         dtk_key
     * @param $app_id
     * @param $code
     * @return mixed
     * @throws AppKeyException
     * @throws Exception
     * @throws WeChatException
     */
//    public function get_token($app_id, $code)
//    {
//        //1.检查app_key是否存在并返回wx_key,wx_secret,tb_pid
//        $app_data = $this->app_model->get_app($app_id);
//        if (!$app_data) {
//            throw new AppKeyException();
//        }
//        //2.携带code访问微信服务器获取opendid
////        $wx_login_uri = $this->_get_wx_request_uri($app_data['wx_key'], $app_data['wx_secret'], $code);
//        $wx_login_uri = $this->_get_wx_request_uri_for_third($app_data['wx_key'],  $code);
//        $result = curl_get($wx_login_uri);
//        $wxResult = json_decode($result, true);
//        //3.生成令牌，并且将uid,wx_xxx,tb_pid,dtk_key等存入缓存
//        if (empty($wxResult)) {
//            throw new Exception('获取session_key及openID时异常，微信内部错误');
//        } else {
//            $loginFail = array_key_exists('errcode', $wxResult);
//            if ($loginFail) {
//                throw new WeChatException(
//                    [
//                        'error_msg' => $wxResult['errmsg'],
//                        'error_code' => $wxResult['errcode']
//                    ]);
//            } else {
//                return $this->_grant_token($wxResult, $app_data);
//            }
//        }
//    }

    /**
     * 获取令牌（适用于第三方开发）
     * @param $app_id
     * @param $code
     * @return mixed
     * @throws AppKeyException
     * @throws Exception
     * @throws WeChatException
     */
    public function get_token($app_id, $code)
    {
        //1.检查app_key是否存在并返回wx_key,wx_secret,tb_pid
        $app_data = $this->app_model->get_app($app_id);
        if (!$app_data) {
            throw new AppKeyException();
        }
        //2.携带code访问微信服务器获取opendid
        $wx_login_uri = $this->_get_wx_request_uri_for_third($app_data['wx_key'],  $code);
        $result = curl_get($wx_login_uri);
        $wxResult = json_decode($result, true);
        //3.生成令牌，并且将uid,wx_xxx,tb_pid,dtk_key等存入缓存
        if (empty($wxResult)) {
            throw new Exception('获取session_key及openID时异常，微信内部错误');
        } else {
            $loginFail = array_key_exists('errcode', $wxResult);
            if ($loginFail) {
                throw new WeChatException(
                    [
                        'error_msg' => $wxResult['errmsg'],
                        'error_code' => $wxResult['errcode']
                    ]);
            } else {
                return $this->_grant_token($wxResult, $app_data);
            }
        }
    }

    private function _grant_token($wxResult, $app_data)
    {
        $open_id = $wxResult['openid'];
        $session_key = $wxResult['session_key'];
        // 数据库里看一下，这个openid是不是已经存在
        // 如果存在 则不处理，如果不存在那么新增一条user记录
        $uid = $this->get_user($open_id,$session_key, $app_data['id']);
        // 生成令牌，准备缓存数据，写入缓存
        $cached_value = $this->_prepare_cached_value($wxResult, $uid, $app_data);
        // 把令牌返回到客户端去
        // key:    token
        // value:  uid
//                tb_pid
//                app_key
//                wx_key
//                wx_secret
//                dtk_key
        $token = $this->_save_cache($cached_value);
        return $token;
    }

    /**
     * 查询用户，如果用户不存在则创建新用户
     * @param $open_id
     * @param $app_id
     * @return mixed 返回用户id
     * @throws Exception
     */
    public function get_user($open_id, $session_key,$app_id)
    {
        $user = $this->db->get_where(self::TABLE_USER, ['openid' => $open_id,'app_id' => $app_id])->row_array();
        //用户已经存在
        if ($user) {
            return $user['id'];
        } //新增用户
        else {
            $user = [
                'openid' => $open_id,
                'app_id' => $app_id,
                'session_key'=>$session_key,
                'create_time' => mktime(),
                'update_time' => mktime()
            ];
            $ok = $this->db->insert(self::TABLE_USER, $user);
            if (!$ok) {
                throw new Exception('新增用户失败');
            } else {
                return $this->db->insert_id();
            }
        }
    }

    private function _prepare_cached_value($wxResult, $uid, $app_data)
    {
        $cached_value = $wxResult;
        $cached_value['uid'] = $uid;
        $cached_value['tb_pid'] = $app_data['tb_pid'];
        $cached_value['app_key'] = $app_data['app_key'];
        $cached_value['wx_key'] = $app_data['wx_key'];
        $cached_value['wx_secret'] = $app_data['wx_secret'];
        $cached_value['dtk_key'] = $app_data['dtk_key'];
        $cached_value['chaozhi_token'] = $app_data['chaozhi_token'];
        $cached_value['chaozhi_session'] = $app_data['chaozhi_session'];

        // scope=16 代表App用户的权限数值
//        $cached_value['scope'] = ScopeEnum::User;
//        $cachedValue['scope'] = 15;

        // scope =32 代表CMS（管理员）用户的权限数值
//        $cachedValue['scope'] = 32;
        return $cached_value;
    }

    /**
     * @param $cached_value
     * @return mixed
     * @throws TokenException
     */
    private function _save_cache($cached_value)
    {
        $this->load->config('app');
        $key = $this->token->create();
        $value = json_encode($cached_value);
        $expire_in = $this->config->item('app_token_expire_in');

        $request = $this->cache->save($key, $value, $expire_in);
        if (!$request) {
            throw new TokenException([
                'msg' => '服务器缓存异常',
                'errorCode' => 10005
            ]);
        }
        return $key;
    }
}