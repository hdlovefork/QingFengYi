<div class="fly-case-header">
    <h2 class="fly-case-main h_animate_scale"><?= $this->config->item('app_name') ?></h2>
    <h2 class="fly-case-aux">一个简单、易用的淘客小程序制作网站</h2>
    <h3 class="color-yellow">轻松做出生动、有趣的小程序作品</h3>
</div>
<div class="layui-container home-container" style="overflow: hidden;">
    <div class="home-section-header ax-text-center ax-mt8 ax-mb3">
        <h3 class="title">四大核心服务</h3>
        <h3 class="subtitle ax-fz16"><?= $this->config->item('app_name') ?>是一款简单易上手的淘宝优惠券分享工具，通过微信小程序<br>
            的方式，给用户带来生动有趣，并且清晰地展示优惠券商品。</h3>
    </div>
    <div class="layui-row ax-text-center clearfix home-feature-section">
        <div class="layui-col-xs6 layui-col-md3 ax-mt2">
            <img src="./images/grids/feature01.jpg" data-target="h_features" data-type="animate">
            <h5 class="ax-mt2"><b><span class="color_blue_3">微信</span>平台展示模式</b></h5>
            <p>新功能 微信小程序的展示效果耳目一新</p>
        </div>
        <div class="layui-col-xs6 layui-col-md3 ax-mt2">
            <img src="./images/grids/feature02.jpg" data-target="h_features" data-type="animate">
            <h5 class="ax-mt2"><b><span class="color_blue_3">海量</span>精选商品</b></h5>
            <p>商品100%人工审核与官网实时自动同步</p>
        </div>
        <div class="layui-col-xs6 layui-col-md3 ax-mt2">
            <img src="./images/grids/feature03.jpg" data-target="h_features" data-type="animate">
            <h5 class="ax-mt2"><b><span class="color_blue_3">高性能</span>程序内核</b></h5>
            <p>微信小程序全兼容下拉自动加载不卡顿</p>
        </div>
        <div class="layui-col-xs6 layui-col-md3 ax-mt2">
            <img src="./images/grids/feature04.jpg" data-target="h_features" data-type="animate">
            <h5 class="ax-mt2"><b><span class="color_blue_3">限时</span>免费使用</b></h5>
            <p>免费期间注册的帐号即永久可以使用</p>
        </div>
    </div>
    <div class="home-section-header ax-text-center ax-mt8 ax-mb5">
        <h3 class="title">听听用户怎么说</h3>
        <p class="subtitle ax-fz16">我们一直在倾听用户需求，努力让<?=$this->config->item('app_name')?>更贴近用户</p>
    </div>
    <div class="layui-row home-reviews-section home_animation" id="h_reviews" data-animation="h_animate_slide">
        <div class="layui-col-md3 layui-col-xs6 ax-mb2" data-target="h_reviews" data-type="animate">
            <div class="reviews-item ax-text-center ax-bd-solid">
                <div class="portrait portrait_1 ax-center"></div>
                <p class="ax-mt1 ax-mb2">谢先生</p>
                <p class="reviews-content"><?=$this->config->item('app_name')?>淘客软件是一个极好的产品，制作一个淘客小程序根本不需要动用一些大型的超专业的程序开发软件，<?=$this->config->item('app_name')?>在线就能搞定！工作上用它真的能省好多时间和精力！小程序商品内容也是独一无二、精挑细选。</p>
            </div>
        </div>
        <div class="layui-col-md3 layui-col-xs6 ax-mb2" data-target="h_reviews" data-type="animate">
            <div class="reviews-item ax-text-center ax-bd-solid">
                <div class="portrait portrait_2 ax-center"></div>
                <p class="ax-mt1 ax-mb2">郭玲玲</p>
                <p class="reviews-content">自从微信淘客群被微信大量封杀我的微信小号，当时绝觉淘客就此没法再做了 一天在学校的淘客交流群里第一次发现了这个软件，当我看了网站上一些小程序资料，确实是被吸引了。居然可以自己动手做一个属于自己的小程序，感觉很高大上啊，最关键是还不需要花费太多时间。</p>
            </div>
        </div>
        <div class="layui-col-md3 layui-col-xs6 ax-mb2" data-target="h_reviews" data-type="animate">
            <div class="reviews-item ax-text-center ax-bd-solid">
                <div class="portrait portrait_3 ax-center"></div>
                <p class="ax-mt1 ax-mb2">罗斌</p>
                <p class="reviews-content"><?=$this->config->item('app_name')?>是我用过的最好用的淘客软件。它独特的用户界面设计和独特又简单的操作方式深深地吸引了我，并且我的顾客们都反映很方便，还可以自己搜索商品 。</p>
            </div>
        </div>
        <div class="layui-col-md3 layui-col-xs6 ax-mb2" data-target="h_reviews" data-type="animate">
            <div class="reviews-item ax-text-center ax-bd-solid">
                <div class="portrait portrait_4 ax-center"></div>
                <p class="ax-mt1 ax-mb2">网友</p>
                <p class="reviews-content">这个软件总体来说比同行的小程序要方便许多，而且动画炫丽，用<?=$this->config->item('app_name')?>，基本上人人都能做出高收入的，而且不需要挂机群发商品 。基本上不需要多少时间就可以轻松打理，我觉得这绝对是一个好软件。</p>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('layui/layui.js') ?>"></script>
<script>
    layui.use(['jquery'],function ($) {
        var $h_animation = $('.home_animation'),
            scrolls = [],
            ani_status = [],
            delay = 300;

        $h_animation.each(function(index, ele){
            scrolls.push(ele.offsetTop-100);
            ani_status.push(false);
        });

        function add_animation_circle(t, i, ani){
            setTimeout(function(){
                t.addClass(ani);
            }, delay*i);
        }

        window.onscroll = function(e){
            var scrollTop = document.body.scrollTop || window.scrollY || window.pageYOffset, i = 0, j = 0, $t;
            for(; i<scrolls.length; i++){
                if(scrollTop > scrolls[i] && !ani_status[i]){
                    ani_status[i] = true;
                    j = 0;
                    $t = $('[data-target="' + $h_animation.eq(i).attr('id') + '"]');
                    for(; j < $t.length; j++){
                        add_animation_circle($t.eq(j), j, $h_animation.eq(i).data('animation'));
                    }
                }
            }
        }
    });
</script>