<?php
namespace libraries\huoxing\request;

/**
 * 火星来客淘宝联盟超级搜索查询入口
 * 生成淘口令
 * GET /api/tool/taobao_password
 * @haguo
 */

class TaobaokeToolTaoBaoPasswordGet extends Request
{   
    /**
     * API接口名称
     */
    protected $method = '/api/tool/taobao_password';

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
     * 推广链接[必须]
     */
    public function setClickUrl($value)
    {
        $this->params['clickUrl'] = $value;
        return $this;
    }

    /**
     * 文本描述[必须]
     */
    public function setText($value)
    {
        $this->params['text'] = $value;
        return $this;
    }

    /**
     * 图片logo
     */
    public function setLogo($value)
    {
        $this->params['logo'] = $value;
        return $this;
    }

}