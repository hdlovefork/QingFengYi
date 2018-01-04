<?php

/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2017-12-12
 * Time: 15:06
 */
class Home extends UC_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
    }

    public function index()
    {
        if($this->input->method()==='post'){
            $this->load->library('form_validation');
            if($this->form_validation->run()===FALSE){
                $this->view('home');
            }else{
                $this->load->model('db/app_model');
                if($this->app_model->update_key($this->auth->id,$this->input->post())){
                    //更新session中的app数据，否则前台还是显示空数据，因为前台读取的是session值
                    $app = $this->app_model->get_app($this->auth->app_key);
                    $this->auth->login($app);
                    $this->success('保存成功！');
                    redirect('uc');
                }else{
                    $this->error('保存失败！');
                    $this->view('home');
                }
            }
        }else{
            if (!$this->session->has_userdata('welcome')) {
                $this->success('欢迎您回来！');
                $this->session->set_userdata('welcome', TRUE);
            }
            $this->view('home');
        }
    }

    public function setpass(){
        if($this->input->method()==='post'){
            $this->load->library('form_validation');
            if($this->form_validation->run()===FALSE){
                $this->view('home');
            }else{
                $this->load->model('db/app_model');
                if($this->app_model->modify_pwd($this->auth->id,$this->input->post('pass'))){
                    $this->success('密码修改成功！');
                }else{
                    $this->error('密码修改失败！');
                }
                redirect('uc');
            }
        }else{
            redirect('uc');
        }
    }


    /**
     * 用户退出
     */
    public function logout()
    {
        $this->auth->logout('login');
    }
}