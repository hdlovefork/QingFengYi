<?php
/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2018-01-04
 * Time: 19:30
 */

include_once 'Dataoke_model.php';

class Query_model extends Dataoke_model
{
    public function get_quan_info($id)
    {
        $request = new \libraries\daotaoke\request\SingleGoodsGet($this->key);
        $request->setId($id);
        $res = $this->client->execute($request);
        if (!$res || !$res['result']) return null;
        $goods = $res['result'];
        $data = array(
            'title' => $goods['Title'],
            'quanhou' => round($goods['Price'], 2),
            'yuanjia' => round($goods['Org_Price'], 2),
            'quan' => round($goods['Quan_price'], 2),
            'activity' => $goods['Quan_id'],
            'taoid' => $goods['GoodsID'],
            'xiaoliang' => $goods['Sales_num'],
            'mp4' => '',
            'jie' => $goods['Introduce']
        );
        return $data;
    }


}