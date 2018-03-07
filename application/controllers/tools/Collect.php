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

    public function format()
    {
        $raw = '';
        $raw = preg_replace_callback('/(\d+)px/', function ($arr) {
            return ($arr[1] * 2) . 'rpx';
        }, $raw);
        $raw = strtr($raw, ['wx-'=>'']);
        $raw = strtr($raw, ['body'=>'page']);
        log_message('DEBUG', $raw);
    }

    public function fetch()
    {
//        $this->load->helper('phpquery');
//        phpQuery::newDocumentFileHTML('http://www.dataoke.com/item?id=5177111');
//        var_dump(pq('#jp_container_1')->attr('data-video'));
        $this->load->helper('url');
        redirect('https://oauth.taobao.com/authorize?response_type=code&client_id=21181716&redirect_uri=https://wx.tztfanli.com/tools/collect/callback');

    }

    public function callback()
    {
        $code = $_GET['code'];
        $out['post'] = $_POST;
        $out['raw'] = file_get_contents('php://input');
        var_dump($out);
    }

    public function get_token()
    {
        $code = 'ATVEX5zwPAL0HWtXnYqAsZ4L8312322';
        $url = "http://tool.chaozhi.hk/api/authorize.php?code={$code}";
        $res = curl_get($url);
        var_dump($res);
    }
}