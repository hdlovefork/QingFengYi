<?php
/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2018-01-04
 * Time: 19:30
 */

include_once 'Dataoke_model.php';

class Query_model extends Dataoke_model
{

    /**
     * 获取优惠券的额度
     * @param $ids ['tbid']
     * @return array|null
        array (
          'title' => '小白鞋秋季女2017新款百搭韩版学生厚底丑鞋子韩范儿平底软妹板鞋',
          'quanhou' => 29.899999999999999,
          'yuanjia' => 39.899999999999999,
          'quan' => 10,
          'activity' => '313edc19430d4c378e63275b7ef8c27a',
          'taoid' => '561874449846',
          'xiaoliang' => '192',
          'mp4' => '',
          'jie' => '韩范儿休闲风，棉质内里，柔软吸汗透气，耐磨防滑，加厚隐形鞋底，轻松穿出大长腿~',
        )
     */
    public function get_quan_info($ids)
    {
        $this->benchmark->mark('get_quan_info_start');
        $request = new \libraries\daotaoke\request\SingleGoodsGet($this->dtk_key);
        $request->setId($ids['tbid']?:$ids['dtkid']);
        $res = $this->client->execute($request);
        $this->benchmark->mark('dataoke_get_quan_end');
//        log_message('DEBUG', "quan_start--->get_quan_info_start:{$this->benchmark->elapsed_time('quan_start', 'get_quan_info_start')}");
//        log_message('DEBUG', "get_quan_info_start--->dataoke_get_quan_end:{$this->benchmark->elapsed_time('get_quan_info_start', 'dataoke_get_quan_end')}");
        if (!$res || !$res['result']) return null;
        $goods = $res['result'];
        $data = array(
            'title' => $goods['Title'],
            'quanhou' => round($goods['Price'], 2),
            'yuanjia' => round($goods['Org_Price'], 2),
            'quan' => round($goods['Quan_price'], 2),
            'activity' => $goods['Quan_id'],
            'taoid' => $goods['GoodsID'],
            'xiaoliang' => $goods['Sales_num'],
            'mp4' => $this->get_video_url($ids['dtkid']),
            'jie' => $goods['Introduce']
        );
        return $data;
    }

    /**
     * 调用淘宝API获取图片详情
     * @param $ids
     * @return null
        array (
          0 => 'https://assets.alicdn.com/kissy/1.0.0/build/imglazyload/spaceball.gif"><p><img',
          1 => 'https://img.alicdn.com/imgextra/i2/1137198250/TB2Ul7jeIjI8KJjSsppXXXbyVXa_!!1137198250.jpg',
          2 => 'https://img.alicdn.com/imgextra/i1/1137198250/TB29XpBaQfb_uJkSnb4XXXCrXXa_!!1137198250.jpg',
          3 => 'https://img.alicdn.com/imgextra/i4/1137198250/TB29pxYbtHO8KJjSZFLXXaTqVXa_!!1137198250.jpg',
          4 => 'https://img.alicdn.com/imgextra/i3/1137198250/TB2_PMseNPI8KJjSspoXXX6MFXa_!!1137198250.jpg',
          5 => 'https://assets.alicdn.com/kissy/1.0.0/build/imglazyload/spaceball.gif"><p><img',
          6 => 'https://img.alicdn.com/imgextra/i4/1137198250/TB2JRX6bqLN8KJjSZFKXXb7NVXa_!!1137198250.jpg',
          7 => 'https://img.alicdn.com/imgextra/i1/1137198250/TB2vRtubvfM8KJjSZPfXXbklXXa_!!1137198250.jpg',
          8 => 'https://assets.alicdn.com/kissy/1.0.0/build/imglazyload/spaceball.gif"><p><img',
          9 => 'https://img.alicdn.com/imgextra/i3/1137198250/TB2C0ZieJrJ8KJjSspaXXXuKpXa_!!1137198250.jpg',
          10 => 'https://img.alicdn.com/imgextra/i1/1137198250/TB2wc4CaPgy_uJjSZR0XXaK5pXa_!!1137198250.jpg',
          11 => 'https://img.alicdn.com/imgextra/i2/1137198250/TB28fR8bqLN8KJjSZFmXXcQ6XXa_!!1137198250.jpg',
          12 => 'https://assets.alicdn.com/kissy/1.0.0/build/imglazyload/spaceball.gif"><p><img',
          13 => 'https://img.alicdn.com/imgextra/i1/1137198250/TB2PX3DeN6I8KJjy0FgXXXXzVXa_!!1137198250.jpg',
          14 => 'https://img.alicdn.com/imgextra/i2/1137198250/TB2bW47bBbM8KJjSZFFXXaynpXa_!!1137198250.jpg',
          15 => 'https://img.alicdn.com/imgextra/i3/1137198250/TB2Z80yaPgy_uJjSZSyXXbqvVXa_!!1137198250.jpg',
          16 => 'https://assets.alicdn.com/kissy/1.0.0/build/imglazyload/spaceball.gif"><p><img',
          17 => 'https://img.alicdn.com/imgextra/i4/1137198250/TB2hCIeeRHH8KJjy0FbXXcqlpXa_!!1137198250.jpg',
          18 => 'https://img.alicdn.com/imgextra/i4/1137198250/TB25fwpeNrI8KJjy0FpXXb5hVXa_!!1137198250.jpg',
          19 => 'https://img.alicdn.com/imgextra/i3/1137198250/TB2d40AaQfb_uJjSsD4XXaqiFXa_!!1137198250.jpg',
        )
     */
    public function get_detail_pics($ids)
    {
        $url = 'http://api.m.taobao.com/h5/mtop.taobao.detail.getdesc/6.0/?data=%s';
        
        $str = sprintf('{"id":"%s"}', $ids['tbid']);
        $url = sprintf($url, urlencode($str));
        $str = curl_get($url);
        $data = $this->json_to_array($str);
        if(!$data) return NULL;
        $html_pics = urldecode($data['data']['pcDescContent']);
        $html_pics = stripslashes($html_pics);

        $preg = '/<img.*?src=[\"|\']?(.*?)[\"|\']?\s.*?>/i';
        preg_match_all($preg, $html_pics, $imgArr);
        if (count($imgArr) === 2) {
            return $this->add_protocol($imgArr[1]);
        }
        return NULL;
    }
    
    public function get_video_url($dtkid){
        $this->load->helper('phpquery');
        return phpQuery::newDocumentFile("http://www.dataoke.com/item?id={$dtkid}")
        ->find('#jp_container_1')
            ->attr('data-video');
    }

}