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
        $raw = 'body{ -webkit-animation: 1s opacity2; animation: 1s opacity2; font-family: \'微软雅黑\'; font-size: 14px; color: #666666; background: #fff; }
@-webkit-keyframes opacity2{ 0%{opacity:0}
50%{opacity:0.5;}
100%{opacity:1;}
}@keyframes opacity2{ 0%{opacity:0}
50%{opacity:0.5;}
100%{opacity:1;}
}@-webkit-keyframes opacity3{ 0%{opacity:0}
50%{opacity:0.5;}
100%{opacity:1;}
}@keyframes opacity3{ 0%{opacity:0}
50%{opacity:0.5;}
100%{opacity:1;}
}.new_div{ padding:10px; background: #fff; border-radius: 6px; }
wx-swiper{ width: 100%; height: 20rem; }
.img{ width: 100%; -webkit-animation: 1s opacity3; animation: 1s opacity3; }
.mp4{ height: 20rem; width: 100%; }
.quandiv{ padding: 8px 0; text-align: center; border-bottom: 1px solid #eee; }
.jieshaop{ margin-bottom:84px }
.title{ color: #666666; letter-spacing:1px; }
.jieshao{ padding: 10px; border-top: 4px solid #eee; line-height: 18px; font-size: 12px; color: #808080 ; }
.jieshao wx-text:nth-child(1){ border-radius: 2px; color: #ff6a00; border: 1px solid #ff6600; padding: 1px 3px; font-size: 10px; margin-right: 2px; }
.lingquan{ -webkit-animation: 1s opacity3; animation: 1s opacity3; text-align: center; margin-top: 10px; position:relative; }
.lingquan .lingquantext{ position:absolute; top:30px; right:36px; color: #fff; font-size: 16px; }
.lingquan .quane{ position:absolute; top:24px; left:70px; color: #fff; font-size: 24px; }
.lingquan .quane .quanet{ font-size: 20px; }
.lingquan wx-image{ width: 96%; }
.dianpu .dptitle{ margin-top: 4px; }
.dianpu{ border-top: 4px solid #eee; border-bottom: 4px solid #eee; padding: 10px; }
.dianpu .dplogo{ display: initial; }
.dianpu .dplogo wx-image{ width: 20px; height: 20px; }
.dianpu .dpjieshao{ float: left; padding-left: 20px; }
.dianpu .dppicUrl{ float: left; }
.dpxinxi{ margin-top: 6px; }
.dpxinxi .ping{ font-size: 12px; display: initial; padding-right: 4px; }
.dianpu .dppicUrl wx-image{ width: 60px; height: 60px; border-radius: 2px; }
.jiage{ float: left; font-size: 16px; padding: 4px; color: #f10000; }
.jiage wx-image{ -webkit-animation: 1s opacity2; animation: 1s opacity2; }
.jiage .quans{ font-size: 20px; color: #f10000; }
.xiangqingtitle{ text-align: center; color: #444; font-size: 14px; padding: 10px 0; }
.quanhou{ color: #f10000; font-size: 16px; }
.quan{ padding-left: 20px; color: #ff6a00; font-size: 12px; }
.xiaoliang{ margin-top: 8px; float: right; display:inline; color:#666666; font-size: 12px; padding-left: 20px; }
.tklv{ clear: both; margin: 4px 0; padding: 4px; border: 1px dashed #ff6600; }
.tklv wx-text{ line-height: 22px; font-size: 12px; color: #ff6a6a; }
.tklp{ color: #cecece; font-size: 10px; padding-top: 8px; }
.tklbtn{ margin: 10px 0; background-color: #d24a58; color: #fff; font-size: 14px; text-align: center; margin-left: 10px; margin-right: 10px; }
.foottkl{ box-shadow: -10px 10px 10px 10px #eee; background: #fff; position: fixed; height: 48px; line-height: 48px; bottom: 0; width: 100%; }
.foottkll{ background: fff; float: left; width: 70%; }
.tuwen wx-image{ width: 100%; }
.foottklls{ padding-left: 20px; font-size: 12px; }
.footquanhou{ font-size: 14px; color: #ff6600 }
.footyuanjia{ font-size: 13px; color: #666666; padding-left: 10px; text-decoration: line-through; }
.widget-goTop { position: fixed; bottom: 85px; right: 5px; overflow: hidden; z-index: 500; }
.tishi{ text-align: center; color: #444; margin-bottom: 160px; }
.widget-goTop .gotop-wrap { display:-webkit-flex; display:flex; -webkit-flex-direction:column; flex-direction:column; -webkit-align-items:center; align-items:center; -webkit-justify-content:center; justify-content:center; }
.foottklr{ float: right; width: 30%; text-align: center; color: #fff; background-image: -webkit-linear-gradient(to right,#ff7438,#ff1f1f); background-image: linear-gradient(to right,#ff7438,#ff1f1f); }
.chakan{ text-align: center; margin-top: 6px; padding: 8px; background: whitesmoke; border-radius: 4px; }
.shengcheng{ z-index: 99999; position:fixed; top:50px; right: 0; background:rgba(0, 0, 0, 0.55); color: #fff; font-size: 12px; display: initial; padding: 1px 8px; border-bottom-left-radius: 4px; border-top-left-radius: 4px; border-bottom-right-radius: 0px; border-top-right-radius: 0px; }
.fenxiang{ top:120px; }
.fahuodianjia{ margin-top: 4px; padding-top:6px; font-size:10px; color:#808080; }
.quandiv{ margin-bottom: 5px; }
.fahuodianjia .huodi{ float: left; }
.cont{ text-align: center; padding: 6px 0; border-bottom: 1px solid #eee; }
.fahuodianjia .dianjia{ float: right; }
.itemTypeName{ background: #f10000; padding: 2px 4px; color: #fff; line-height: 20px; font-size: 10px; border-radius: 4px; display: initial; }
.img-plus-style { height: 50px; width: 50px; position:fixed; z-index: 100; bottom:135px; right:5px; }
.is_kuaijiedaohang{ position:fixed; bottom:183px; right:10px; z-index:100; opacity:1; }
.is_kuaijiedaohang wx-image{ width: 240px; }
.img-style{ display: -webkit-flex; display: flex; -webkit-justify-content:center; justify-content:center; -webkit-align-items:Center; align-items:Center; width: 78px; position: fixed; bottom: 140px; right: 15px; opacity: 0; }
.img-style wx-text{ padding-right: 5px; color: #fff; font-size: 12px; }
.img-style wx-image{ height: 35px; width: 35px; }
.toolbutton{ border: none; padding: 0; margin: 0 auto; color: #fff; background-color:rgba(0, 0, 0, 0); }
.toolbutton:after{ border: none; }
.toolbutton wx-text{ display:inline-table; }
.toolbutton wx-image{ display:inline-table; }
.modal { position: fixed; height: 100%; width: 100%; display:-webkit-flex; display:flex; -webkit-flex-direction:column; flex-direction:column; -webkit-align-items:center; align-items:center; -webkit-justify-content: center; justify-content: center; background:rgba(0, 0, 0, 0.80); }
.guigediv{ padding: 0 10px; border-bottom: 1px solid #eee; line-height: 26px; font-size: 12px; }
.guigediv wx-text{ color: #444; }
.pinlinmini{ border-bottom:4px solid #eee; }
.guigedivs{ border-bottom:4px solid #eee; }
.reping{ padding: 10px; line-height: 26px; }
.touxianghead{ display: block; }
.touxianghead wx-image{ width: 20px; height: 20px; border-radius: 50%; }
.touxianghead .nick{ padding-left: 10px; color: #333; }
.pinglunmain{ font-size: 12px; line-height: 24px; color: #333; }
.biaoqian{ margin:0 auto; padding: 8px 0px; color: #999; font-size: 12px; }
.pinlunguig{ color: #666; font-size: 10px; }
.pinlunimages{ float: left; width: 70px; height: 60px; margin: 5px; }
.pinlunimages wx-image{ width: 100%; border-radius: 2px; height: 60px; }
.chakanquanbupl{ text-align: center; margin-top: 4px; }
.chakanquanbupl wx-text{ font-size: 10px; padding: 5px 10px; border: 1px #ff6600 solid; border-radius: 20px; color: #ff6600; }
@media screen and (max-width:440px) { .is_kuaijiedaohang { bottom:202px; }
.img-plus-style,.img-style{ bottom:155px; }
.img-style{ right:13px; }
.lingquan .quane { top:30px; left:81px; }
.lingquan .lingquantext{ top:37px; right:40px; font-size:18px; }
}@media screen and (max-width:414px) { .img-plus-style,.img-style{ bottom:135px; }
.img-style{ right:13px; }
.lingquan .quane { top:28px; }
.lingquan .lingquantext{ top:32px; right:38px; }
}@media screen and (max-width: 375px) { .is_kuaijiedaohang{ bottom:189px; }
.img-plus-style,.img-style{ bottom:142px; }
.img-style{ right:13px; }
.lingquan .quane { top:21px; }
.lingquan .lingquantext{ top:27px; right:30px; }
}@media screen and (max-width: 360px) { .lingquan .quane { left:67px; top:22px; }
.lingquan .lingquantext{ top:29px; right:31px; }
}@media screen and (max-width: 320px) { .dianpu .dpjieshao{ padding-left: 10px; }
.img-plus-style,.img-style{ bottom:130px; }
.img-style{ right:13px; }
.chakan{ margin-top: 0; }
.lingquan .quane{ top:17px; left:47px; }
.lingquan .lingquantext{ font-size:16px; top:23px; right:23px; }
.lingquan .quane{ font-size: 22px; }
.lingquan .quane .quanet { font-size:17px; }
}';
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