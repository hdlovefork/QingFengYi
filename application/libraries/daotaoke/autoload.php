<?php
/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2017-12-23
 * Time: 20:09
 */

include '../huoxing/autoload.php';

if (!defined('DATAOKE_API_DIR')) {
    define('DATAOKE_API_DIR', rtrim(__DIR__, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR);
}

$client = DATAOKE_API_DIR . 'DTK_Client.php';
if (is_file($client))
    include $client;

$req = DATAOKE_API_DIR . '/request/*.php';
foreach (glob($req) as $file) {
    if(is_file($file))
        include $file;
}
