<?php
namespace libraries\huoxing\request;

/**
 * 火星来客淘宝联盟超级搜索查询入口
 * 根据商品id查询优惠券信息
 * GET /api/search/coupon_item 根据商品id查询优惠券信息
 * @haguo
 */

class TaobaokeCouponItemListGet extends Request
{   
    /**
     * API接口名称
     */
    protected $method = '/api/search/coupon_item_list';

    /**
     * 设置需返回的字段列表[必须]
     * 参考：afterCouponPrice,afterCouponPrice,biz30day,clickUrl,couponPrice,currentPrice,freeShipping,itemId,pictUrl,provcity,sellerId,sellerNick,title,userType
     */
    public function setFields($value)
    {
        $this->params['fields'] = $value;
        return $this;
    }
	
	/**
     * 推广位[必须]
     */
    public function setPid($value)
    {
        $this->params['pid'] = $value;
        return $this;
    }

    /**
     * 查搜索关键字,[必须]
     */
    public function setQ($value)
    {
        $this->params['q'] = $value;
        return $this;
    }

    /**
     * 页码，可选，不传默认为1
     */
    public function setPageNo($value = 1)
    {
        $this->params['pageNum'] = $value;
        return $this;
    }

    /**
     * 每页返回多少条记录，可选，默认为20,最大不能超过100条
     */
    public function setPageSize($value = 20)
    {
        $this->params['pageSize'] = $value;
        return $this;
    }

}