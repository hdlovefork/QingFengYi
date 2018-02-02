<?php

/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2017-12-11
 * Time: 16:54
 */
class AppKeyException extends BaseException
{
    public $http_code = 403;
    public $error_code = 10002;
    public $error_msg  = '小程序不存在';
}