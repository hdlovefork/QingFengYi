<?php
namespace libraries\daotaoke\request;

/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2017-12-23
 * Time: 20:36
 */
class SingleGoodsGet extends DataokeGet
{
    public function setId($id){
        $this->params['id']=$id;
        return $this;
    }
}