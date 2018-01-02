<?php

/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2017-12-11
 * Time: 20:22
 */
class ResourceNotExistException extends BaseException
{
    public $http_code = 404;
    public $error_code = 10003;
    public $error_msg = '请求的资源不存在';
}