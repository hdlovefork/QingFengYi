<?php
namespace libraries\huoxing\request;

/**
 * 火星来客淘宝联盟超级搜索查询入口
 * 根据商品id查询优惠券信息
 * GET /api/search/coupon_item 根据商品id查询优惠券信息
 * @haguo
 */

class TaobaokeCouponItemGet extends Request
{   
    /**
     * API接口名称
     */
    protected $method = '/api/search/coupon_item';

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
     * 商品id查询,[必须]
     */
    public function setitemId($value)
    {
        $this->params['itemId'] = $value;
        return $this;
    }


}