<?php
/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2018-01-08
 * Time: 20:16
 */
include_once 'DaiShu_model.php';

class Topic_model extends DaiShu_model
{

//array (
//  0 =>
//  array (
//    'pages' => '/pages/list/huodong/huodong?get=ershijiu&title=30元优质好货,超值商品推荐',
//    'src' => 'http://public.immmmmm.com/images/topic/i19.jpg',
//  ),
//)
    public function get_all()
    {
        if (isset($this->collection, $this->collection['topic'])) {
            return array_values($this->collection['topic']) ?: null;
        }
        return null;
    }

    /**
     * 获取某个主题下的商品列表
     * @param $data array ['id'=>'qinglv']
     * @return mixed|null
array (
  'data' =>
  array (
    'topic_id' => 21295,
    'title' => '甜蜜情侣',
    'coupon_list' =>
    array (
      0 =>
      array (
        'coupon_id' => 17907491,
        'title' => '秋冬韩版刺绣拼接高领卫衣',
        'raw_price' => '118',
        'zk_price' => '59',
        'platform_id' => 2,
        'item_id' => 560875059972,
        'post_free' => 1,
        'month_sales' => 5849,
        'thumbnail_pic' => 'http://gaitaobao2.alicdn.com/tfscom/i3/2980392595/TB19VZhXS_I8KJjy0FoXXaFnVXa_!!0-item_pic.jpg_400x400',
        'share_url' => 'http://m.sqkb.com/coupon/17907491?channel=333&from_sqkb=1',
        'detail_url' => 'http://m.ibantang.com/zhekou/17907491?channel=11',
        'is_new' => false,
        'url' => 'https://item.taobao.com/item.htm?id=560875059972',
        'product_type' => 2,
        'subcate_id' => 111,
        'description' => '',
        'is_must_grab' => false,
        'is_from_tb' => false,
        'prepayment' => '0',
        'is_promotion' => 0,
        'update_time' => 1513217902,
        'apply_commission_type' => 0,
        'discount' => '5',
        'pid' => '',
        'shop_type' => 1,
      ),
    ),
  ),
  'message' => '请求成功',
  'timestamp' => 1513819892,
  'status_code' => 1,
)
     */
    public function get($data){
        $url  = "https://public.immmmmm.com/wxapp/topic.php?id={$data['id']}";
        $res = curl_get($url);
        return $this->json_to_array($res);
    }
}