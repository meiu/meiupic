<?php 
defined('IN_MWEB') or die('access denied');

$id = intval(getGet('id'));
$space_default_app = !empty($_G['settings']['space_default_app'])?$_G['settings']['space_default_app']:'album';
redirect(U($space_default_app,'space','id='.$id));