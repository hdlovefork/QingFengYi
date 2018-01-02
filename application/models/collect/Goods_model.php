<?php

/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2017-12-20
 * Time: 11:04
 */
require_once('Base_collect.php');

class Goods_model extends Base_collect
{
    protected $table = 'goods';

    function transform_data($res)
    {
        $data = json_decode($res, TRUE);
        return $data['result'];
    }

    function persistent($formatted)
    {
        //查出现有数据库的淘宝宝贝ID，以便插入时跳过
        $db_entries = $this->db->select('id,tb_id')->get($this->table)->result_array();
        //待批量插入到数据库的记录集这是一个二维数组
        //每个元素代表一个goods
        $entries = [];
        foreach ($formatted as $goods) {
            if($this->_exist($goods['GoodsID'], $db_entries))
                continue;
            $entry = [];
            //提取每条记录生成二维数组，以备插入数据库
            foreach ($this->field_maps as $key => $value) {
                $entry[$key]=$goods[$value];
            }
            if (!empty($entry))
                $entries[] = $entry;
        }
        //插入数据库返回成功插入的行数
        if (!empty($entries))
            return $this->db->insert_batch($this->table, $entries);
        return 0;
    }

    /**
     * 检查数据库中是否已经存在tb_id的记录
     * @param $tb_id 淘宝商品id
     * @param $db_entries 数据库所有记录集
     * @return bool 存在返回TRUE，否则为FALSE
     */
    private function _exist($tb_id, $db_entries)
    {
        if(empty($tb_id)) return FALSE;
        foreach ($db_entries as $db_goods) {
            if ($db_goods['tb_id'] && $db_goods['tb_id'] === $tb_id)
                return TRUE;
        }
        return FALSE;
    }

    //wx_goods表字段与服务器返回JSON键的对应关系
    protected $field_maps = [
        'tb_id' => 'GoodsID',
        'goods_id' => 'ID',
        'title' => 'Title',
        'd_title' => 'D_title',
        'pic' => 'Pic',
        'cid' => 'Cid',
        'org_price' => 'Org_Price',
        'price' => 'Price',
        'is_tmall' => 'IsTmall',
        'sales_num' => 'Sales_num',
        'seller_id' => 'SellerID',
        'commission_jihua' => 'Commission_jihua',
        'commission_queqiao' => 'Commission_queqiao',
        'jihua_link' => 'Jihua_link',
        'introduce' => 'Introduce',
        'quan_id' => 'Quan_id',
        'quan_price' => 'Quan_price',
        'quan_time' => 'Quan_time',
        'quan_surplus' => 'Quan_surplus',
        'quan_receive' => 'Quan_receive',
        'quan_link' => 'Quan_link',
        'quan_m_link' => 'Quan_m_link'
    ];
}