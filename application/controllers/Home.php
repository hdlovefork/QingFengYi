<?php

use Gregwar\Captcha\CaptchaBuilder;

/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2017-12-13
 * Time: 11:32
 */
class Home extends Front_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
    }

    public function index()
    {
        $this->view('home');
    }

    public function demo()
    {
        $this->view('demo');
    }

    public function register()
    {
        $this->load->library('auth');
        if ($this->auth->check_auth()) {
            redirect('uc');
        }
        if ($this->input->method() === 'post') {
            $this->load->library(['form_validation', 'session', 'inputex']);
            if ($this->form_validation->run() === FALSE) {
                $this->view('register');
            } else {
                //注册APP向数据库插入记录同时自动生成APP_KEY
                $this->load->model('db/app_model');
                $res = $this->app_model->add_app($this->inputex->post('email'), $this->inputex->post('password'));
                if ($res !== FALSE) {
                    $this->success('注册成功！');
                    redirect('login');
                } else {
                    $this->error('注册失败！');
                    redirect('reg');
                }
            }
        } else {
            $this->view('register');
        }
    }

    public function login()
    {
        $this->load->library('auth');
        if ($this->auth->check_auth()) {
            redirect('uc');
        }
        if ($this->input->method() === 'post') {
            $this->load->library(['form_validation']);
            if ($this->form_validation->run() === FALSE) {
                $this->view('login');
            } else {
                $this->load->model('db/app_model');
                if (!($app = $this->app_model->check_email_pwd($this->input->post('email'), $this->input->post('pass')))) {
                    $app === NULL ? $this->error('邮箱不存在') : $this->error('密码不正确');
                    $this->view('login');
                } else {
                    //APP没启用不允许登录
                    if (!$app['enable']) {
                        $this->error('请联系客服开通');
                        redirect('login');
                    } else {
                        //登录成功，保存到session变量中
                        $this->load->library(['auth', 'session']);
                        $this->auth->login($app);
                        $this->success('欢迎您回来！');
                        session_commit();
                        redirect('uc');
                    }
                }
            }
        } else {
            $this->view('login');
        }
    }

    /**
     * 发送邮件验证码，这是来自于前端的请求
     */
    public function vercode()
    {
        $this->load->library(['inputex', 'form_validation']);
        $this->load->helper('common');//加载JSON函数
        $this->load->library('session');

        if ($this->session->has_userdata('vercode')) {
            json(['success' => FALSE]);
        }

        $this->form_validation->set_data($this->inputex->post());
        if ($this->form_validation->run('group_email') === FALSE) {
            json(['success' => false, 'error_msg' => form_error('email')]);
        }
        //验证邮箱地址是否有效
        $this->load->helper('string');
        //随机生成4位验证码
        $code = random_string('nozero', 4);
        //将验证码和邮箱地址临时存储在SESSION中，有效期60秒
        $this->session->set_tempdata('vercode', $code, 60);
        $this->session->set_tempdata('email', $this->inputex->post('email'));
        session_commit();

        //下面一行是用于调试，将code码直接返回到页面
//        json(['success' => TRUE, 'code' => $code]);
        //发送验证邮件到用户邮箱
        $subject = '清风易邮箱验证';
        $content = "您的验证码为：{$code}";
        if ($this->_send_email($this->inputex->post('email'), $subject, $content) === TRUE) {
            json(['success' => TRUE]);
        }
        json(['success' => FALSE]);
    }

    /**
     * 解密链接中的邮箱、密码、过期时间戳
     * @param string $url
     */
    public function reset_email($url = '')
    {
        if ($url && strlen($url) > 100) {
            //解密邮件链接地址中的 格式："邮箱|密码|过期时间戳"
            $this->load->library('encryption');
            $url = strtr($url, '~', '=');
            $url = base64_decode($url);
            $url = $this->encryption->decrypt($url);
            //校验数据完整性
            if (strpos($url, '|') !== FALSE
                && (($app = explode('|', $url)) && count($app) === 3)
            ) {
                list($email, $password, $time) = $app;
                //链接是否超时
                if (mktime() <= $time) {
                    $this->load->model('db/app_model');
                    //数据库是否有记录
                    $app = $this->app_model->check_email_pwd($email, $password, TRUE);
                    if ($app) {
                        //邮箱密码匹配成功
                        $this->load->library('session');
                        //将appid保存至$_SESSION['reset']，有效期10分钟
                        $this->session->set_tempdata('reset', $app['id'], 120);
                        session_commit();
                        redirect('resetpass');
                    }
                }
            }
        }
        $this->error('链接不正确或已失效！');
        redirect('forget');
    }

    /**
     * 设置新密码
     */
    public function reset_pass()
    {
        $this->load->library('session');
        $app_id = $this->session->tempdata('reset');
        if ($app_id) {
            if ($this->input->method() === 'post') {
                $this->load->library('form_validation');
                if ($this->form_validation->run() === FALSE) {
                    //增加120秒过期时间
                    $this->session->mark_as_temp('reset', 900);
                    $this->view('resetpass');
                } else {
                    $this->load->model('db/app_model');
                    //向数据库写入新密码
                    if ($this->app_model->modify_pwd($app_id, $this->input->post('pass'))) {
                        $this->success('密码修改成功！');
                        redirect('login');
                    } else {
                        $this->error('密码修改失败！');
                        $this->view('resetpass');
                    }
                }
            } //允许重置密码显示重置密码页面
            else {
                $this->view('resetpass');
            }
        } else {
            $this->error('您无权操作！');
            redirect('login');
        }
    }

    /**
     * 忘记密码
     */
    public function forget()
    {
        $this->load->library('auth');
        if ($this->auth->check_auth()) {
            redirect('uc');
        }
        if ($this->input->method() === "post") {
            $this->load->library(['form_validation']);
            if ($this->form_validation->run() === FALSE) {
                $this->view('forget');
            } else {
                //检查邮箱是否存在
                $this->load->model('db/app_model');
                $app = $this->app_model->get_by_email($this->input->post('email'));
                if (!$app) {
                    $this->error('邮箱不存在');
                    $this->view('forget');
                } else {
                    //发送重置密码邮件，格式："邮箱|密码|过期时间戳"
                    $url = $app['email'] . '|' . $app['password'] . '|' . (mktime() + 600);
                    $this->load->library('encryption');
                    $en = $this->encryption->encrypt($url);
                    //CI不允许base64编码中的=号，所以将=替换成~
                    $en = strtr(base64_encode($en), '=', '~');
                    $url = is_https() ? 'https:' : 'http:' . base_url("resetemail/$en");
                    if ($this->_send_email($app['email'], $this->config->item('app_name') . '密码重置', "点击链接重置密码：{$url}，有效期10分钟")) {
                        $this->success('请查收邮件');
                    } else {
                        $this->error('邮件发送失败~');
                    }
                    $this->view('forget');
                }
            }
        } else {
            $this->view('forget');
        }
    }

    /**
     * 验证邮箱是否有效，这是来自前端的AJAX请求，以JSON格式返回数据
     */
    public
    function check_email()
    {
        $this->load->helper('common');
        $this->load->library(['form_validation', 'inputex']);
        //使用自定义类库inputex才能获取到POST的JSON数据
        $this->form_validation->set_data($this->inputex->post());
        if ($this->form_validation->run('group_email') === FALSE) {
            $this->form_validation->set_error_delimiters('', '');
            json([
                'success' => false,
                'email' => $this->inputex->post('email'),
                'error_msg' => form_error('email')
            ]);
        }
        $this->load->model('db/app_model');
        //检查邮箱是否被使用
        $app = $this->app_model->get_by_email($this->inputex->post('email'));
        json([
            'exist' => !empty($app),
            'success' => true,
            'email' => $this->inputex->post('email'),
        ]);
    }

    /**
     * 发送邮件
     * @param $to
     * @param $subject
     * @param $content
     * @return bool 成功返回TRUE，失败返回FALSE
     */
    private
    function _send_email($to, $subject, $content)
    {
        //加载内置邮件类
        $this->load->library('email');
        //读取邮箱配置
        $this->load->config('email');
        $this->email->from($this->config->item('smtp_user'), '清风易');
        $this->email->to($to);
        //设置邮件主题和内容
        $this->email->subject($subject);
        $this->email->message($content);

        if ($this->email->send()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * 输出验证码图片
     */
    public function captcha()
    {
        $this->load->helper('string');
        //生成4位随机数字
        $vercode = random_string('nozero', 4);
        $this->load->library('session');
        //将随机数写入SESSION
        $this->session->set_tempdata('vercode', $vercode, 120);
        session_commit();
        //生成验证码图片
        $builder = new CaptchaBuilder($vercode);
        $builder->build();
        header('Content-type: image/jpeg');
        $builder->output();
    }
}