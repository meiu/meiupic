<?php

defined('IN_MWEB') or die('access denied');

$user_setting = getSetting('user_setting',true);
if(!$user_setting['enable_register']){
    showInfo('当前网站关闭了注册！','javascript:history.back();');
}

if(isPost() || getGet('ajax') ){
    $data = $infodata = array();
    $data['username'] = safestr(trim(getPost('username')));
    $data['userpass'] = getPost('userpass');
    $data['email'] = safestr(trim(getPost('email')));
    $data['mobile'] = safestr(trim(getPost('mobile')));
    $data['nickname'] = safestr(trim(getPost('nickname')));

    $infodata['extra1'] = safestr(getPost('extra1'));
    $infodata['extra2'] = safestr(getPost('extra2'));
    $infodata['extra3'] = safestr(getPost('extra3'));
    $infodata['extra4'] = safestr(getPost('extra4'));
    $infodata['extra5'] = safestr(getPost('extra5'));
    $infodata['extra6'] = safestr(getPost('extra6'));
    $infodata['extra7'] = safestr(getPost('extra7'));
    $infodata['extra8'] = safestr(getPost('extra8'));

    if(empty(getPost('agree'))){
        alert('你未同意用户协议!',false,'',array('field'=>'agree'));
    }
    if(empty($data['username'])){
        alert('请输入用户名!',false,'',array('field'=>'username'));
    }
    if(empty($data['userpass'])){
        alert('请输入密码!',false,'',array('field'=>'userpass'));
    }
    if(strlen($data['userpass']) < 6){
        alert('密码不得少于6位!',false,'',array('field'=>'userpass'));
    }
    if(empty($data['email'])){
        alert('请输入Email!',false,'',array('field'=>'email'));
    }
    if(!isEmail($data['email'])){
        alert('Email格式不正确!',false,'',array('field'=>'email'));
    }
    if(empty($data['mobile'])){
        alert('请输入手机号码!',false,'',array('field'=>'mobile'));
    }
    if(!isMobile($data['mobile'])){
        alert('手机号码格式不正确!',false,'',array('field'=>'mobile'));
    }
    if(empty($data['nickname'])){
        alert('请输入昵称!',false,'',array('field'=>'nickname'));
    }
    $user_setting = getSetting('user_setting',true);
    if($user_setting['enable_reg_captcha']){
        $captcha_code = getPost('captcha');
        $captcha = new Captcha;
        if(!$captcha_code){
            alert('请输入验证码!',false,'',array('field'=>'captcha'));
        }
        if(!$captcha->check($captcha_code)){
            alert('验证码输入错误!',false,'',array('field'=>'captcha'));
        }
    }
    $mobile_code = getPost('mobile_code');
    if(!app('sms')->checkCode($data['mobile'],$mobile_code)){
        alert('手机验证码输入错误！',false,'',array('field'=>'mobile_code'));
    }

    $data['userpass'] = md5($data['userpass']);
    
    list($uid,$msg,$field) = app('user')->register($data);
    if($uid){
        //插入额外信息
        $infodata['uid'] = $uid;
        $infodata['addtime'] = time();
        M('users_info')->insert($infodata);

        list($r,$msg) = app('user')->setLogin($data['username'],$data['userpass'],0,false);

        alert('注册成功!',true,U('base','index'),array('othermsg'=>$msg));
    }else{
        alert($msg,false,'',array('field'=>$field));
    }
    exit;
}
//取出额外字段
$fields = app('base')->getSetting('user_fields',true);
$view->assign('fields',$fields);

$site_title = '用户注册 - '.getSetting('site_title');
$view->assign('site_title',$site_title);

$view->display('user/register.php');