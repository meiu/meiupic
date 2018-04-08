<?php
ini_set('display_errors', 'on');//显示所有的错误
error_reporting(E_ALL);

define('APP_DEBUG', true);
define('IN_MWEB',true);
define('ADMINPAGE',true);

require_once('../core/core.php');

runAdmin();