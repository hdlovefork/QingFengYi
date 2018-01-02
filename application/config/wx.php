<?php
/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2017-12-09
 * Time: 17:53
 */

/**
 * 微信登录URL
 */
$config['wx_login_url'] =  "https://api.weixin.qq.com/sns/jscode2session?" .
"appid=%s&secret=%s&js_code=%s&grant_type=authorization_code";

/**
 * 微信获取TOKEN URL
 */
$config['wx_access_token_url'] = "https://api.weixin.qq.com/cgi-bin/token?" .
"grant_type=client_credential&appid=%s&secret=%s";

$config['wx_token_salt'] = '28kn$msf026mfb';


