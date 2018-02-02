<?php

/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2017-12-08
 * Time: 21:35
 */
class Api extends Api_Controller
{
    public function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->helper('http');
        $this->load->driver('cache',
            array('adapter' => 'apc', 'backup' => 'file')
        );
        $this->load->config('wx');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '');
        $this->load->model(['db/user_model']);
    }

    /**
     * 颁发令牌，需要APPKEY、小程序code码
     * @throws ParameterException
     */
    public function token_user_post()
    {
        $this->form_validation->set_data($this->post());
        if ($this->form_validation->run() === FALSE) {
            throw new ParameterException(array('error_msg' => $this->form_validation->error_string()));
        }
        $token = $this->user_model->get_token(
            $this->head($this->config->item('rest_key_name')),
            $this->post('code'));
        $this->response(['token' => $token]);
    }

    /**
     * 验证令牌是否过期
     */
    public function token_verify_post()
    {
        $exist = $this->token->check($this->post('token'));
        $this->response(['is_valid' => !empty($exist)]);
    }

    /**
     * 获取首页banners
     */
    public function banners_get()
    {
        $this->load->model(['page/home_model']);
        $res = $this->home_model->banners();
        $this->response($res);
    }

    public function homeicons_get(){
        $this->load->model(['page/home_model']);
        $res = $this->home_model->homeicons();
        $this->response($res);
    }

    public function topics_get(){
        $this->load->model(['page/home_model']);
        $res = $this->home_model->topics();
        $this->response($res);
    }

    /**
     * 搜索商品
     * @param $keyword string 商品名称
     */
    public function search_get($keyword)
    {
    }

    /**
     * 获取优惠券信息
     */
    public function quan_get()
    {
//        $this->output->cache(10);
//        $this->benchmark->mark('quan_start');
        $this->load->model('page/detail_model');
        $res = $this->detail_model->get_quan_info($this->get());
        $this->response($res);
    }

    /**
     * 获取商品详情图片
     */
    public function pics_get()
    {
//        $this->output->cache(10);
        $this->load->model('page/detail_model');
        $res = $this->detail_model->get_detail_pics($this->get());
        $this->response($res);
    }

    /**
     * 获取淘口令
     * $data array ['activity','tbid']
     */
    public function tkl_post()
    {
        $this->load->model('page/detail_model');
        $this->load->library('token');
        //读取当前用户所属APP的PID
        $data = $this->post();
        $data['pid'] = $this->token->get_data('tb_pid');
        $res = $this->detail_model->get_tkl($data);
        $this->response($res);
    }

    /**
     * 获取评论数据
     * $data array ['tbid','page']
     * @return void
     */
    public function rate_get()
    {
        $this->load->model('page/detail_model');
        $res = $this->detail_model->get_rate($this->get());
        $this->response($res);
    }

    /**
     * 获取活动页面数据
     * $data ['huodong']
     */
    public function huodong_get(){
        $this->load->model('page/home_model');
        $res = $this->home_model->huodong($this->get());
        $this->response($res);
    }

    /**
     * 更多优惠，返回一个目录列表
     */
    public function category_get(){
        $this->load->model('page/home_model');
        $res = $this->home_model->category();
        $this->response($res);
    }

    /**
     * 大白菜商品列表页顶部导航菜单
     */
    public function dabaicai_list_nav_get(){
        $this->load->model('page/list_model');
        $res = $this->list_model->dabaicai_list_nav();
        $this->response($res);
    }

    /**
     * 大白菜某分类下的商品
     */
    public function dabaicai_goods_get(){
        $this->load->model('page/list_model');
        $res = $this->list_model->dabaicai_goods($this->get());
        $this->response($res);
    }
}