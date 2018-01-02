<?php
namespace libraries\huoxing\request;

/**
 * 火星来客淘宝联盟超级搜索查询入口
 * 淘宝客商品查询
 * GET /api/search/taobaoke_item_list 淘宝客商品搜索
 * @haguo
 */

class TaobaokeItemListGet extends Request
{   
    /**
     * API接口名称
     */
    protected $method = '/api/search/taobaoke_item_list';

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
     * 搜索的价格区间，可选，格式如：23~90，表示价格在23到90元之间
     */
    public function setPrice($value)
    {
        $this->params['price'] = $value;
        return $this;
    }

    /**
     *  类目id,[可选]
     */
    public function setCateGoryId($value)
    {
        $this->params['categoryId'] = $value;
        return $this;
    }
	
	/**
     * 排序方式，可选，1：综合排序，2：按销量降序， 3：按价格升序，4：按价格降序。默认为1
     */
    public function setSort($value = 1)
    {
        $this->params['sort'] = $value;
        return $this;
    }
	
	/**
     * 是否只返回天猫商品，可选，默认为false
	 */
    public function setIsTmall($value = false)
    {
        $this->params['tmall'] = $value;
        return $this;
    }
    
	/**
     * 是否需要高亮搜索关键字
     */
    public function setHighLight($value = false)
    {
        $this->params['highlight'] = $value;
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