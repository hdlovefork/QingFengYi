<div id="app" class="layui-container fly-marginTop">
    <div class="layui-row">
        <div class="layui-col-md6 layui-col-md-offset3 fly-panel fly-panel-user" pad20>
            <div class="layui-tab layui-tab-brief" lay-filter="user">
                <ul class="layui-tab-title">
                    <li class="layui-this">登入</li>
                    <li><a href="<?= base_url('reg') ?>">注册</a></li>
                </ul>
                <div class="layui-form layui-tab-content" id="LAY_ucm" style="padding: 20px 0;">
                    <div class="layui-tab-item layui-show">
                        <div class="layui-form layui-form-pane">
                            <?= form_open('login') ?>
                            <div class="layui-form-item">
                                <label for="L_email" class="layui-form-label">邮箱</label>
                                <div class="layui-input-block">
                                    <?= form_input(['id' => 'L_email', 'name' => 'email', 'lay-verify' => 'email', 'placeholder' => '请输入电子邮箱', 'required' => '', 'value' => set_value('email'), 'autocomplete' => 'off', 'class' => 'layui-input']) ?>
                                    <span class="fly-text-error"><?= form_error('email') ?></span>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label for="L_pass" class="layui-form-label">密码</label>
                                <div class="layui-input-block">
                                    <input type="password" id="L_pass" name="pass" lay-verify="pass"
                                           placeholder="请输入密码" autocomplete="off" class="layui-input">
                                    <span class="fly-text-error"><?= form_error('pass') ?></span>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label for="L_vercode" class="layui-form-label">验证码</label>
                                <div class="layui-input-inline" style="width:100px;">
                                    <?= form_input(['id' => 'L_vercode', 'name' => 'vercode', 'class' => 'layui-input', 'lay-verify' => 'vercode', 'placeholder' => '请输入验证码', 'autocomplete' => 'off']) ?>
                                    <span class="fly-text-error"><?= form_error('vercode') ?></span>
                                </div>
                                <div class="layui-input-inline" style="width:150px;">
                                    <img :src="imageUrl" width="150px" @click="changeImage"/>
                                </div>
                                <div class="layui-input-inline" style="width:80px;">
                                    <button type="button" class="layui-btn layui-btn-normal layui-btn-xs"
                                            style="position: relative;top: 10px;" @click="changeImage">看不清
                                    </button>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <button class="layui-btn" lay-filter="*" lay-submit>立即登录</button>
                                <span style="padding-left:20px;"><a
                                            href="<?= base_url('forget') ?>">忘记密码？</a></span>
                            </div>
                            <?= form_close() ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('js/vue.js') ?>"></script>
<script src="<?= base_url('layui/layui.js') ?>"></script>

<script>
    <?=show_message()?>
    var app = new Vue({
        el: '#app',
        data: {
            imageUrl: '<?=base_url('captcha')?>',
            layer: {}
        },
        mounted: function () {
            layui.use(['form', 'layer'], function (form, layer) {
                form.verify({
                    pass: [
                        /^[\S]{6,20}$/
                        , '密码必须6到20位，且不能出现空格'
                    ],
                    vercode: [
                        /^\d{4}$/,
                        '验证码应该是4位数字'
                    ]
                });
            })
        },
        methods: {
            changeImage: function () {
                app.imageUrl = '<?=base_url('captcha')?>?t=' + Math.random();
            }
        },
    });
</script>