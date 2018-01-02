<?php
namespace libraries\daotaoke\request;

use libraries\huoxing\request\Request;


/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2017-12-23
 * Time: 20:25
 */
class DataokeGet extends Request
{
    public function __construct($appkey)
    {
        $this->params['v'] = 2;
        $this->params['r'] = 'Port/index';
        $this->params['appkey'] = $appkey;
    }

    public function setPage($page)
    {
        $this->params['page'] = $page;
        return $this;
    }

}