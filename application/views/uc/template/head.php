<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?=!empty($title)?:$this->config->item('app_site_default_title')?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="keywords" content="<?=$this->config->item('app_site_keywords')?>">
    <meta name="description" content="<?=$this->config->item('app_site_description')?>">
    <link rel="stylesheet" href="<?=base_url('layui/css/layui.css')?>">
    <link rel="stylesheet" href="<?=base_url('css/global.css')?>">
</head>
<body>

<div class="fly-header layui-bg-black">
    <div class="layui-container">
        <a class="fly-logo" style="color:#999;font-size:26px;" href="/">
            <?=$this->config->item('app_name')?>
        </a>
        <ul class="layui-nav fly-nav layui-hide-xs">
            <li class="layui-nav-item layui-this">
                <a href="<?=base_url()?>"><i class="layui-icon">&#xe68e;</i>首页</a>
            </li>
            <li class="layui-nav-item">
                <a href="<?=base_url('demo')?>"><i class="layui-icon">&#xe63b;</i>案例</a>
            </li>
        </ul>

        <ul class="layui-nav fly-nav-user">
            <!-- 登入后的状态 -->
            <li class="layui-nav-item">
                <a class="fly-nav-avatar" href="javascript:;">
                    <cite class="layui-hide-xs"><?=$this->auth->username()?></cite>
                    <i class="iconfont icon-renzheng layui-hide-xs"></i>
                    <i class="layui-badge fly-badge-vip layui-hide-xs">VIP</i>
                </a>
                <dl class="layui-nav-child">
                    <dd><a href="<?=base_url('uc')?>"><i class="layui-icon">&#xe620;</i>基本设置</a></dd>
                    <hr style="margin: 5px 0;">
                    <dd><a href="<?=base_url('logout')?>" style="text-align: center;">退出</a></dd>
                </dl>
            </li>
        </ul>
    </div>
</div>