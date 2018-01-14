<?php

namespace libraries\huoxing\request;
/**
 * 火星来客淘宝联盟超级搜索查询入口
 * @haguo
 */
class Request
{

    /**
     * 请求参数
     * @var array
     */
    protected $params = array();

    protected $method = '';

    protected $submitMethod = 'GET';

    /**
     * 返回API接口名称。
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * 返回所有参数
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    public function getSubmitMethod()
    {
        return $this->submitMethod;
    }

    protected $header = [];

    public function addHeader($key, $value)
    {
        $this->header[$key] = $value;
    }

    public function getHeader()
    {
        return $this->header;
    }

    protected $cookies = [];

    public function addCookie($key, $value)
    {
        $this->cookies[$key] = $value;
        return $this;
    }

    public function getCookies()
    {
        return $this->cookies;
    }
}