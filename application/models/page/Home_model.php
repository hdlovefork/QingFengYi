<?php
/**
 * 提供主页的数据
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2017-12-28
 * Time: 19:45
 */
require_once ('Page_model.php');

class Home_model extends Page_model
{
    protected $service = 'daishu';

    public function banners(){
        $this->load->model("service/{$this->service}/banner_model");
        $res = $this->banner_model->get_all();
        return $res;
    }

    public function homeicons(){
        $this->load->model("service/{$this->service}/homeicon_model");
        $res = $this->homeicon_model->get_all();
        return $res;
    }

    public function topics(){
        $this->load->model("service/{$this->service}/topic_model");
        $res = $this->topic_model->get_all();
        return $res;
    }


    public function huodong($data)
    {
        $this->load->model("service/{$this->service}/huodong_model");
        $res = $this->huodong_model->get($data);
        return $res;
    }


    public function topic($data){
        $this->load->model("service/{$this->service}/topic_model");
        $res = $this->topic_model->get($data);
        return $res;
    }

    /**
     * 更多优惠，返回一个目录列表
     */
    public function category(){
        $this->load->model("service/{$this->service}/category_model");
        $res = $this->category_model->get_all();
        return $res;
    }
}