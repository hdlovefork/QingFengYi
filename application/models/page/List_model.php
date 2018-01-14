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

    public function list_nav(){
        $this->load->model("service/{$this->service}/category_model");
        $res = $this->category_model->list_nav();
        return $res;
    }
}