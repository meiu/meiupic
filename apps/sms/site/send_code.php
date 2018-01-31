<?php 
defined('IN_MWEB') or die('access denied');


$mobile = trim(getPost('mobile'));
$captcha_code = trim(getPost('captcha'));

$captcha = new Captcha;
if(!$captcha_code){
    echo json_encode(array('ret'=>true,'msg'=>'请输入验证码!'));
    exit;
}
if(!$captcha->check($captcha_code,'sms')){
    echo json_encode(array('ret'=>false,'msg'=>'验证码输入错误!'));
    exit;
}

//判断手机号码
if(!isMobile($mobile)){
    echo json_encode(array('ret'=>false,'msg'=>'手机号码有误!'));
    exit;
}

$captcha->clear('sms');

$code = rand(100000,999999);

$msg = '';
if(app('sms')->sendCode($mobile,$code,$msg)){
    echo json_encode(array('ret'=>true,'msg'=>$msg));
    exit;
}else{
    echo json_encode(array('ret'=>false,'msg'=>$msg));
    exit;
}