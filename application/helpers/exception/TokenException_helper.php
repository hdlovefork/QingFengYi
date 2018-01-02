<?php

/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2017-12-11
 * Time: 11:08
 */
class TokenException extends BaseException
{
    public $http_code = 401;
    public $error_code = 10001;
    public $error_msg = 'Token已过期或无效Token';
}