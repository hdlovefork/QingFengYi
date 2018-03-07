<div class="site-tree-mobile layui-hide">
    <i class="layui-icon">&#xe65f;</i>
</div>
<div class="site-mobile-shade"></div>

<div class="site-tree-mobile layui-hide">
    <i class="layui-icon">&#xe65f;</i>
</div>
<div class="site-mobile-shade"></div>


<div class="fly-panel fly-panel-user" pad20>
    <div class="layui-tab layui-tab-brief" lay-filter="user">
        <ul class="layui-tab-title" id="LAY_mine">
            <li class="<?= $tab == 'wx' ? 'layui-this' : '' ?>" lay-id="info">微信授权</li>
            <li class="<?= $tab == 'tb' ? 'layui-this' : '' ?>" lay-id="info">高佣授权</li>
        </ul>
        <div class="layui-tab-content" style="padding: 20px 0;">
            <div class="layui-fluid layui-tab-item <?= $tab == 'wx' ? 'layui-show' : '' ?>">
                <div class="layui-row pd5">
                    <div class="layui-col-md12">
                        <a @click="getWxAuthUrl" target="_blank" class="layui-btn" key="set-mine"
                           lay-filter="*"
                           lay-submit=""><?= $wx_auth_text ?></a>
                    </div>
                </div>
                <?php if ($xcx_info): ?>
                    <div class="layui-row pd5">
                        <div class="layui-col-md1">
                            名称：
                        </div>
                        <div class="layui-col-md9">
                            <?= $xcx_info['nick_name'] ?>
                        </div>
                    </div>
                    <div class="layui-row pd5">
                        <div class="layui-col-md1">
                            介绍：
                        </div>
                        <div class="layui-col-md9">
                            <?= $xcx_info['signature'] ?>
                        </div>
                    </div>
                    <div class="layui-row pd5">
                        <div class="layui-col-md1">
                            LOGO：
                        </div>
                        <div class="layui-col-md10">
                            <img src="<?= $xcx_info['head_img'] ?>" width="144" height="144"/>
                        </div>
                    </div>
                    <div class="layui-row pd5">
                        <div class="layui-col-md1">
                            体验二维码：
                        </div>
                        <div class="layui-col-md10">
                            <img src="<?= base_url("images/qrcode/{$this->auth->wx_key}.jpg") ?>" width="280" height="280" alt=""/>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="layui-form layui-form-pane layui-tab-item <?= $tab == 'tb' ? 'layui-show' : '' ?>">
                <?= form_open('uc/wxauth/tb_access_token') ?>
                <?php if ($gy_expire_in): ?>
                    <blockquote class="layui-elem-quote">授权过期时间：<?= $gy_expire_in ?>，请在此时间之前【重新授权】</blockquote>
                <?php endif; ?>
                <blockquote class="layui-elem-quote">第一步：点击下面高佣授权，跳转到淘宝后登录淘客账号。</blockquote>
                <div class="layui-row pd5">
                    <div class="layui-col-md12">
                        <a href="<?=$gy_auth_url?>" target="_blank" class="layui-btn" key="set-mine"
                           lay-filter="*"><?= $gy_auth_text ?></a>
                        <a ref="btnTbAuth" :href="tbAuthUrl" target="_blank"></a>
                    </div>
                </div>
                <blockquote class="layui-elem-quote">第二步：当在淘宝授权登录之后会出现一行字符，请填写到下面框内：</blockquote>
                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">淘宝登录授权后出现的内容粘贴到框内</label>
                    <div class="layui-input-block">
                        <?= form_textarea(['name' => 'tb_access_token', 'lay-verify' => "required|checkLength", 'data-label' => '淘宝授权码', 'placeholder' => html_escape('{"w1_expires_in":86400,"refresh_token_valid_time":1519998163154,"taobao_user_nick":"......'), 'class' => 'layui-textarea'], set_value('tb_access_token') ?: $this->auth->tb_access_token) ?>
                    </div>
                </div>
                <div class="layui-form-item">
                    <button class="layui-btn layui-btn-fluid" key="set-mine" lay-filter="*" lay-submit>保存</button>
                </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
<script src="<?= base_url('layui/layui.js') ?>"></script>
<script src="<?= base_url('js/vue.js') ?>"></script>
<script src="https://cdn.bootcss.com/axios/0.17.1/axios.min.js"></script>
<script>
    var vue = new Vue({
            el: "#app",
            data: {
                tbAuthUrl: '',
            },
            mounted: function () {
                layui.use(['layer', 'form', 'element', 'jquery'], function (layer, form, element, $) {
                    form.verify({
                        checkLength: function (value, e) {
                            if (value.trim().length < 50) {
                                return e.getAttribute('data-label') + '长度不符合要求';
                            }
                        }
                    });

                    //手机设备的简单适配
                    var treeMobile = $('.site-tree-mobile')
                        , shadeMobile = $('.site-mobile-shade');

                    treeMobile.on('click', function () {
                        $('body').addClass('site-mobile');
                    });

                    shadeMobile.on('click', function () {
                        $('body').removeClass('site-mobile');
                    });
                });
            },
            methods: {
                getWxAuthUrl: function () {
                    layer.load();
                    axios.get('<?=base_url('uc/wxauth/get_wx_auth_url')?>')
                        .then(function (res) {
                            layer.closeAll('loading');
                            if (!res.data.success) {
                                layer.msg(res.data.error_msg);
                                return;
                            }
                            layer.open({
                                type: 2,
                                title: '微信扫码授权',
                                shadeClose: true,
                                shade: false,
                                resize: false,
                                area: ['893px', '600px'],
                                content: res.data.wx_auth_url,
                                btn: ['授权完成', '取消'],
                                yes: function () {
                                    window.location.href = '<?=base_url('uc/wxauth')?>';
                                }
                            });
                        });
                }
            },
        })
    ;

    <?=show_message()?>
</script>
</script>