<?php



require_once(APPPATH . 'libraries/REST_Controller.php');

//自动加载类
class ImportHelper
{

    public static function load($lib)
    {
        // log_message('DEBUG', "自动加载类：{$lib}");
        $filename = APPPATH . $lib . '.php';
        $filename = strtr($filename, '\\', DIRECTORY_SEPARATOR);
        if (file_exists($filename)) {
            // log_message('DEBUG', "成功加载：{$filename}");
            include $filename;
        }else{
            // log_message('DEBUG', "加载不成功：{$filename}");
        }
    }
}

spl_autoload_register(['ImportHelper', 'load'], FALSE, TRUE);

/**
 * 重写REST_Controller，捕获异常
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2017-12-08
 * Time: 21:36
 */
class Api_Controller extends REST_Controller
{
    public function __construct($config = 'rest')
    {
        parent::__construct($config);
        //导入异常类
        $this->load->helper([
            'exception/BaseException',
            'exception/TokenException',
            'exception/AppKeyException',
            'exception/WechatException',
            'exception/ParameterException',
            'exception/ResourceNotExistException',
        ]);
    }

    public function index_get()
    {
        throw new ResourceNotExistException();
    }

    public function index_post()
    {
        throw new ResourceNotExistException();
    }

    public function index_put()
    {
        throw new ResourceNotExistException();
    }

    public function index_delete()
    {
        throw new ResourceNotExistException();
    }

    public function index_patch()
    {
        throw new ResourceNotExistException();
    }

    public function index_options()
    {
        throw new ResourceNotExistException();
    }

    public function invoke($callback, $args_arr = [])
    {
        $error = NULL;
        $http_code = 0;
        try {
            call_user_func_array($callback, is_array($args_arr) ? $args_arr : []);
        } catch (BaseException $e) {
            $http_code = $e->http_code;
            $error = array(
                $this->config->item('rest_status_field_name') => FALSE,
                'error_code' => $e->error_code,
                $this->config->item('rest_message_field_name') => $e->error_msg
            );
        } catch (Exception $e) {
            //生产环境下生成错误码
            if (ENVIRONMENT !== 'development') {
                $this->load->helper('language');
                $http_code = 500;
                $error = array(
                    $this->config->item('rest_status_field_name') => FALSE,
                    'error_code' => 999,
                    $this->config->item('rest_message_field_name') => lang('text_rest_internal_error')
                );
                //将错误写入日志
                log_message('error', $e->getMessage());
            } //开发模式下直接抛出异常
            else {
                throw $e;
            }
        }
        //生产环境下返回错误代码
        if (isset($error) && $http_code > 0) {
            parent::response($error, $http_code);
        }
    }

    //重写父类方法验证权限
    protected function early_checks()
    {
        $this->invoke([$this, 'auth_check'], []);
    }

    /**
     * 基于token的权限验证
     * @throws TokenException
     */
    protected function auth_check()
    {
        //读取需要做权限验证的方法
        $auth_override_class_method = $this->config->item('auth_override_class_method');
        if (!empty($auth_override_class_method)
            && !empty($auth_override_class_method[$this->router->class][$this->router->method])) {
            if ($auth_override_class_method[$this->router->class][$this->router->method] === 'token') {
                //token已经在缓存中？
                $this->load->library('token');
                $exist = $this->token->check($this->head('Token'));
                if (!$exist) {
                    //token不在缓存中则终止程序
                    $this->load->helper([
                        'exception/baseexception',
                        'exception/tokenexception']);
                    throw new TokenException();
                }
            }
        }
    }

    // 捕获异常，需要将rest.php配置文件中$config['rest_handle_exceptions'] 的值设为 FALSE
    // REST_Controller才会抛出异常
    public function _remap($object_called, $arguments = [])
    {
        $this->invoke('parent::_remap', [$object_called, $arguments]);
    }

//    protected function _auth_override_check()
//    {
//        $pass = parent::_auth_override_check();
//        $auth_override_class_method = $this->config->item('auth_override_class_method');
//        if ($pass === FALSE) {
//            if (!empty($auth_override_class_method)
//                && !empty($auth_override_class_method[$this->router->class][$this->router->method])) {
//                if ($auth_override_class_method[$this->router->class][$this->router->method] === 'token') {
//                    return FALSE;
//                }
//            }
//
//        }
//    }


    // 没有错误发生的情况下重构回复的数据，加上error_code,error_msg字段
//    public function response($data = NULL, $http_code = NULL, $continue = FALSE)
//    {
//        //没有错误发生我们需要包装
//        if (isset($data['status']) && $data['status'] === FALSE) {
//            $res = $data;
//        } else {
//            $res['status'] = TRUE;
//            $res['data'] = $data;
//        }
//        parent::response($res, $http_code, $continue); // TODO: Change the autogenerated stub
//    }
}

class Base_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['url', 'common']);
        $this->load->config('app');
        if (ENVIRONMENT === 'development') {
            $this->output->enable_profiler(TRUE);
        }
    }

    /**
     * 设置数据到flash_session中
     * @param $key
     * @param $value
     * @internal param array $data
     */
    public function message($key, $value = NULL)
    {
        $this->load->library('session');
        $this->session->set_flashdata($key, $value);
    }

    /**
     * 设置错误消息(key=error_msg)到flash_session中
     * @param $error_msg
     */
    public function error($error_msg)
    {
        $this->message('error_msg', $error_msg);
    }

    /**
     * 设置成功消息(key=success_msg)到flash_session中
     * @param $success_msg
     */
    public function success($success_msg)
    {
        $this->message('success_msg', $success_msg);
    }
}

class Front_Controller extends Base_Controller
{
    public function view($view, $vars = array(), $return = FALSE)
    {
        $this->load->view('template/head');
        $this->load->view($view, $vars, $return);
        $this->load->view('template/foot');
    }
}

class UC_Controller extends Base_Controller
{
    /**
     * @var array 用户登录数据
     */
    protected $cert;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('auth');
        //判断用户是否已经登录
        if (!($this->cert = $this->auth->check_auth())) {
            //用户未登录
            redirect('login');
        }
    }

    public function view($view, $vars = array(), $return = FALSE)
    {
        $this->load->view('uc/template/head');
        $this->load->view('uc/template/sidebar');
        $this->load->view("uc/$view", $vars, $return);
        $this->load->view('uc/template/foot');
    }
}
