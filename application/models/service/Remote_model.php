<?php
/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2017-12-30
 * Time: 10:32
 */


abstract class Remote_model extends CI_Model
{
    protected $base_url = '';

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('http');
        $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file', 'key_prefix' => 'wx_'));
        $base_url = $this->get_base_url();
        $this->base_url = $base_url ?: rtrim($this->config->item('base_url'),'/') . '/';
    }

    abstract function get_base_url();

    public function cache_get($key){
        return $this->cache->get($key);
    }

    public function cache_set($key,$value,$expire=3600){
        $this->cache->save($key,$value,$expire);
    }

    public function get_juan_desc($tb_id){
        APPPATH . 'libraries/daotaoke/autoload.php';
    }
}