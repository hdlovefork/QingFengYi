<?php

/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2017-12-27
 * Time: 18:57
 */
include_once APPPATH . 'models/db/DB_model.php';

class Banner_model extends DB_model
{
    /**
     * 查询所有Banner
     * @return mixed
     */
    public function get_all(){
        return $this->db->select('dtk_id,tb_id,pic')->get(self::TABLE_BANNER)->result_array();
    }
}