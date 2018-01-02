<?php

/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2017-12-20
 * Time: 11:02
 */

/**
 * 采集商品类
 * Class Collect
 */
class Collect extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->config('app');
        $this->load->model('collect/goods_model');
    }

    /**
     * 采集商品
     * @param int $page_count
     */
    public function goods($page_count = 2)
    {
        $this->clean();
        for ($page = 1; $page <= $page_count; $page++) {
            log_message('debug', "------------正在获取第{$page}页------------");
            $req_url = sprintf($this->config->item('app_dtk_total_goods'), $this->config->item('app_dtk_key'), $page);
            $this->goods_model->collect($req_url);
            usleep(0.1 * 1000 * 1000);
        }
    }

    /**
     * 清理过期商品
     */
    public function clean()
    {
        $this->goods_model->clean_expire(0, 0, 2, 0, 0, 0);
    }
}