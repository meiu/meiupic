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

$m = M('sms_codes');
$row = $m->findRow("mobile=".$m->escape($mobile));

if($row){
    if(time() - $row['lasttime']<60){
        echo json_encode(array('ret'=>false,'msg'=>'操作过于频繁，请稍后再次操作!'));
        exit;
    }
    if($row['send_count']>=10){
        echo json_encode(array('ret'=>false,'msg'=>'您今日操作过于频繁!'));
        exit;
    }
}

$code = rand(100000,999999);
if($row){
    //如果不是今日send_cound置1，否则加一
    if(date('Y-m-d',$row['lasttime'])==date('Y-m-d')){
        $send_count = $row['send_count']+1;
    }else{
        $send_count = 1;
    }
    $m->updateW("mobile=".$m->escape($mobile),array('code'=>$code,'lasttime'=>time(),'send_count'=>$send_count));
}else{
    $m->insert(array("mobile"=>$mobile,'code'=>$code,'lasttime'=>time(),'send_count'=>1));
}

if(app('sms')->sendSMS($mobile,$code,1)){
    echo json_encode(array('ret'=>true,'msg'=>'发送成功!'));
    exit;
}else{
    echo json_encode(array('ret'=>false,'msg'=>'发送失败！'));
    exit;
}