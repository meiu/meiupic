<?php
ini_set('display_errors', 'on');//显示所有的错误
error_reporting(E_ALL ^ E_NOTICE);

define('APP_DEBUG', true);
define('LOG_RECORD',true);
define('IN_MWEB',true);
define('ADMINPAGE',false);

require_once('core/core.php');

run();