<?php
/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2018-01-14
 * Time: 23:00
 */
include_once 'Page_model.php';

/**
 * 商品列表页面
 * Class List_model
 */
class List_model extends Page_model
{
    protected $service = 'daishu';

    /**
     * 大白菜商品列表导航
     * @return mixed
     */
    public function dabaicai_list_nav(){
        $this->load->model("service/{$this->service}/category_model");
        $res = $this->category_model->dabaicai_list_nav();
        return $res;
    }

    /**
     * 获取大白菜某分类下的商品
     * @param $data ['page','cid']
     * @return mixed|null
     */
    public function dabaicai_goods($data){
        $this->load->model("service/{$this->service}/category_model");
        $res = $this->category_model->dabaicai_goods($data);
        return $res;
    }
}