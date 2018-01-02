<?php

/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2017-12-11
 * Time: 15:50
 */
class Token
{
    protected $CI;

    public function __construct()
    {
        $this->CI = & get_instance();
        $this->CI->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file', 'key_prefix' => 'wx_'));

    }

    /**
     * 生成令牌
     * @return string
     */
    public function create(){
        $this->CI->load->helper('string');
        $token = random_string('md5');
        $token = $token . $this->CI->config->item('wx_token_salt');
        return md5($token);
    }

    /**
     * 获取当前用户令牌对应的KEY值（实际上是将TOKEN作为键从缓存中取出$key值）
     * @param $key
     * @return mixed
     * @throws Exception
     * @throws TokenException
     */
    public function get_data($key){
        $token = $this->CI->input->post('token');
        $vars = $this->CI->cache->get($token);
        if(!$vars){
            throw new TokenException();
        }else{
            if (!is_array($vars))
            {
                $vars = json_decode($vars, true);
            }
            if (array_key_exists($key, $vars))
            {
                return $vars[$key];
            }
            else
            {
                throw new Exception('尝试获取的Token变量并不存在');
            }
        }
    }

    /**
     * 获取令牌数据缓存区中的uid值
     * @return mixed
     */
    public function get_uid()
    {
        $uid = $this->get_data('uid');
        return $uid;
    }

    /**
     * 检查指定令牌是否合法
     * @param $token
     * @return mixed
     */
    public function check($token){
        $cache = $this->CI->cache->get($token);
        return $cache;
    }


}