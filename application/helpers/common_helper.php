<?php
/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2017-12-14
 * Time: 14:04
 */

/**
 * JSON格式回复并终止程序运行
 * @param array $data 数组数据
 */
function json($data = [])
{
    $CI = &get_instance();
    $CI->output->set_status_header(200)
        ->set_content_type('application/json', strtolower(config_item('charset')))
        ->set_output(json_encode($data))
        ->_display();
    exit;
}

/**
 * 在模板文件中使用，返回flash_session中的成功、错误消息
 * @return string
 */
function show_message()
{
    $CI = &get_instance();
    $CI->load->library('session');
    $html = <<<EOT
layui.use(['layer'],function(layer){
    var msg={};
    msg.errorMsg = '{$CI->session->flashdata('error_msg')}';
    msg.successMsg = '{$CI->session->flashdata('success_msg')}';
    msg.errorMsg  && layer && layer.msg(msg.errorMsg );
    msg.successMsg && layer && layer.msg(msg.successMsg);
});
EOT;
    return $html;
}

function json_to_array($json)
{
    $res = json_decode($json, TRUE);
    if ($res === NULL) {
        //强制返回NULL，用于404状态码
        $json = format_ErrorJson($json);
        $res = json_decode($json, TRUE);
        if ($res === FALSE)
            return NULL;
    }
    return $res;
}

function format_ErrorJson($checkLogin)
{
    for ($i = 0; $i <= 31; ++$i) {
        $checkLogin = str_replace(chr($i), '', $checkLogin);
    }
    $checkLogin = str_replace(chr(127), '', $checkLogin);

// This is the most common part
// Some file begins with 'efbbbf' to mark the beginning of the file. (binary level)
// here we detect it and we remove it, basically it's the first 3 characters
    if (0 === strpos(bin2hex($checkLogin), 'efbbbf')) {
        $checkLogin = substr($checkLogin, 3);
    }
    return $checkLogin;
}

function convertToUTF8($data)
{
    if (!empty($data)) {
        $fileType = mb_detect_encoding($data, array('UTF-8', 'GBK', 'LATIN1', 'BIG5'));
        if ($fileType != 'UTF-8') {
            $data = mb_convert_encoding($data, 'utf-8', $fileType);
        }
    }
    return $data;
}