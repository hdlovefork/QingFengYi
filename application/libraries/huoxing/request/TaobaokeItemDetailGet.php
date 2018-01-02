<?php
namespace libraries\huoxing\request;

/**
 * 火星来客淘宝联盟超级搜索查询入口
 * 淘宝客商品查询
 * GET /api/item/detail 淘宝客商品详情
 * @haguo
 */

class TaobaokeItemDetailGet extends Request
{   
    /**
     * API接口名称
     */
    protected $method = '/api/item/detail';

	/**
     * 商品id[必须]
     */
    public function setId($value)
    {
        $this->params['itemId'] = $value;
        return $this;
    }

}