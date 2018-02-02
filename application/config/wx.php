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
//$config['wx_login_url'] =  "https://api.weixin.qq.com/sns/jscode2session?" .
//"appid=%s&secret=%s&js_code=%s&grant_type=authorization_code";
$config['wx_login_url'] =  'https://api.weixin.qq.com/sns/component/jscode2session?'.
    'appid=%s&js_code=%s&grant_type=authorization_code&component_appid=%s&component_access_token=%s';

/**
 * 微信获取TOKEN URL
 */
$config['wx_access_token_url'] = "https://api.weixin.qq.com/cgi-bin/token?" .
"grant_type=client_credential&appid=%s&secret=%s";

$config['wx_token_salt'] = '28kn$msf026mfb';

//清风易小程序开放平台
$config['wx_third_token']='8YvAmKWHt6FD9';
$config['wx_third_encodingaeskey']='f1uVIMteErxUAb5oPYmS9KBXcZ7ji0kd62Ha3zOlRqN';
$config['wx_third_app_id']='wx134c2d5e297d573f';
$config['wx_third_app_secret']='a62588db8e0adc9d05f8f707b307540b';
//$config['wx_third_app_secret']='a62588db8e0adc9d05f8f707b307540b';
//$config['wx_third_app_secret']='a62588db8e0adc9d05f8f707b307540b';


