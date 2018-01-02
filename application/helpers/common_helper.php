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
function json($data = []){
    $CI = &get_instance();
    $CI->output->set_status_header(200)
        ->set_content_type('application/json', strtolower(config_item('charset')))
        ->set_output(json_encode($data))
        ->_display();
    exit;
}

/**
 * 返回flash_session中的成功、错误消息
 * @return string
 */
function show_message(){
    $CI = & get_instance();
    $CI->load->library('session');
    $html=<<<EOT
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