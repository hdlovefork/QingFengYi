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
            array('adapter' => 'apc', 'backup' => 'file', 'key_prefix' => 'wx_')
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
        $this->load->model('service/daishu/banner_model');
        $res = $this->banner_model->get_all();
        $this->response($res);
    }

    /**
     * 首页数据
     */
    public function home_get()
    {
        //$this->output->cache(1);
        $this->load->model(['page/home_model']);
        $data = [
            'banners' => $this->home_model->banners() ?: $this->home_model->banners()
        ];
        $this->response($data);
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
    public function quan_post()
    {
//        $this->output->cache(10);
        $this->load->model('page/detail_model');
        $res = $this->detail_model->get_quan_info($this->post());
        $this->response($res);
    }

    /**
     * 获取商品详情图片
     */
    public function pics_post()
    {
//        $this->output->cache(10);
        $this->load->model('page/detail_model');
        $res = $this->detail_model->get_detail_pics($this->post());
        $this->response($res);
    }

    /**
     * 领券
     * @param $id string 宝贝ID
     */
    public function ling_get($id)
    {

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
    public function rate_post()
    {
        $this->load->model('page/detail_model');
        $res = $this->detail_model->get_rate($this->post());
        $this->response($res);
    }
}