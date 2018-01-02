<?php

/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2017-12-17
 * Time: 16:29
 */

/**
 * 用于存储CMS端用户登录数据和验证用户权限
 * Class Auth
 */
class Auth
{
    //$_SESSION中保存用户数据所使用的KEY
    protected $session_key = 'app';

    //保存用户名所使用的KEY
    protected $username_key = 'email';

    private $CI;

    public function __construct($params = [])
    {
        $this->CI = &get_instance();
        $this->CI->load->library('session');
        $this->CI->load->config('auth');
        //构造函数传值优先
        $this->session_key = $params['auth_session_key'] ?: $this->CI->config->item('auth_session_key');
        $this->username_key = $params['auth_username_key'] ?: $this->CI->config->item('auth_username_key');
    }

    /**
     * 验证用户是否登录，失败返回NULL值
     * @return mixed
     */
    public function check_auth()
    {
        return $this->CI->session->userdata($this->session_key);
    }

    /**
     * 标记用户为登录状态，将$value存入session当中
     * @param $value
     */
    public function login($value)
    {
        $this->CI->session->set_userdata($this->session_key, $value);
    }

    /**
     * 标记用户为退出状态
     * @param string $redirect 退出后跳转地址
     */
    public function logout($redirect = '')
    {
        $this->CI->session->unset_userdata($this->session_key);
        if ($redirect) {
            $this->CI->load->helper('url');
            redirect($redirect);
        }
    }

    /**
     * 获取登录用户名
     * @return mixed
     */
    public function username()
    {
        return $this->get($this->username_key);
    }

    /**
     * 获取当前登录的用户数据
     */
    public function user()
    {
        return $this->CI->session->userdata($this->session_key);
    }

    /**
     * 获取当前登录用户字段信息
     * @param $key
     * @return null
     */
    public function get($key)
    {
        $data = $this->CI->session->userdata($this->session_key);
        if (isset($data[$key]))
            return $data[$key];
        return NULL;
    }

    function __get($key)
    {
        return $this->get($key);
    }
}