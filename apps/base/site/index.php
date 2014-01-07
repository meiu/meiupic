<?php

defined('IN_MWEB') or die('access denied');

$title = getSetting('site_seo_title');
if($title){
    $view->assign('site_title',getSetting('site_seo_title'));
}
$view->assign('site_keywords',getSetting('site_seo_keywords'));
$view->assign('site_description',getSetting('site_seo_description'));

$view->display('default.php');