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
    //array (
    //'title' => '正版中国孩子少儿百科全书6-12岁小学生版注音十万个为什么幼儿童科普拼音故事书地理全套儿童读物7-10课外阅读书籍动植物恐龙绘本',
    //'quanhou' => '18.88',
    //'yuanjia' => '28.88',
    //'quan' => '10',
    //'activity' => '5bc44752ebff4125ab418a836a73ed78',
    //'taoid' => '540669645278',
    //'xiaoliang' => '8107',
    //'mp4' => '',
    //'jie' => '全新升级彩图全8册96P彩图注音版，环保纸张，内容精粹，善于发现，注重细节，培养想象能力和创意力的童话故事。【送运费险】',
    //)
    public function get_quan_info($id)
    {
        $this->load->model("service/{$this->service}/query_model");
        $res = $this->query_model->get_quan_info($id);
        return $res;
    }
}