<?php
/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2018-01-18
 * Time: 23:11
 */

class Wxauth extends UC_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('weixin/weixinapi');
    }

    public function index()
    {
        $this->load->library('taogy');
        $wx_auth_text = '我要授权';
        $gy_auth_text = '高佣授权';
        $gy_auth_url = $this->taogy->get_auth_url();
        //session中读出json格式的授权信息
        $auth_info = $this->auth->get('wx_authorizer_access_token');
        //已经授权过了显示小程序授权信息
        if ($auth_info) {
            $auth_arr = json_to_array($auth_info);
            if ($auth_arr['authorizer_access_token'] && $auth_arr['authorizer_refresh_token']) {
                $wx_auth_text = '重新授权';
                $wx_auth_info = $this->weixinapi->get_auth_info($auth_arr['authorizer_appid']);
                //判断是否有调用小程序API的权限
                $func_info = $wx_auth_info['authorization_info']['func_info'];
                //默认具有权限
                $allow_permission = TRUE;
                //需要的权限
                $need_permission = [18];
                foreach ($func_info as $value) {
                    foreach ($value['funcscope_category'] as $perm) {
                        $allow = in_array($perm, $need_permission);
                        if(!$allow) {
                            //没有足够的权限调用
                            $allow_permission = FALSE;
                            break 2;
                        }
                    }
                }
                if(!$allow_permission){
                    $this->error('没有授予正确的权限，无法正常使用！');
                }
            }
        }
        //获取微信授权页面
        $wx_auth_url = $this->weixinapi->get_auth_url(base_url('uc/wxauth/authcallback'));
        $this->view('wxauth',
            [
                'wx_auth_url' => $wx_auth_url,
                'wx_auth_text' => $wx_auth_text,
                'xcx_info'=>$wx_auth_info['authorizer_info'],
                'gy_auth_url'=>$gy_auth_url,
                'gy_auth_text'=>$gy_auth_text
            ]);
    }

    public function tb_access_token(){
        echo 111;
    }

    //用户授权回调
    public function authcallback()
    {
        // 1：从回调链接的get参数中取得授权码
        $auth_code = $this->input->get('auth_code');
        // 2：拿授权码换取授权令牌
        $res = $this->weixinapi->get_auth_token($auth_code);
//        log_message('DEBUG', "授权回调返回数据\n" . var_export($res, TRUE));
//        log_message('DEBUG', "当前用户的wx_key--->{$this->auth->get('wx_key')}");
        $auth_info = $res['authorization_info'];
        $authorizer_appid = $auth_info['authorizer_appid'];

        //3：查询当前用户是否已经注册这个appid
        if ($this->auth->get('wx_key') !== $authorizer_appid) {
            $this->error('授权失败！与基本信息中填写的小程序不符');
        } else {
            //保存authorizer_access_token和authorizer_refresh_token到数据库
            $app_id = $this->auth->get('id');
            $this->load->model('db/app_model');
            $update_data['authorizer_access_token'] = $auth_info['authorizer_access_token'];
            $update_data['authorizer_refresh_token'] = $auth_info['authorizer_refresh_token'];
            $update_data['authorizer_appid'] = $auth_info['authorizer_appid'];
            //缩短过期时间让它减少10分钟
            $update_data['expires_in'] = mktime() + $auth_info['expires_in'] - 60 * 10;
            if ($this->app_model->update_token($app_id, $update_data)) {

                //重新从数据库读取当前APP的信息，便于uc/wxauth/index页面显示小程序授权信息
                $this->load->model('db/app_model');
                $user_info = $this->app_model->get_by_email($this->auth->get('email'));
                $this->auth->login($user_info);
                //设置第三方域名
                if($this->weixinapi->set_domain($auth_info['authorizer_access_token'])){
                    $this->success('授权成功！');
                }else{
                    $this->error('授权失败！设置域名时出错！');
                }
            } else
                $this->error('授权失败！');
        }
        redirect('uc/wxauth');
    }

}