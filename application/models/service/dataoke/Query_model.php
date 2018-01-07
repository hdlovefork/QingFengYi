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
    public function get_quan_info($ids)
    {
        $request = new \libraries\daotaoke\request\SingleGoodsGet($this->dtk_key);
        $request->setId($ids['tbid']);
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

    /**
     * 调用淘宝API获取图片详情
     * @param $ids
     * @return null
     */
    public function get_detail_pics($ids)
    {
        $url = 'http://api.m.taobao.com/h5/mtop.taobao.detail.getdesc/6.0/?data=%s';
        $str = sprintf('{"id":"%s"}', $ids['tbid']);
        $url = sprintf($url, urlencode($str));
        $str = curl_get($url);
        $data = $this->json_to_array($str);
        $html_pics = $data['data']['pcDescContent'];
        $html_pics = stripslashes($html_pics);

        $preg = '/<img.*?src=[\"|\']?(.*?)[\"|\']?\s.*?>/i';
        preg_match_all($preg, $html_pics, $imgArr);
        if (count($imgArr) === 2) {
            return $this->add_protocol($imgArr[1]);
        }
        return null;
    }

}