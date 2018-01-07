<?php

/**
 * 袋鼠优惠券API，从袋鼠服务器获取数据
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2017-12-30
 * Time: 10:35
 */
include_once APPPATH . 'models/service/Remote_model.php';

class DaiShu_model extends Remote_model
{
    /**
     * 首页所有数据
     * @var null
     */
    protected $collection = NULL;

    public function __construct()
    {
        parent::__construct();
        $this->init();
    }

    public function init()
    {
        $this->home_init();
    }

    /**
     * 首页初始化
     */
    public function home_init()
    {
        $url = 'https://public2.immmmmm.com/ali_1111_daishuyouhuiquan/collocation2.php';
        $res = curl_get($url, $http_code);
        if ($http_code === 200 && $res) {
            $this->collection = json_decode($res,TRUE);
            log_message('DEBUG', "袋鼠首页数据\n" . var_export($this->collection,TRUE));
        } else {
            log_message('ERROR', '袋鼠首页初始化失败');
        }
    }
}