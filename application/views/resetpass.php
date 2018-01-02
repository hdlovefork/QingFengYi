<div id="app" class="layui-container fly-marginTop">
    <div class="layui-row">
        <div class="layui-col-md6 layui-col-md-offset3 fly-panel fly-panel-user" pad20>
            <div class="layui-tab layui-tab-brief" lay-filter="user">
                <ul class="layui-tab-title">
                    <li class="layui-this">设置新密码</li>
                </ul>
                <div class="layui-form layui-tab-content" id="LAY_ucm" style="padding: 20px 0;">
                    <div class="layui-tab-item layui-show">
                        <div class="layui-form layui-form-pane">
                            <?= form_open('resetpass') ?>
                            <div class="layui-form-item">
                                <label for="L_pass" class="layui-form-label">新密码</label>
                                <div class="layui-input-block">
                                    <input type="password" v-model="pass" id="L_pass" name="pass" lay-verify="pass"
                                           placeholder="请输入新密码" autocomplete="off" class="layui-input">
                                    <span class="fly-text-error"><?= form_error('pass') ?></span>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label for="L_pass" class="layui-form-label">确认密码</label>
                                <div class="layui-input-block">
                                    <input type="password" v-model="repass" id="L_pass" name="repass"
                                           lay-verify="repass"
                                           placeholder="请再次输入新密码" autocomplete="off" class="layui-input">
                                    <span class="fly-text-error"><?= form_error('repass') ?></span>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <button class="layui-btn" lay-filter="*" lay-submit style="width: 100%;">确定</button>
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
            pass: '',
            repass: '',
        },
        mounted: function () {
            layui.use(['form', 'layer'], function (form, layer) {
                form.verify({
                    pass: [
                        /^[\S]{6,20}$/
                        , '密码必须6到20位，且不能出现空格'
                    ],
                    repass: function (repass) {
                        if (app.pass !== app.repass) {
                            return '两次输入不一致';
                        }
                    }
                });
            })
        },
    });
</script>