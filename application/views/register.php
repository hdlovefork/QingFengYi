<div id="app" class="layui-container fly-marginTop">
    <div class="layui-row">
        <div class="layui-col-md6 layui-col-md-offset3 fly-panel fly-panel-user" pad20>
            <div class="layui-tab layui-tab-brief" lay-filter="user">
                <ul class="layui-tab-title">
                    <li><a href="<?= base_url('login') ?>">登入</a></li>
                    <li class="layui-this">注册</li>
                </ul>
                <div class="layui-form layui-tab-content" id="LAY_ucm" style="padding: 20px 0;">
                    <div class="layui-tab-item layui-show">
                        <div class="layui-form layui-form-pane">
                            <?= form_open('reg') ?>
                            <div class="layui-form-item">
                                <label for="L_email" class="layui-form-label">邮箱</label>
                                <div class="layui-input-inline" style="width: 240px">
                                    <?= form_input(['id' => 'L_email', 'name' => 'email', 'v-bind:readonly' => 'remainSecond>=0', 'v-bind:class' => '{\'fly-text-disabled\':remainSecond>=0}', 'v-model.trim' => 'email', 'lay-verify' => 'email', 'placeholder' => '请输入电子邮箱', 'required' => '', 'value' => set_value('email'), 'autocomplete' => 'off', 'class' => 'layui-input']) ?>
                                    <span id="L_email_error" class="fly-text-error"
                                          v-show="emailError" style="display:none;"
                                          v-text="emailError"><?= form_error('email') ?></span>
                                </div>
                                <div class="layui-input-inline" style="width:80px">
                                    <button type="button" v-show="emailUnique" v-bind:disabled="isSentEmail"
                                            v-bind:class="{'layui-btn-disabled':isSentEmail}"
                                            class="layui-btn layui-btn-radius layui-btn-normal" style="display: none;"
                                            @click="sendVerifyEmail" v-text="btnSendEmailText"></button>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label for="L_vercode" class="layui-form-label">验证码</label>
                                <div class="layui-input-inline" style="width:240px;">
                                    <?= form_input(['id' => 'L_vercode', 'v-model' => 'verCode', 'name' => 'vercode', 'v-bind:disabled' => '!allowInputVerCode', 'v-bind:class' => "{'fly-text-disabled':!allowInputVerCode}", 'class' => 'layui-input', 'lay-verify' => 'vercode', 'required' => '', 'placeholder' => '请输入邮箱验证码', 'autocomplete' => 'off']) ?>
                                    <span class="fly-text-error"><?= form_error('vercode') ?></span>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label for="L_pass" class="layui-form-label">密码</label>
                                <div class="layui-input-inline" style="width:240px">
                                    <?= form_password(['id' => 'L_pass', 'name' => 'pass', 'class' => 'layui-input', 'lay-verify' => 'pass', 'placeholder' => '请输入密码', 'autocomplete' => 'off']) ?>
                                    <span class="fly-text-error"><?= form_error('pass') ?></span>
                                </div>
                                <div class="layui-form-mid layui-word-aux">6到20个字符</div>
                            </div>

                            <div class="layui-form-item">
                                <?= form_button(['class' => 'layui-btn', 'type' => 'submit', 'lay-submit' => '', 'style' => 'width:100%', 'lay-filter' => '*'], '立即注册') ?>
                            </div>
                            <?php echo form_close() ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.bootcss.com/lodash.js/4.17.4/lodash.min.js"></script>
<script src="https://cdn.bootcss.com/axios/0.17.1/axios.min.js"></script>
<script src="<?= base_url('js/vue.js') ?>"></script>
<script src="<?= base_url('layui/layui.js') ?>"></script>

<script>
    <?=show_message()?>
    var app = new Vue({
        el: '#app',
        data: {
            email: '<?=set_value('email')?>',
            emailError: '',
            verCode: '',
            emailUnique: false,
            btnSendEmailText: '收取验证邮件',
            isSentEmail: false,
            remainSecond: -1,
            allowInputVerCode: false,
            layer: {},
        },
        computed: {},
        mounted: function () {
            layui.use(['layer', 'form'], function (layer, form) {
                app.layer = layer;
                form.verify({
                    pass: [
                        /^[\S]{6,20}$/
                        , '密码必须6到20位，且不能出现空格'
                    ],
                    vercode: function (value, item) {
                        if (!app.allowInputVerCode) {
                            return '请收取验证邮件';
                        }
                        if (/^\d{4}$/.test(value) === false) {
                            if (app.isSentEmail && value.length === 0) {
                                return '你还没有输入验证码呢！';
                            }
                            return '验证码应该是4位数字';
                        }
                    }
                });
            })
        },
        methods: {
            validEmail: function (email) {
                return /^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/.test(email);
            },
            sendVerifyEmail: function () {
                app.btnSendEmailText = '发送中...';
                axios.post('<?=base_url('vercode')?>',
                    {email: this.email})
                    .then(function (res) {
                        app.emailError = res.data.error_msg;
                        if (res.data.success === true) {
                            app.allowInputVerCode = true;
                            app.remainSecond = 60;
                            app.countdown();
                        } else {
                            app.resetEmailStatus();
                            app.layer && app.layer.msg('发送邮件失败');
                        }
                    });
                app.isSentEmail = true;
                app.btnSendEmailText = '发送中...';
            },
            sendCheckEmail: _.debounce(function (email) {
                axios.post('<?=base_url('checkemail')?>',
                    {email: email})
                    .then(function (res) {
                        app.emailUnique = res.data.success && !res.data.exist && res.data.email === app.email;
                        app.emailError = res.data.success ? (res.data.exist ? '该邮箱已被注册' : '') : res.data.error_msg;
                    });

            }, 500),
            checkEmail: function (newEmail) {
                app.emailError = '';
                app.emailUnique = false;
                app.allowInputVerCode = false;
                app.verCode = '';
                if (!app.validEmail(newEmail)) {
                    return;
                } else {
                    app.emailError = '';
                }
                app.sendCheckEmail(newEmail);
            },
            countdown: function () {
                if (app.remainSecond > 0) {
                    app.btnSendEmailText = app.remainSecond + '秒后重新发送';
                    app.remainSecond--;
                    setTimeout(function () {
                        app.countdown();
                    }, 1000);
                } else {
                    app.resetEmailStatus();
                    app.emailUnique = true;
                }

            },
            resetEmailStatus: function () {
                app.emailError = '';
                app.btnSendEmailText = '收取验证邮件';
                app.isSentEmail = false;
                app.remainSecond = -1;
            }
        },
        watch: {
            email: _.debounce(function (newEmail) {
                app.checkEmail(newEmail);
            }, 500)
        }
    });
</script>