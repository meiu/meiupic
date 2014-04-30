<?php
ini_set('display_errors', 'on');//显示所有的错误
error_reporting(E_ALL ^ E_NOTICE);

define('APP_DEBUG', true);
define('LOG_RECORD',true);
define('IN_MWEB',true);
define('ADMINPAGE',false);

//360安全过滤
if(is_file($_SERVER['DOCUMENT_ROOT'].'/360safe/360webscan.php')){
    require_once($_SERVER['DOCUMENT_ROOT'].'/360safe/360webscan.php');
}

require_once('core/core.php');

run();