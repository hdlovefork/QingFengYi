<div id="app" class="layui-container fly-marginTop">
    <div class="layui-row">
        <div class="layui-col-md6 layui-col-md-offset3 fly-panel fly-panel-user" pad20>
            <div class="layui-tab layui-tab-brief" lay-filter="user">
                <ul class="layui-tab-title">
                    <li><a href="<?=base_url('login')?>">登入</a></li>
                    <li class="layui-this">忘记密码</li>
                </ul>
                <div class="layui-form layui-tab-content" id="LAY_ucm" style="padding: 20px 0;">
                    <div class="layui-tab-item layui-show">
                        <div class="layui-form layui-form-pane">
                            <?= form_open('forget') ?>
                            <div class="layui-form-item">
                                <label for="L_email" class="layui-form-label">邮箱</label>
                                <div class="layui-input-block">
                                    <?= form_input(['id' => 'L_email','v-model'=>'email', 'name' => 'email', 'lay-verify' => 'email', 'placeholder' => '请输入电子邮箱', 'required' => '', 'value' => set_value('email'), 'autocomplete' => 'off', 'class' => 'layui-input']) ?>
                                    <span class="fly-text-error" v-text="emailError"><?=form_error('email')?></span>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label for="L_vercode" class="layui-form-label">验证码</label>
                                <div class="layui-input-inline" style="width:100px;">
                                    <?= form_input(['id' => 'L_vercode', 'name' => 'vercode','class' => 'layui-input','v-model'=>'verCode', 'lay-verify' => 'vercode',  'placeholder' => '请输入验证码', 'autocomplete' => 'off']) ?>
                                    <span class="fly-text-error"><?= form_error('vercode') ?></span>
                                </div>
                                <div class="layui-input-inline" style="width:150px;">
                                    <img :src="imageUrl" width="150px" @click="changeImage"/>
                                </div>
                                <div class="layui-input-inline" style="width:80px;">
                                    <button type="button" class="layui-btn layui-btn-normal layui-btn-xs" style="position: relative;top: 10px;" @click="changeImage">看不清</button>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <button class="layui-btn" lay-filter="*" lay-submit v-bind:disabled="!allPass" v-bind:class="{'layui-btn-disabled':!allPass}">发送重置密码邮件</button>
                            </div>
                            <?=form_close()?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('js/vue.js') ?>"></script>
<script src="https://cdn.bootcss.com/lodash.js/4.17.4/lodash.min.js"></script>
<script src="https://cdn.bootcss.com/axios/0.17.1/axios.min.js"></script>
<script src="<?= base_url('layui/layui.js') ?>"></script>

<script>
    <?=show_message()?>

    var app = new Vue({
        el: '#app',
        data: {
            imageUrl:'<?=base_url('captcha')?>',
            email:'',
            emailError:'',
            emailExist: false,
            verCode:'',
            layer:{}
        },
        mounted: function () {
            layui.use(['form','layer'], function (form,layer) {
                app.layer = layer;
                form.verify({
                    vercode: [
                        /^\d{4}$/,
                        '验证码应该是4位数字'
                    ]
                });
            })
        },
        computed:{
            validCode:function () {
                return /^\d{4}$/.test(this.verCode);
            },
            allPass:function () {
                return this.emailError ==='' && this.validCode && this.emailExist;
            }
        },
        methods:{
            changeImage:function () {
                app.imageUrl = '<?=base_url('captcha')?>?t='+Math.random();
            },
            checkEmail: function (newEmail) {
                app.emailError = '';
                app.emailExist = false;
                app.allowInputVerCode = false;
                app.verCode  = '';
                if (app.validEmail(newEmail)) {
                    app.emailError = '';
                    app.sendCheckEmail(newEmail);
                }

            },
            sendCheckEmail: _.debounce(function (email) {
                axios.post('<?=base_url('checkemail')?>',
                    {email: email})
                    .then(function (res) {
                        app.emailExist = res.data.success && res.data.exist && res.data.email === app.email;
                        app.emailError = (res.data.success)?(res.data.exist?'':'找不到该邮箱'):res.data.error_msg;
                    });

            }, 500),
            validEmail: function (email) {
                return /^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/.test(email);
            },
        },
        watch:{
            email:_.debounce(function (newEmail) {
                app.checkEmail(newEmail)
            },500)
        }
    });
</script>