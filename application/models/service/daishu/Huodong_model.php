<?php
/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2018-01-08
 * Time: 23:19
 */

include_once APPPATH . 'models/service/Remote_model.php';

class Huodong_model extends Remote_model
{
    public function get($data)
    {
        $res = null;
        $method = $data['huodong'];
        if (method_exists($this, $method)) {
            $res = $this->{$method}($data);
        }
        return $res;
    }

//array (
//  'changcidata' =>
//  array (
//    0 =>
//    array (
//      'shikuang' => '已开抢',
//      'changciid' => '0900',
//      'shijiantiem' => '00:00',
//    ),
//  ),
//  'jxsid' => '0921',
//  'jxss' => '9',
//  'jxstiem' => '21:00',
//  'goods' =>
//  array (
//    0 =>
//    array (
//      'paiqi' => '2018-01-09 21:00:00',
//      'new_words' => '加厚加固，无棱角设计，触摸感十足【赠送运费险】',
//      'dtkid' => '5118142',
//      'imgurl' => 'https://img.alicdn.com/imgextra/i3/2172425935/TB2ztPrgcrI8KJjy0FhXXbfnpXa_!!2172425935.jpg_400x400.jpg',
//      'otitle' => '儿童摇摇马音乐小摇椅宝宝玩具婴儿摇摇车',
//      'title' => '摇摇马木马儿童1-2-3周岁宝宝生日礼物带音乐塑料小玩具婴儿椅车',
//      'descript1' => '环保无毒塑料，加厚加固，无棱角设计，触摸感十足，卡通可爱，适合0-6岁的宝贝，快乐童年从玩摇马开始【赠送运费险】',
//      'yuanjia' => '75.00',
//      'coupon_price' => '20.00',
//      'xiaoliang' => '14207',
//      'huodong_type' => '2',
//      'quan_time' => '2018-01-10 23:59:59',
//      'quan_num' => '50000',
//      'jiage' => '55.00',
//      'pprice' => 55,
//    ),
//  ),
//)
    public function newqiang()
    {
        $url = 'https://public.immmmmm.com/wxapp/dataoke.php?huodong=newqiang';
        $res = curl_get($url);
        return $this->json_to_array($res);
    }

    /**
     * 获取首页最下方列表数据
     * @param $data
     * @return mixed|null
    array (
     * 'goods' =>
     * array (
     * 0 =>
     * array (
     * 'imgurl' => 'https://img.alicdn.com/imgextra/i1/2140731718/TB18YrlbgMPMeJjy1XdXXasrXXa_!!0-item_pic.jpg_300x300.jpg',
     * 'otitle' => '威莱登尼【加绒加厚】男手套女手套',
     * 'listmp4' => 'video_icon',
     * 'pprice' => '14.9',
     * 'coupon_price' => '5',
     * 'biaoqian' => '测试',
     * 'xiaoliang' => '6.8万',
     * 'tbid' => '40763353135',
     * 'dtkid' => '5146782',
     * ),
     */
    public function home($data)
    {
        $url = "https://public.immmmmm.com/wxapp/dataoke.php?huodong=home&page={$data['[page']}";
        $res = curl_get($url);
        return $this->json_to_array($res);
    }

    /**
     * 销量榜
     * @param $data
     * @return array
    array (
     * 'goods' =>
     * array (
     * 0 =>
     * array (
     * 'imgurl' => 'https://img.alicdn.com/imgextra/i3/3012913363/TB2n2NqlZLJ8KJjy0FnXXcFDpXa_!!3012913363.jpg_480x480.jpg',
     * 'otitle' => '【良品铺子】零食年货大礼包（2规格选）',
     * 'pprice' => '49.9',
     * 'coupon_price' => '10',
     * 'xiaoliang' => '84091',
     * 'tbid' => '557596683088',
     * 'dtkid' => '5157018',
     * ),
     * ),
     * )
     */
    public function ssxiaobang($data)
    {
        $url = "https://public.immmmmm.com/wxapp/dataoke.php?huodong=ssxiaobang&page={$data['page']}&px={$data['px']}";
        $res = curl_get($url);
        return $this->json_to_array($res);
    }

    /**
     * 聚划算
     * @param $data
     * @return mixed|null 与ssxiaobang()方法返回值相同
     */
    public function calculate($data){
        $url = "https://public.immmmmm.com/wxapp/dataoke.php?huodong=calculate&page={$data['page']}&px={$data['px']}";
        $res = curl_get($url);
        return $this->json_to_array($res);
    }

    /**
     * 边看边买
     * @param $data
     * @return mixed|null 与ssxiaobang()方法返回值相同
     */
    public function video($data){
        $url ="https://public.immmmmm.com/wxapp/dataoke.php?huodong=video&page={$data['page']}&px={$data['px']}";
        $res = curl_get($url);
        return $this->json_to_array($res);
    }

    /**
     * 海淘精选
     * @param $data
     * @return mixed|null 与ssxiaobang()方法返回值相同
     */
    public function haitao($data){
        $url ="https://public.immmmmm.com/wxapp/dataoke.php?huodong=haitao&page={$data['page']}&px={$data['px']}";
        $res = curl_get($url);
        return $this->json_to_array($res);
    }

    /**
     * 30元封顶
     * @param $data
     * @return mixed|null 与ssxiaobang()方法返回值相同
     */
    public function ershijiu($data){
        $url ="https://public.immmmmm.com/wxapp/dataoke.php?huodong=ershijiu&page={$data['page']}&px={$data['px']}";
        $res = curl_get($url);
        return $this->json_to_array($res);
    }
}