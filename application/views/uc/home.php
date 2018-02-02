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
            <li class="layui-this" lay-id="info">我的资料</li>
            <li lay-id="pass">修改密码</li>
        </ul>
        <div class="layui-tab-content" style="padding: 20px 0;">
            <div class="layui-form layui-form-pane layui-tab-item layui-show">
                <?= form_open('uc') ?>
                <div class="layui-form-item">
                    <label for="L_wxkey" class="layui-form-label fly-form-label-md">微信AppID</label>
                    <div class="layui-input-inline fly-input-inline-md">
                        <?= form_input(['id' => "L_wxkey", 'name' => "wxkey", 'required' => '', 'data-label' => '微信KEY', 'lay-verify' => "required|checkLength", 'placeholder' => "微信公众平台申请", 'autocomplete' => "off", 'class' => "layui-input"], set_value('wxkey') ?: $this->auth->wx_key) ?>
                        <span class="fly-text-error"><?= form_error('wxkey') ?></span>
                    </div>
                </div>
<!--                <div class="layui-form-item">-->
<!--                    <label for="L_wxsecret" class="layui-form-label fly-form-label-md">微信AppSecret</label>-->
<!--                    <div class="layui-input-inline fly-input-inline-md">-->
<!--                        --><?//= form_input(['id' => "L_wxsecret", 'name' => "wxsecret", 'required' => '', 'data-label' => '微信SECRET', 'lay-verify' => "required|checkLength", 'placeholder' => "微信公众平台申请", 'autocomplete' => "off", 'class' => "layui-input"], set_value('wxsecret') ?: $this->auth->wx_secret) ?>
<!--                        <span class="fly-text-error">--><?//= form_error('wxsecret') ?><!--</span>-->
<!--                    </div>-->
<!--                </div>-->
                <div class="layui-form-item">
                    <label for="L_tbpid" class="layui-form-label fly-form-label-md">淘宝pid</label>
                    <div class="layui-input-inline fly-input-inline-md">
                        <?= form_input(['id' => "L_tbpid", 'name' => "tbpid", 'required' => '', 'data-label' => '淘宝PID', 'lay-verify' => "required|checkLength|checkPID", 'placeholder' => "格式：mm_xxx_xxx_xxx", 'autocomplete' => "off", 'class' => "layui-input"], set_value('tbpid') ?: $this->auth->tb_pid) ?>
                        <span class="fly-text-error"><?= form_error('tbpid') ?></span>
                    </div>
                    <div class="layui-form-mid">
                        <span class="layui-word-aux">格式：mm_xxx_xxx_xxx</span>
                    </div>
                </div>
<!--                <div class="layui-form-item">-->
<!--                    <label for="L_dtkkey" class="layui-form-label fly-form-label-md">大淘客APIKEY</label>-->
<!--                    <div class="layui-input-inline fly-input-inline-md">-->
<!--                        --><?//= form_input(['id' => "L_dtkkey", 'name' => "dtkkey", 'required' => '', 'data-label' => '大淘客APIKEY', 'lay-verify' => "required|checkLength", 'placeholder' => "大淘客申请的APIKEY", 'autocomplete' => "off", 'class' => "layui-input"], set_value('dtkkey') ?: $this->auth->dtk_key) ?>
<!--                        <span class="fly-text-error">--><?//= form_error('dtkkey') ?><!--</span>-->
<!--                    </div>-->
<!--                </div>-->
                <div class="layui-form-item">
                    <button class="layui-btn" key="set-mine" lay-filter="*" lay-submit>确认修改</button>
                </div>
                <?= form_close() ?>
            </div>
            <div class="layui-form layui-form-pane layui-tab-item">
                <?= form_open('uc/setpass') ?>
                <div class="layui-form-item">
                    <label for="L_pass" class="layui-form-label">新密码</label>
                    <div class="layui-input-inline">
                        <input type="password" id="L_pass" name="pass" required lay-verify="required|pass"
                               autocomplete="off"
                               class="layui-input">
                    </div>
                    <div class="layui-form-mid layui-word-aux">6到20个字符</div>
                </div>
                <div class="layui-form-item">
                    <label for="L_repass" class="layui-form-label">确认密码</label>
                    <div class="layui-input-inline">
                        <input type="password" id="L_repass" name="repass" required lay-verify="required|repass"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <button class="layui-btn" key="set-mine" lay-filter="*" lay-submit>确认修改</button>
                </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>
</div>
<script src="<?= base_url('layui/layui.js') ?>"></script>
<script>
    layui.use(['form', 'element', 'jquery'], function (form, element, $) {
        form.verify({
            checkLength: function (value, e) {
                if (value.trim().length < 10) {
                    return e.getAttribute('data-label') + '长度不符合要求';
                }
            },
            checkPID: function (value, e) {
                if (!/mm_\d+_\d+_\d+/.test(value)) {
                    return e.getAttribute('data-label') + '格式不正确';
                }
            },
            pass: [
                /^[\S]{6,20}$/,
                "密码长度应为6~20个字符，且不能包含空格"
            ],
            repass: function (value) {
                if ($('#L_pass').val() !== value) {
                    return '两次输入的密码不一致';
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
    <?=show_message()?>
</script>
</script>