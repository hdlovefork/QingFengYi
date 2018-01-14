<?php
/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2018-01-14
 * Time: 0:57
 */

namespace libraries\chaozhi\request;


use libraries\huoxing\request\Request;

class GaoYongGet extends Request
{
    protected $submitMethod = 'POST';

    public function setPid($pid)
    {
        $pid=strtr($pid, '_', ' ');
        $n = sscanf($pid, 'mm %s %s %s', $user_id, $site_id, $adzone_id);
        if ($n === 3) {
            $this->params['site_id'] = $site_id;
            $this->params['adzone_id'] = $adzone_id;
        }
        return $this;
    }

    /**
     * 授权访问令牌
     * @param $sessionKey
     * @return GaoYongGet
     */
    public function setToken($sessionKey)
    {
        $this->params['key'] = $sessionKey;
        return $this;
    }


    public function setTbID($tbid)
    {
        $this->params['id'] = $tbid;
        return $this;
    }
}