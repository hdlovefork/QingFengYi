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
}