<?php
/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2018-01-14
 * Time: 16:41
 */

include_once 'DaiShu_model.php';

class Category_model extends DaiShu_model
{
    /**
     * 获取分类目录列表
     * @link 返回值参考 application/views/data/category.json
     */
    public function get_all()
    {
        $url = 'https://public.immmmmm.com/wxapp/categorysr.txt';
        $res = curl_get($url);
        return $this->json_to_array($res);
    }

    /**
     * 大白菜商品列表导航
     * @link 返回值参考 application/views/data/list_nav.json
     */
    public function dabaicai_list_nav(){
        $json = $this->load->view('data/list_nav.json',NULL,TRUE);
        return $this->json_to_array($json);
    }

    /**
     * 获取大白菜某分类下的商品
     * @param $data ['page','cid']
     * @return array
        array (
          'goods' =>
          array (
            0 =>
            array (
              'id' => 4980,
              'title' => '植美村旗舰店 新年活动大促水精灵四件套',
              'otitle' => '植美村护肤品套装女补水保湿乳液水乳素颜霜少女秋冬学生化妆正品',
              'tbid' => '540267461790',
              'imgpath' => '',
              'imgs' => 'https://img.alicdn.com/tfscom/i4/748501705/TB2oOkEmlTH8KJjy0FiXXcRsXXa_!!748501705.jpg|https://img.alicdn.com/tfscom/i3/748501705/TB2E7czmgnH8KJjSspcXXb3QFXa_!!748501705.jpg|https://img.alicdn.com/tfscom/i4/748501705/TB2YHDjXcIa61Bjy0FiXXc1XpXa_!!748501705.jpg|https://img.alicdn.com/tfscom/i3/748501705/TB2mmSkaC0mpuFjSZPiXXbssVXa_!!748501705.jpg_430x430q90.jpg',
              'imgurl' => 'https://img.alicdn.com/tfscom/i3/748501705/TB2mmSkaC0mpuFjSZPiXXbssVXa_!!748501705.jpg_430x430q90.jpg',
              'oprice' => '362.00',
              'tbprice' => '99.00',
              'nowprice' => '99.00',
              'pprice' => '39.00',
              'tbdesc' => NULL,
              'promotion' => 3,
              'coupon_link_pc' => 'https://taoquan.taobao.com/coupon/unify_apply.htm?activityId=b24c4e43758448c5a7e8ec19566e54bc&sellerId=748501705',
              'coupon_link_m' => 'http://shop.m.taobao.com/shop/coupon.htm?seller_id=748501705&activity_id=b24c4e43758448c5a7e8ec19566e54bc&v=0&pds=wapedition%23h%23',
              'coupon_price' => '60',
              'coupon_num' => 0,
              'seq' => 8013537.0108899996,
              'istop' => 0,
              'list_status' => 2,
              'ptime' => 0,
              'mtime' => 1516010452,
              'past_rate' => '31.00',
              'tag' => '',
              'uplist_time' => 1512564650,
              'cid' => 2,
              'slogan' => '洗面奶100g+爽肤水120ML+精华乳120ML+保湿霜55g，4件爆款完美搭配。
        全套呵护，全面滋养，让肌肤完美享受寒冬时节的美妙时光。
        这个冬季，只要这一套！！！',
              'target_id' => 355,
              'tburl' => 'https://s.click.taobao.com/90b3vUw',
              'taosec' => '￥Tupa0PE19uG￥',
              'prom_status' => 0,
              'ptime_str' => NULL,
            ),
          ),
          'page' => '1',
          'lastPage' => 6814,
          'cid' => '2',
        )
     */
    public function dabaicai_goods($data){
        $url="https://public.immmmmm.com/wxapp/dabaicai.php?page=${data['page']}&cid={$data['cid']}";
        $res = curl_get($url);
        return $this->json_to_array($res);
    }


}