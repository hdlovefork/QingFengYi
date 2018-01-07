<?php

/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2017-12-11
 * Time: 17:00
 */
class WeChatException extends BaseException
{
    public $http_code = 400;
    public $error_msg = '微信服务器接口调用失败';
    public $error_code = 999;
}