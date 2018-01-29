<?php 
defined('IN_MWEB') or die('access denied');
define('IN_MY',true);

checkLogin();

$my_default_app = !empty($_G['settings']['my_default_app'])?$_G['settings']['my_default_app']:'album';
redirect(U($my_default_app,'my'));