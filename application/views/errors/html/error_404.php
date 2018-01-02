<?php
$CI = &get_instance();
if(empty($CI)){
    $CI = new CI_Controller();
}
$CI->load->config('app');
$CI->load->helper('url');
echo $CI->load->view('template/head',NULL,TRUE);
?>
<div class="layui-container fly-marginTop">
    <div class="fly-panel">
        <div class="fly-none">
            <h2><i class="iconfont icon-404"></i></h2>
            <p>外星人入侵把页面带走了，啥也没有留下！您只能返回<a href="<?=base_url()?>"> 地球 </a>了。 </p>
        </div>
    </div>
</div>
<?php
echo $CI->load->view('template/foot',NULL,TRUE);
?>