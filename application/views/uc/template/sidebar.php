<div id="app" class="layui-container fly-marginTop fly-user-main">
    <ul class="layui-nav layui-nav-tree layui-inline" lay-filter="user">
        <li class="layui-nav-item <?='uc'===$this->uri->uri_string()?'layui-this':''?>">
            <a href="<?=base_url('uc')?>">
                <i class="layui-icon">&#xe620;</i>
                基本设置
            </a>
        </li>
        <li class="layui-nav-item <?='uc/wxauth'===$this->uri->uri_string()?'layui-this':''?>">
            <a href="<?=base_url('uc/wxauth/')?>">
                <i class="layui-icon">&#xe612;</i>
                授权信息
            </a>
        </li>
    </ul>