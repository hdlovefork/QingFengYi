<?php
/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2017-12-30
 * Time: 10:32
 */

abstract class Remote_model extends CI_Model
{
    /**
     * 带后缀/的主机地址
     * @var string
     */
    protected $host_url = '';

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('http');
        $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file', 'key_prefix' => 'wx_'));
        $base_url = $this->get_host_url();
        $this->host_url = $base_url ? rtrim($base_url,'/'). '/' : rtrim($this->config->item('base_url'),'/') . '/';
    }

    abstract function get_host_url();
}