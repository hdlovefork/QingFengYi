<?php

/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2017-12-08
 * Time: 22:17
 */
class BaseException extends Exception
{
    public $http_code = 400;
    public $error_code = 10000;
    public $error_msg = '参数错误';

    /**
     * BaseException constructor.
     * @param array|null $params
     */
    public function __construct($params = array())
    {
        if (array_key_exists('http_code', $params))
            $this->http_code = $params['http_code'];

        if (array_key_exists('error_code', $params))
            $this->error_code = $params['error_code'];

        if (array_key_exists('error_msg', $params))
            $this->error_msg = $params['error_msg'];
    }


}