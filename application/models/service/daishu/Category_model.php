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
     * 商品列表导航
     */
    public function list_nav(){
        $json = $this->load->view('data/list_nav.json',NULL,TRUE);
        return $this->json_to_array($json);
    }
}