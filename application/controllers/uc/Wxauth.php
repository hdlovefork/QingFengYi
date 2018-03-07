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
        $this->load->library(['weixin/weixinapi', 'taogy']);
    }

    public function index($tab = 'wx')
    {
        $wx_auth_text = '我要授权';
        $gy_auth_text = '高佣授权';
        $gy_auth_url = $this->taogy->get_auth_url();
        $gy_expire_in = '';
        //session中读出json格式的授权信息
        $auth_info = $this->auth->get('wx_authorizer_access_token');
        $tb_access_token_json = $this->auth->get('tb_access_token');

        //已经授权过了显示小程序授权信息
        if ($auth_info) {
            $auth_arr = json_to_array($auth_info);
            if ($auth_arr['authorizer_access_token'] && $auth_arr['authorizer_refresh_token']) {
                $wx_auth_text = '重新授权';
                $wx_auth_info = $this->weixinapi->get_auth_info($auth_arr['authorizer_appid']);
            }
        }
        //淘宝授权信息
        if ($tb_access_token_json) {
            $tb_access_token_arr = json_to_array($tb_access_token_json);
            if ($tb_access_token_arr) {
                $gy_auth_text = '重新授权';
                $gy_expire_in = date('Y-m-d H:i:s', $tb_access_token_arr['expire_time']);
            }
        }
        $this->view('wxauth',
            [
                'wx_auth_text' => $wx_auth_text,
                'gy_auth_url' => $gy_auth_url,
                'xcx_info' => $wx_auth_info['authorizer_info'],
                'gy_auth_text' => $gy_auth_text,
                'gy_expire_in' => $gy_expire_in,
                'qrcode_url'=>'',
                //当前显示的TAB项，包含：wx,tb两项
                'tab' => $tab,
            ]);
    }

    /**
     * AJAX获取微信授权地址
     */
    public function get_wx_auth_url()
    {
        $url = $wx_auth_url = $this->weixinapi->get_auth_url(base_url('uc/wxauth/authcallback'));
        $res['success'] = $url ? TRUE : FALSE;
        $res['error_msg'] = $url ? '' : '获取授权地址失败，请重试！';
        $res['wx_auth_url'] = $url ?: '';
        json($res);
    }

    /**
     * 保存淘客授权令牌
     */
    public function tb_access_token()
    {
        if ($this->input->method() === 'post') {
            $tb_access_token_str = trim($this->input->post('tb_access_token'));
            $tb_access_token_str = urldecode($tb_access_token_str);
            $tb_access_token_arr = json_to_array($tb_access_token_str);
            if (!$tb_access_token_arr) {
                $this->error('输入有误，请重试！');
            } else {
                //将淘宝授权访问令牌保存到数据库
                $this->load->model('db/app_model');
                $tb_access_token_arr['expire_time'] = $tb_access_token_arr['expires_in'] + mktime();
                $this->app_model->update_tb_token($this->auth->get('id'), json_encode($tb_access_token_arr));
                $this->refresh_app_info();
                $this->success('保存成功！');
            }

        }
        redirect('uc/wxauth/index/tb');
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
        $error_msg = '';
        try {
            // 1：判断授权APPID是否与当前登录用户的APPID相符
            if ($this->auth->get('wx_key') !== $authorizer_appid) {
                throw new Exception('授权失败！与基本信息中填写的小程序不符');
            }
            // 2：判断是否已经授予指定的权限
            $allow_permission = $this->isAllowPermission($auth_info);
            if (!$allow_permission) {
                throw new Exception('没有授予正确的权限，无法正常使用！');
            }
            $authorizer_access_token = $auth_info['authorizer_access_token'];
            // 3：代设置小程序域名
            if (!$this->weixinapi->set_domain($authorizer_access_token)) {
                throw new Exception('授权失败！设置域名时出错！');
            }
            // 4：代上传小程序代码
            $app_cur_version = $this->config->item('app_cur_version');
            $app_cur_desc = $this->config->item('app_cur_desc');
            $app_cur_temp_id = $this->config->item('app_cur_temp_id');
            $ext_json_arr['extAppid'] = $authorizer_appid;
            $ext_json_arr['ext']['appid'] = $this->auth->get('id');
            $ok = $this->weixinapi->commit_code($authorizer_access_token, $app_cur_temp_id, $ext_json_arr, $app_cur_version, $app_cur_desc);
            if(!$ok){
                throw new Exception('部署代码时出错！');
            }
            // 5：获取小程序体验二维码
            $qrcode_img_url =$this->weixinapi->get_qrcode_url($authorizer_access_token);
            $qrcode_img = file_get_contents($qrcode_img_url);
            if(!file_put_contents("./images/qrcode/{$authorizer_appid}.jpg", $qrcode_img)){
                throw new Exception('获取体验二维码失败！');
            }
            // 6：更新当前用户的登录信息
            $this->load->model('db/app_model');
            $update_data['authorizer_access_token'] = $auth_info['authorizer_access_token'];
            $update_data['authorizer_refresh_token'] = $auth_info['authorizer_refresh_token'];
            $update_data['authorizer_appid'] = $auth_info['authorizer_appid'];
            //缩短过期时间让它减少10分钟
            $update_data['expires_in'] = mktime() + $auth_info['expires_in'] - 60 * 10;
            $app_id = $this->auth->get('id');
            if ($this->app_model->update_token($app_id, $update_data)) {
                //重新从数据库读取当前APP的信息，便于uc/wxauth/index页面显示小程序授权信息
                $this->refresh_app_info();
            }else{
                throw new Exception('更新数据失败！');
            }
        } catch (Exception $e) {
            $error_msg = $e->getMessage();
        }
        if ($error_msg) {
            $this->error($error_msg);
        }else{
            $this->success('授权成功！');
        }
        redirect('uc/wxauth');
    }

    /**
     * 判断微信已授权的权限是否完整
     * @param $wx_auth_info
     * @return bool
     */
    protected function isAllowPermission($wx_auth_info)
    {
        // 判断是否有调用小程序API的权限
        $func_info = $wx_auth_info['func_info'];
        // 默认具有权限
        $allow_permission = TRUE;
        // 需要的权限
        $need_permission = [18];
        if(!is_array($func_info)) return FALSE;
        foreach ($func_info as $value) {
            foreach ($value['funcscope_category'] as $perm) {
                $allow = in_array($perm, $need_permission);
                if (!$allow) {
                    //没有足够的权限调用
                    $allow_permission = FALSE;
                    break 2;
                }
            }
        }
        return $allow_permission;
    }

}