<?php

/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2017-12-11
 * Time: 17:40
 */
class ParameterException extends BaseException
{
    public $http_code = 400;
    public $error_msg = '参数错误';
    public $error_code = 10000;
}