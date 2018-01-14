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
    //array (
    //  'results' =>
    //  array (
    //    'homepage' => '',
    //    'shenhe' => 'false',
    //    'hometan' => '1',
    //    'homejqb' => '',
    //    'chaojisou' => '/pages/search/search?so=',
    //    'tancimg' => '',
    //    'homets' => '',
    //    'jifen' => 'true',
    //    'miandan' => 'true',
    //    'homeso' => '',
    //    'paiban' => 'on',
    //    'zttj' => 'on',
    //    'ddqgkg' => 'on',
    //    'dlsq' => 'on',
    //    'pinglun' => 'true',
    //    'cidso' => '/pages/dataoke/search/search?type=cid&so=',
    //    'zhaoso' => '/pages/dataoke/search/search?so=',
    //    'weixinhao' => '805500091',
    //    'qqhao' => '805500091',
    //    'hometanso' => '',
    //    'shoujihao' => '',
    //    'ewm_url' => 'http://ww3.sinaimg.cn/large/0060lm7Tly1fmhfivbk2rj30by0byaax.jpg',
    //    'title' => '微信小程序',
    //    'jieshao' => '如有任何问题可联系QQ/微信客服：805500091',
    //    'home' =>
    //    array (
    //      'buttonimg' => 'https://immmmmm.com/images/cjss.jpg',
    //      'yemian' => '/pages/souquan/soquan',
    //      'homeon' => 'on',
    //    ),
    //  ),
    //  'topic' =>
    //  array (
    //    'topic1' =>
    //    array (
    //      'pages' => '/pages/list/huodong/huodong?get=ershijiu&title=30元优质好货,超值商品推荐',
    //      'src' => 'http://public.immmmmm.com/images/topic/i19.jpg',
    //    ),
    //    'topic2' =>
    //    array (
    //      'pages' => '/pages/baicai/search/search?so=年货',
    //      'src' => 'http://public.immmmmm.com/images/topic/i18.jpg',
    //    ),
    //    'topic3' =>
    //    array (
    //      'pages' => '/pages/topic/topic?id=qinglv',
    //      'src' => 'http://public.immmmmm.com/images/topic/i15.jpg',
    //    ),
    //    'topic4' =>
    //    array (
    //      'pages' => '/pages/list/haitao/haitao?title=海淘精选',
    //      'src' => 'http://public.immmmmm.com/images/topic/i14.jpg',
    //    ),
    //  ),
    //  'banner' =>
    //  array (
    //    'one5073642&u=1' =>
    //    array (
    //      'url' => 'https://img.alicdn.com/imgextra/i2/1879660321/TB2CP2PlwDD8KJjy0FdXXcjvXXa_!!1879660321.jpg',
    //      'ym' => '/pages/share/share?x=dtk&ti=5073642&u=1&i=561874449846',
    //    ),
    //    'one5120022&u=1' =>
    //    array (
    //      'url' => 'https://img.alicdn.com/imgextra/i1/1879660321/TB26ryzkQfb_uJjSsrbXXb6bVXa_!!1879660321.jpg',
    //      'ym' => '/pages/share/share?x=dtk&ti=5120022&u=1&i=542298326367',
    //    ),
    //    'one5106034&u=1' =>
    //    array (
    //      'url' => 'https://img.alicdn.com/imgextra/i4/1879660321/TB2zjUFehk98KJjSZFoXXXS6pXa_!!1879660321.jpg',
    //      'ym' => '/pages/share/share?x=dtk&ti=5106034&u=1&i=557660520315',
    //    ),
    //    'one5074552&u=1' =>
    //    array (
    //      'url' => 'https://img.alicdn.com/imgextra/i4/1879660321/TB2lAJQlv2H8KJjy0FcXXaDlFXa_!!1879660321.jpg',
    //      'ym' => '/pages/share/share?x=dtk&ti=5074552&u=1&i=536238401521',
    //    ),
    //    'one5093923&u=1' =>
    //    array (
    //      'url' => 'https://img.alicdn.com/imgextra/i2/1879660321/TB22IMvlMvD8KJjSsplXXaIEFXa_!!1879660321.jpg',
    //      'ym' => '/pages/share/share?x=dtk&ti=5093923&u=1&i=554828921926',
    //    ),
    //  ),
    //  'homeicon' =>
    //  array (
    //    0 =>
    //    array (
    //      'icon1' =>
    //      array (
    //        'src' => 'https://immmmmm.com/images/icon1.png',
    //        'txt' => '聚划算',
    //        'ym' => '/pages/list/huodong/huodong?get=calculate&title=【聚划算】无所不能聚',
    //      ),
    //      'icon2' =>
    //      array (
    //        'src' => 'https://immmmmm.com/images/icon2.png',
    //        'txt' => '边看边买',
    //        'ym' => '/pages/list/huodong/huodong?get=video&title=【边看边买】看得见的商品',
    //      ),
    //      'icon3' =>
    //      array (
    //        'src' => 'https://immmmmm.com/images/icon3.png',
    //        'txt' => '天猫精选',
    //        'ym' => '/pages/list/huodong/huodong?get=tianmao&title=【天猫精选】品牌大促销',
    //      ),
    //      'icon4' =>
    //      array (
    //        'src' => 'https://immmmmm.com/images/icon4.png',
    //        'txt' => '九块九邮',
    //        'ym' => '/pages/list/huodong/huodong?get=jiu&title=【九块九包邮】超值商品推荐',
    //      ),
    //      'icon5' =>
    //      array (
    //        'src' => 'https://immmmmm.com/images/icon5.png',
    //        'txt' => '查券教程',
    //        'ym' => '/pages/wode/lingquanjc/lingquanjc',
    //      ),
    //    ),
    //  ),
    //)
     */
    public function home_init()
    {
//        $url = 'https://public4.immmmmm.com/14e67858f5c545/collocation2.php?banben=V14.9.1';
        $url = 'https://public4.immmmmm.com/ceshiapp/collocation2.php?banben=V14.9.1';
        $res = curl_get($url, $http_code);
        if ($http_code === 200 && $res) {
            $this->collection = $this->json_to_array($res);
            //log_message('DEBUG', "袋鼠首页数据\n" . var_export($this->collection,TRUE));
        } else {
            log_message('ERROR', '袋鼠首页初始化失败');
        }
    }
}