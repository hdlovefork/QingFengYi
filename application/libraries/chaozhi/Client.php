<?php
/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2018-01-14
 * Time: 0:52
 */

namespace libraries\chaozhi;

use libraries\huoxing\Client as HX_Client;


class Client extends HX_Client
{
    protected $reqURI='http://tool.chaozhi.hk/api/tb/ulandPrivilege.php';
}