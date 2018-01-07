<?php
/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2018-01-04
 * Time: 20:23
 */

include_once 'Page_model.php';

/**
 * 商品详情页面
 * Class Detail_model
 */
class Detail_model extends Page_model
{
    protected $service = 'dataoke';
    protected $taoapi = 'huoxing';


    /**
     * @param $ids ['tbid','dtkid']
     * @return array array(
     *  'title' => '正版中国孩子少儿百科全书6-12岁小学生版注音十万个为什么幼儿童科普拼音故事书地理全套儿童读物7-10课外阅读书籍动植物恐龙绘本',
     *  'quanhou' => '18.88',
     *  'yuanjia' => '28.88',
     *  'quan' => '10',
     *  'activity' => '5bc44752ebff4125ab418a836a73ed78',
     *  'taoid' => '540669645278',
     *  'xiaoliang' => '8107',
     *  'mp4' => '',
     *  'jie' => '全新升级彩图全8册96P彩图注音版，环保纸张，内容精粹，善于发现，注重细节，培养想象能力和创意力的童话故事。【送运费险】',
     *  )
     */
    public function get_quan_info($ids)
    {
        $this->load->model("service/{$this->service}/query_model");
        $res = $this->query_model->get_quan_info($ids);
        return $res;
    }


    /**
     * @param $ids ['tbid','dtkid']
     * @return array array(
     *    0 => 'https://img.alicdn.com/imgextra/i4/2139091636/TB2DhqKatFopuFjSZFHXXbSlXXa_!!2139091636.jpg',
     *    1 => 'https://img.alicdn.com/imgextra/i3/2139091636/TB2tK0llwDD8KJjy0FdXXcjvXXa_!!2139091636.jpg',
     *    2 => 'https://img.alicdn.com/imgextra/i3/2139091636/TB27odaXciEJuJjy1XbXXaizFXa_!!2139091636.jpg',
     *    3 => 'https://img.alicdn.com/imgextra/i3/2139091636/TB2kZNyb1IPyuJjSspcXXXiApXa_!!2139091636.jpg',
     *    4 => 'https://img.alicdn.com/imgextra/i4/2139091636/TB2cTHdaTZRMeJjSspnXXcJdFXa_!!2139091636.jpg',
     *    5 => 'https://img.alicdn.com/imgextra/i4/2139091636/TB2maQndk7myKJjSZFzXXXgDpXa_!!2139091636.jpg',
     *    6 => 'https://img.alicdn.com/imgextra/i3/2139091636/TB21TQbdd3nyKJjSZFjXXcdBXXa_!!2139091636.jpg',
     *    7 => 'https://img.alicdn.com/imgextra/i4/2139091636/TB2XBM4bK7JL1JjSZFKXXc4KXXa_!!2139091636.jpg',
     *    8 => 'https://img.alicdn.com/imgextra/i1/2139091636/TB2xVFab7.HL1JjSZFlXXaiRFXa_!!2139091636.jpg',
     *    9 => 'https://img.alicdn.com/imgextra/i1/2139091636/TB2_y8ub8cHL1JjSZJiXXcKcpXa_!!2139091636.jpg',
     *    10 => 'https://img.alicdn.com/imgextra/i1/2139091636/TB2zHXCb.sIL1JjSZPiXXXKmpXa_!!2139091636.jpg',
     *    11 => 'https://img.alicdn.com/imgextra/i4/2139091636/TB22dRHb1EJL1JjSZFGXXa6OXXa_!!2139091636.jpg',
     *    12 => 'https://img.alicdn.com/imgextra/i1/2139091636/TB2quVHb8cHL1JjSZJiXXcKcpXa_!!2139091636.jpg',
     *    13 => 'https://img.alicdn.com/imgextra/i2/2139091636/TB29qzJaMsSMeJjSsphXXXuJFXa_!!2139091636.jpg',
     *    14 => 'https://img.alicdn.com/imgextra/i3/2139091636/TB2l5FKb5wIL1JjSZFsXXcXFFXa_!!2139091636.jpg',
     *    )
     */
    public function get_detail_pics($ids)
    {
        $this->load->model("service/{$this->service}/query_model");
        $res = $this->query_model->get_detail_pics($ids);
        return $res;
    }

    /**
     * 获得淘口令
     * @param $data array ['activity','tbid','pid']
     * @return array|null ['gmtip','guanlink','tkl']
     */
    public function get_tkl($data)
    {
        $this->load->model("service/{$this->taoapi}/taoapi_model");
        $res = $this->taoapi_model->get_tkl($data);
        return $res;
    }

    /**
     * 获取评论数据
     * @param $data array ['tbid','page']
     * @return null|array
     */
    public function get_rate($data){
        $this->load->model("service/{$this->taoapi}/taoapi_model");
        $res = $this->taoapi_model->get_rate($data);
        return $res;
    }
}