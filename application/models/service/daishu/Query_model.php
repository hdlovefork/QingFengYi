<?php
/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2018-01-04
 * Time: 19:30
 */

include_once 'DaiShu_model.php';

class Query_model extends DaiShu_model
{

    //array (
    //'title' => '格力取暖器家用 节能速热电热膜电暖器儿童防烫安全可干衣定时',
    //'quanhou' => '258',
    //'yuanjia' => '318.00',
    //'quan' => '60',
    //'activity' => '9c51271aa4bf4c988f77ab6aa3e1afac',
    //'taoid' => '35512415705',
    //'xiaoliang' => '1369',
    //'mp4' => '',
    //'jie' => '格力电暖气节能暖风机，品牌保证，6秒速热，即开即热。智能恒温技术，移动轻松，收纳方便，无油，安全环保【赠运费险】',
    //)
    public function get_quan_info($ids)
    {
        $url = "https://public.immmmmm.com/wxapp/dataoke.php?r=p/d&id={$ids['dtkid']}";
        $str = curl_get($url);
        return $this->json_to_array($str);
    }

    //array (
    //0 => 'https://img.alicdn.com/imgextra/i4/2139091636/TB2DhqKatFopuFjSZFHXXbSlXXa_!!2139091636.jpg',
    //1 => 'https://img.alicdn.com/imgextra/i3/2139091636/TB2tK0llwDD8KJjy0FdXXcjvXXa_!!2139091636.jpg',
    //2 => 'https://img.alicdn.com/imgextra/i3/2139091636/TB27odaXciEJuJjy1XbXXaizFXa_!!2139091636.jpg',
    //3 => 'https://img.alicdn.com/imgextra/i3/2139091636/TB2kZNyb1IPyuJjSspcXXXiApXa_!!2139091636.jpg',
    //4 => 'https://img.alicdn.com/imgextra/i4/2139091636/TB2cTHdaTZRMeJjSspnXXcJdFXa_!!2139091636.jpg',
    //5 => 'https://img.alicdn.com/imgextra/i4/2139091636/TB2maQndk7myKJjSZFzXXXgDpXa_!!2139091636.jpg',
    //6 => 'https://img.alicdn.com/imgextra/i3/2139091636/TB21TQbdd3nyKJjSZFjXXcdBXXa_!!2139091636.jpg',
    //7 => 'https://img.alicdn.com/imgextra/i4/2139091636/TB2XBM4bK7JL1JjSZFKXXc4KXXa_!!2139091636.jpg',
    //8 => 'https://img.alicdn.com/imgextra/i1/2139091636/TB2xVFab7.HL1JjSZFlXXaiRFXa_!!2139091636.jpg',
    //9 => 'https://img.alicdn.com/imgextra/i1/2139091636/TB2_y8ub8cHL1JjSZJiXXcKcpXa_!!2139091636.jpg',
    //10 => 'https://img.alicdn.com/imgextra/i1/2139091636/TB2zHXCb.sIL1JjSZPiXXXKmpXa_!!2139091636.jpg',
    //11 => 'https://img.alicdn.com/imgextra/i4/2139091636/TB22dRHb1EJL1JjSZFGXXa6OXXa_!!2139091636.jpg',
    //12 => 'https://img.alicdn.com/imgextra/i1/2139091636/TB2quVHb8cHL1JjSZJiXXcKcpXa_!!2139091636.jpg',
    //13 => 'https://img.alicdn.com/imgextra/i2/2139091636/TB29qzJaMsSMeJjSsphXXXuJFXa_!!2139091636.jpg',
    //14 => 'https://img.alicdn.com/imgextra/i3/2139091636/TB2l5FKb5wIL1JjSZFsXXcXFFXa_!!2139091636.jpg',
    //)
    public function get_detail_pics($ids)
    {
        $url = "https://public.immmmmm.com/wxapp/tbcache.php?id={$ids['tbid']}";
        $str = curl_get($url);
        $res = $this->json_to_array($str);
        if ($res) {
            return $res['data']['images'];
        }
        return null;
    }

    /**
     * 热门搜索关键字
     * @return mixed|null
array (
  'er_code' => 10000,
  'er_msg' => '请求成功',
  'data' =>
  array (
    0 =>
    array (
      'rank' => '1',
      'word' => '羽绒服',
      'total' => '28006',
      'change' => '0',
    )
  ),
)
     */
    public function reso(){
        $url = 'https://public.immmmmm.com/wxapp/reso.php';
        $res = curl_get($url);
        return $this->json_to_array($res);
    }

}