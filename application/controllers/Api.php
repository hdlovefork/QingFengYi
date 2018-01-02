<?php

/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2017-12-08
 * Time: 21:35
 */
class Api extends Api_Controller
{
    private $dataoke_key='bs3mmhfk2w';

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

    public function goods_get()
    {
        $this->form_validation->set_data($this->get());
        if ($this->form_validation->run() === FALSE) {
            throw new BaseException(array('error_msg' => $this->form_validation->error_string()));
        }
        $page = $this->get('page');
        $cache_key = strtr($this->uri->ruri_string(), '/', '_');
        if (!($res = $this->cache->get($cache_key))) {
            $res = curl_get("http://api.dataoke.com/index.php?r=Port/index&type=total&appkey={$this->dataoke_key}&v=2&page={$page}");
            $res = json_decode($res, true);
            $r = $this->cache->save($cache_key, $res, 60);
            log_message('debug', '缓存保存结果：' . $r);
        }
        $this->response($res);
    }

    public function qq_get()
    {
        $this->form_validation->set_data($this->get());
        if ($this->form_validation->run() === FALSE) {
            throw new BaseException(array('error_msg' => $this->form_validation->error_string()));
        }
        $page = $this->get('page');
        $cache_key = $this->uri->ruri_string();
        if (!$res = $this->cache->get($cache_key)) {
            $res = curl_get("http://api.dataoke.com/index.php?r=goodsLink/qq&type=qq_quan&appkey={$this->dataoke_key}&v=2&page={$page}");
            $res = json_decode($res, true);
            $this->cache->save($cache_key, $res, 20);
        }
        $this->response($res);
    }

    public function www_get()
    {
        $this->form_validation->set_data($this->get());
        if ($this->form_validation->run() === FALSE) {
            throw new BaseException(array('error_msg' => $this->form_validation->error_string()));
        }
        $page = $this->get('page');
        $cache_key = $this->uri->ruri_string();
        if (!$res = $this->cache->get($cache_key)) {
            $res = curl_get("http://api.dataoke.com/index.php?r=goodsLink/www&type=www_quan&appkey={$this->dataoke_key}&v=2&page={$page}");
            $res = json_decode($res, true);
            $this->cache->save($cache_key, $res, 20);
        }
        $this->response($res);
    }

    public function convert_get()
    {
        $this->load->helper('urlconvert');
        $goods_id = $this->get('num_id');
        $pid = "mm_32805119_40744564_164568086";
        $cookie = "t=cffce85203f8ab29e8f796e22511b64e; cna=HWaeEmEMciMCASoxYrEWWSAr; _umdata=2FB0BDB3C12E491DBC83196CDF6FCE01FE773ED01F17262EEC5E846A22A5691204D73340F5E6093CCD43AD3E795C914CC82E94AFE139CF3C4B371B031A3B3EAB; cookie2=16aefab1b412f68f6c4d9efb054fb35f; v=0; _tb_token_=77531113ef8ae; alimamapwag=TW96aWxsYS81LjAgKFdpbmRvd3MgTlQgMTAuMDsgV2luNjQ7IHg2NCkgQXBwbGVXZWJLaXQvNTM3LjM2IChLSFRNTCwgbGlrZSBHZWNrbykgQ2hyb21lLzYyLjAuMzIwMi45NCBTYWZhcmkvNTM3LjM2; cookie32=d2cfc00b42999947b511d47cc563abae; alimamapw=XQZaDkBRBwtAWmpUBgYGAgVVAlJUVAENUwdUAQBXBFEBVlVVBAQCUFJRAw%3D%3D; cookie31=MzI4MDUxMTksaGRsb3ZlZm9yayxoZGxvdmVmb3JrQDE2My5jb20sVEI%3D; login=V32FPkk%2Fw0dUvg%3D%3D; rurl=aHR0cHM6Ly9wdWIuYWxpbWFtYS5jb20v; isg=AkhIIzOJhkci8Or4y1T4yt_FGbaaWa2cv4A6KAL5k0O23ehHqgF8i97TKYNW";
        $this->response(convertTbkUrl($goods_id, $pid, $cookie));
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

    public function banners_get(){
        $this->load->model('service/daishu/banner_model');
        $res = $this->banner_model->get_all();
        $this->response($res);
    }

    public function home_get(){
        $this->load->model(['page/home_model']);
        $data = [
            'banners'=>$this->home_model->banners()
        ];
        $this->response($data);
    }

    public function search_get(){
        $this->load->model('service/search_model');
    }

    public function test_get(){
        echo $this->cache->file->save('id','xxxx');
        echo $this->cache->file->get('id');
//        $this->benchmark->mark('test_start');
//        for ($i = 0;$i<100000;$i++){
//            $this->cache->get('id');
//        }
//        echo 'test_execute_time: '.$this->benchmark->elapsed_time('test_start');

    }
}