<?php
namespace libraries\daotaoke\request;


/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2017-12-23
 * Time: 20:33
 */
class PaoLiangGet extends DataokeGet
{
    public function __construct($appkey)
    {
        parent::__construct($appkey);
        $this->params['type'] = 'paoliang';
    }
}