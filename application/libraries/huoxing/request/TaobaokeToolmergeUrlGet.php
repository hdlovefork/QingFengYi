<?php
namespace libraries\huoxing\request;

/**
 * 火星来客淘宝联盟超级搜索查询入口
 * 二合一链接合成
 * GET /api/tool/merge_url 二合一链接合成
 * @haguo
 */

class TaobaokeToolmergeUrlGet extends Request
{   
    /**
     * API接口名称
     */
    protected $method = '/api/tool/merge_url';

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
     * 商品Id,[必须]
     */
    public function setitemUrl($value)
    {
        $this->params['itemUrl'] = $value;
        return $this;
    }
	
	/**
     * 优惠券URL链接,[必须]
     */
    public function setUlandUrl($value)
    {
        $this->params['couponUrl'] = $value;
        return $this;
    }

}