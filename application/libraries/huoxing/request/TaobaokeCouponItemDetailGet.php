<?php
namespace libraries\huoxing\request;
/**
 * 火星来客淘宝联盟超级搜索查询入口
 * 淘宝客商品优惠券详情查询
 * GET /api/coupon_item/detail 获取优惠券详细信息
 * @haguo
 */

class TaobaokeCouponItemDetailGet extends Request
{   
    /**
     * API接口名称
     */
    protected $method = '/api/coupon_item/detail';

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
     * 优惠券URL链接,[必须]
     */
    public function setUlandUrl($value)
    {
        $this->params['ulandUrl'] = $value;
        return $this;
    }

}