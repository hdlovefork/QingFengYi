<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/

$hook['pre_system'] = function () {
    date_default_timezone_set('PRC');
};

$hook['wx_refresh_token'] = [
    'class'=> 'Hook_points',
    'function'=>'refresh_token',
    'filename'=>'Hook_points.php',
    'filepath'=>'hooks',
];