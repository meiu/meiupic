<?php

defined('IN_MWEB') or die('access denied');

//输出验证码
$captcha = new Captcha(array('width' => 100,'height'   => 40,'fontsize' => 21));

$captcha->display();