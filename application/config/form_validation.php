<?php
/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2017-12-09
 * Time: 21:13
 */

$config = array(
    /**
     * API控制器验证
     */
    'api/goods' => array(
        array(
            'field' => 'page',
            'label' => '页数',
            'rules' => 'required|greater_than[0]',
        ),
    ),
    'api/qq' => array(
        array(
            'field' => 'page',
            'label' => '页数',
            'rules' => 'required|greater_than[0]',
        ),
    ),
    'api/www' => array(
        array(
            'field' => 'page',
            'label' => '页数',
            'rules' => 'required|greater_than[0]',
        ),
    ),
    'api/token_user' => array(
        array(
            'field' => 'code',
            'label' => 'code',
            'rules' => 'required',
        ),
    ),
    'api/token_verify' => array(
        array(
            'field' => 'code',
            'label' => 'code',
            'rules' => 'required',
        ),
    ),
    /**
     * 前台验证器
     */
    //Guest_Controller
    //用户注册
    'home/register' => array(
        array(
            'field' => 'email',
            'label' => '电子邮箱',
            'rules' => array(
                'required', 'valid_email',
                array(
                    'valid_email_session',
                    function ($value) {
                        //用户输入的验证码与session变量中的值进行比较
                        $CI = &get_instance();
                        $CI->load->library(['session', 'form_validation']);
                        if (strtolower($CI->session->tempdata('email')) !== strtolower($value)) {
                            $CI->form_validation->set_message('valid_email_session', '{field}与收件地址不符');
                            return FALSE;
                        }
                        return TRUE;
                    }
                )
            )
        ),
        array(
            'field' => 'pass',
            'label' => '密码',
            'rules' => 'required|min_length[6]|max_length[20]'
        ),
        array(
            'field' => 'vercode',
            'label' => '验证码',
            'rules' => array(
                'required', 'exact_length[4]',
                array(
                    'check_vercode',//验证规则名，用于设置错误消息模板时使用
                    function ($value) {
                        $CI = &get_instance();
                        $CI->load->library(['session', 'form_validation']);
                        //用户输入的验证码与session变量中的值进行比较
                        $result = !empty($value) && strtolower($CI->session->tempdata('vercode')) === strtolower($value);
                        $msg = $result ? '' : '{field} 不正确';
                        //如果验证失败需要设置错误提示消息
                        $result OR $CI->form_validation->set_message('check_vercode', $msg);
                        return $result;//返回验证结果：真为通过验证，否则为假
                    }
                )
            )
        )
    ),
    //用户登录
    'home/login' => array(
        array(
            'field' => 'email',
            'label' => '电子邮箱',
            'rules' => array(
                'required', 'valid_email'
            )
        ),
        array(
            'field' => 'pass',
            'label' => '密码',
            'rules' => 'required|min_length[6]|max_length[20]'
        ),
        array(
            'field' => 'vercode',
            'label' => '验证码',
            'rules' => array(
                'required', 'exact_length[4]',
                array(
                    'check_vercode',//验证规则名，用于设置错误消息模板时使用
                    function ($value) {
                        $CI = &get_instance();
                        $CI->load->library(['session', 'form_validation']);
                        //用户输入的验证码与session变量中的值进行比较
                        $result = !empty($value) && strtolower($CI->session->tempdata('vercode')) === strtolower($value);
                        $msg = $result ? '' : '{field}不正确';
                        //如果验证失败需要设置错误提示消息
                        $result OR $CI->form_validation->set_message('check_vercode', $msg);
                        return $result;//返回验证结果：真为通过验证，否则为假
                    }
                )
            )
        )
    ),
    //用于忘记密码
    'home/forget' => array(
        array(
            'field' => 'email',
            'label' => '电子邮箱',
            'rules' => 'required|valid_email',
        ),
        array(
            'field' => 'vercode',
            'label' => '验证码',
            'rules' => array(
                'required', 'exact_length[4]',
                array(
                    'check_vercode',//验证规则名，用于设置错误消息模板时使用
                    function ($value) {
                        $CI = &get_instance();
                        $CI->load->library(['session', 'form_validation']);
                        //用户输入的验证码与session变量中的值进行比较
                        $result = !empty($value) && strtolower($CI->session->tempdata('vercode')) === strtolower($value);
                        $msg = $result ? '' : '{field}不正确';
                        //如果验证失败需要设置错误提示消息
                        $result OR $CI->form_validation->set_message('check_vercode', $msg);
                        return $result;//返回验证结果：真为通过验证，否则为假
                    }
                )
            )
        )
    ),
    'home/reset_pass' => array(
        array(
            'field' => 'pass',
            'label' => '密码',
            'rules' => 'required|min_length[6]|max_length[20]',
        ),
        array(
            'field' => 'repass',
            'label' => '密码',
            'rules' => array(
                'required', 'min_length[6]', 'max_length[20]',
                array(
                    'check_repass',
                    function ($repass) {
                        $CI =& get_instance();
                        $pass = $CI->input->post('pass');
                        if ($repass !== $pass) {
                            $CI->form_validation->set_message('check_repass', '两次输入密码不一致！');
                            return FALSE;
                        }
                        return TRUE;
                    }
                )
            )
        ),
    ),
    //用于User_Controller->check_email
    //User_Controller->vercode
    'group_email' => array(
        array(
            'field' => 'email',
            'label' => '电子邮箱',
            'rules' => 'required|valid_email',
        ),
    ),
    /**
     * 用户中心
     */
    //UC_Controller
    //API设置
    'uc/home/index' => array(
        array(
            'field' => 'wxkey',
            'label' => '微信APPKEY',
            'rules' => 'required|min_length[10]',
        ),
        array(
            'field' => 'wxsecret',
            'label' => '微信SECRET',
            'rules' => 'required|min_length[10]',
        ),
        array(
            'field' => 'tbpid',
            'label' => '淘宝PID',
            'rules' => array(
                'required', 'min_length[10]',
                array(
                    'check_tbpid',
                    function ($value) {
                        if (!preg_match('/^mm_\d+_\d+_\d+$/', $value)) {
                            $CI = & get_instance();
                            $CI->form_validation->set_message('check_tbpid', '{field}格式不正确');
                            return FALSE;
                        }
                        return TRUE;
                    }
                ),
            ),
        ),
        array(
            'field' => 'dtkpid',
            'label' => '大淘客APIKEY',
            'rules' => 'required|min_length[10]',
        ),
    ),
    //重新设置密码
    'uc/home/setpass' => array(
        array(
            'field' => 'pass',
            'label' => '密码',
            'rules' => 'required|min_length[6]|max_length[20]'
        ),
        array(
            'field' => 'pass',
            'label' => '密码',
            'rules' => array(
                'required','min_length[6]','max_length[20]',
               array(
                   'check_pass',
                   function($value){
                       $CI=& get_instance();
                       $pass = $CI->input->post('pass');
                       if($pass!==$value){
                           $CI->form_validation->set_message('check_pass','两次输入的密码不一致！');
                           return FALSE;
                       }
                       return TRUE;
                   }
               )
            )
        ),
    ),
);