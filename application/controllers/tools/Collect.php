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
        $raw = '.qiang{ text-align: center; display: block; background: #ff5777; color: #fff; padding: 4px; margin-top: 2px; border-radius: 2px; }
.listshop .title { padding-bottom:2px; }
body{ font-size: 14px; font-family: \'微软雅黑\'; color: #666666; }
.footj{ text-align: center }
.scroll-view_H .ha{ color: #ff5777; }
.section_gap{ background: #fff; }
.scroll-view_H{ background: #fff; height: 65px; white-space: nowrap; }
.scroll-view_H wx-image{ width: 40px; }
.scroll-view_H wx-text{ margin: 0 auto; text-align: center; font-size: 12px; display:table; }
.scroll-view_H wx-view{ margin: 0 auto; text-align: center; padding: 0 13px; display: inline-block; }
.tishi{ background: #fff; color: #999; font-size: 10px; text-align: center; margin: 0 auto; }
.fenge{ margin-top: 6px; border-top: 1px solid #eee; }
.paixu{ text-align: center; font-size: 13px; }
.paixu wx-view{ height: 31px; line-height: 31px; width: 25%; background: #fff; float: left; }
.paixu wx-view wx-text{ padding:0px 8px 3px 8px; }
.paixu .ha{ color: #f10000; border-bottom: 2px solid #f10000; }
body{ background: #eee; font-size: 11px; font-family: "微软雅黑"; }
body{ -webkit-animation: 1s opacity2; animation: 1s opacity2; }
@-webkit-keyframes opacity2{ 0%{opacity:0}
100%{opacity:1;}
}@keyframes opacity2{ 0%{opacity:0}
100%{opacity:1;}
}.footj{ text-align: center }
.listshop{ position:relative; background: #fff; float: left; width: 50%; box-sizing: border-box; border: solid 1px #eee; padding: 2px }
.listshop wx-image{ width: 100%; height: 172px; }
.quanjia{ position:absolute; bottom:46px; right: 2px; background: #ff6a00; padding: 3px 6px; border-top-left-radius: 8px; font-size: 9px; color: #fff; }
.listshop .textlist{ margin-top: 3px; padding: 0 3px; vertical-align:bottom; }
.listshop .title{ color: #444; padding-top: 2px; height:18px; display:block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.listshop .quanhou{ color: #f10000; font-size: 14px; }
.listshop .quanhoutext{ color: #cecece; font-size: 9px; }
.zhibo{ padding: 10px 14px; background: #fff; }
.bang{ width: 16px; height: 16px; float: left; padding-right: 4px; }
.paixu{ text-align: center; }
.paixu wx-view{ height: 31px; line-height: 31px; width: 25%; background: #fff; float: left; }
.paixu wx-view wx-text{ padding-bottom: 5px; }
.paixu .ha{ color: #ff5777; border-bottom: 2px solid #ff5777; }
.video_icon{ width: 50px; height: 50px; border: 2px solid white; border-radius: 100%; position: absolute; left: 50%; top: 38%; margin: -27px; background: rgba(0,0,0,.5); }
.video_icon wx-text{ content: \'\'; width: 0; height: 0; position: absolute; top: 13px; left: 18px; border-top: 12px solid transparent; border-bottom: 12px solid transparent; border-left: 16px solid white; }
.listshop .title { padding-bottom:2px; }
.listshop{ position:relative; }
.biaoqiandiv{ position:absolute; bottom: 90px; right: 10px; }
.biaoqiandiv wx-image{ display:inherit; width: 20px; height: 20px }
.biaoqian{ background: #fff; padding: 2px 6px; color: #EF422D; font-size: 10px; border-radius: 4px; -moz-box-shadow:1px 2px 4px #B8B8B8; -webkit-box-shadow:1px 2px 4px #B8B8B8; box-shadow:1px 2px 4px #B8B8B8; }
.title{ margin-top: 2px; }
.title wx-text{ border: 1px solid #E34310; color: #E34310; font-size: 10px; padding: 1px 3px; margin-right: 2px; }
.quanfooter{ padding: 2px 4px; margin-top: 4px; }
.quanedu{ float: left; height: 20px; font-size: 12px; line-height: 20px; color: #fff; text-align: center; padding: 0 5px; background-color: #FF5F32; border-radius: 2px; }
.quanhoujia{ float: right; font-size: 12px; color: #EF422D; line-height: 20px; vertical-align: bottom; }
.quanjias{ font-size: 16px; font-weight: bold }
.widget-goTop { position: fixed; bottom: 85px; right: 5px; overflow: hidden; z-index: 500; }
.widget-goTop .gotop-wrap { display:-webkit-flex; display:flex; -webkit-flex-direction:column; flex-direction:column; -webkit-align-items:center; align-items:center; -webkit-justify-content:center; justify-content:center; }
.title{ margin-top: 2px; }
.title wx-text{ border: 1px solid #E34310; color: #E34310; font-size: 10px; padding: 1px 3px; margin-right: 2px; }
.quanfooter{ padding: 2px 4px; }
.quanedu{ float: left; height: 20px; font-size: 12px; line-height: 20px; color: #fff; text-align: center; padding: 0 5px; background-color: #FF5F32; border-radius: 2px; }
.quanhoujia{ float: right; font-size: 12px; color: #EF422D; line-height: 20px; vertical-align: bottom; }
.quanjias{ font-size: 14px; font-weight: bold }
.scroll-view_H .ha{ color: #FF3C29 }
.paixu .ha { color: #FF3C29 !important; border-bottom:2px solid #FF3C29 !important; }
.section{ padding-top: 5px; }
.jiazaizhong{ margin: 0 auto; padding: 10px; text-align: center; background: #eee; font-weight: bold; font-size: 16px; }
.jiazaidh{ display: none; }
.jiazaizhong wx-image{ margin: 0 auto; padding-bottom: 5px; width:60%; height:58px; display:block; }
.jiazai{ display: none; }
.glc-btn { width: 70px; font-size: 12px; height: 25px; line-height: 25px; float: right; background-color: #df2434; color: #fff; text-align: center; }
.goods_coupon { position: relative; padding: 0 8px !important; height: 20px; background: linear-gradient(to right,#ff7438,#ff1f1f); border-radius: 2px; line-height: 20px; text-align: center; font-size: 12px; color: white; float: left; }
.c_l { left: -3px; }
.clb { position: absolute; width: 6px; background-color: #fff; border-radius: 100%; margin-top: -3px; top: 50%; height: 6px; }
.c_r { right: -3px; }
.yuanjiaxiaoliang{ padding-top: 6px; padding-left: 4px; padding-right: 4px; font-size: 10px; color: #666; }
.yuanjiaxiaoliang wx-text:nth-child(2){ float: right; }
@keyframes opacity2{ 0%{opacity:0}
100%{opacity:1;}
}@media screen and (max-width: 375px) { .biaoqiandiv{ bottom: 90px !important; }
}@media screen and (max-width: 320px) { .yuanjiaxiaoliang{ padding-top: 2px; }
.goods_coupon { padding:0 6px !important; font-size:10px !important; }
.quanhoujia{ font-size: 10px!important; }
}.guanjianc{ height: 44px; }
.guanjianc wx-view{ background: #F3F3F3; border-radius: 4px; padding: 2px 6px; height: 22px; line-height: 22px; margin: 0 6px; margin-top: 10px; }
.listshop .title { padding-bottom:2px; }
.listshop{ position:relative; }
.biaoqiandiv{ position:absolute; bottom: 70px; right: 10px; }
.biaoqiandiv wx-image{ display:inherit; width: 20px; height: 20px }
.biaoqian{ background: #fff; padding: 2px 6px; color: #EF422D; font-size: 10px; border-radius: 4px; -moz-box-shadow:1px 2px 4px #B8B8B8; -webkit-box-shadow:1px 2px 4px #B8B8B8; box-shadow:1px 2px 4px #B8B8B8; }
.title{ margin-top: 2px; }
.title wx-text{ border: 1px solid #E34310; color: #E34310; font-size: 10px; padding: 1px 3px; margin-right: 2px; }
.quanfooter{ padding: 2px 4px; margin-top: 4px; }
.quanedu{ float: left; height: 20px; font-size: 12px; line-height: 20px; color: #fff; text-align: center; padding: 0 5px; background-color: #FF5F32; border-radius: 2px; }
.quanhoujia{ float: right; font-size: 12px; color: #EF422D; line-height: 20px; vertical-align: bottom; }
.quanjias{ font-size: 14px; font-weight: bold }
.section{ padding-top: 5px; }
';
        $raw = preg_replace_callback('/(\d+)px/', function ($arr) {
            return ($arr[1] * 2) . 'rpx';
        }, $raw);
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