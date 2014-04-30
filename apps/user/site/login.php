<?php

defined('IN_MWEB') or die('access denied');

$act = getGet('a');

if($act == 'logout'){
    list($ret,$msg) = app('user')->setLogout();
    
    $view->assign('redirect',getGet('redirect'));
    $view->assign('msg',$msg);
    $view->display('user/logout.php');
}else{
    if(isPost()){
        $username = safestr(getPost('username'));
        $userpass = getPost('userpass');
        $remember = intval(getPost('remember'));
        $redirect = getPost('redirect');

        if(empty($username)){
            alert('请输入用户名!',false,'',array('field'=>'username'));
        }
        if(empty($userpass)){
            alert('请输入密码!',false,'',array('field'=>'userpass'));
        }
        $user_setting = getSetting('user_setting',true);
        if($user_setting['enable_login_captcha']){
            $captcha_code = getPost('captcha');
            $captcha = new Captcha;
            if(!$captcha_code){
                alert('请输入验证码!',false,'',array('field'=>'captcha'));
            }
            if(!$captcha->check($captcha_code)){
                alert('验证码输入错误!',false,'',array('field'=>'captcha'));
            }
        }

        $expire_time = $remember?CURRENT_TIME+86400*30:0;
        list($uid,$msg,$field) = app('user')->setLogin($username,md5($userpass),$expire_time,false);

        if($uid){
            alert('登录成功!',true, $redirect?$redirect:U('base','index') ,array('othermsg'=>$msg));
        }else{
            alert($msg,false,'',array('field'=>$field));
        }
    }

    $view->assign('redirect',getGet('redirect'));
    $view->assign('flag',$flag);

    //SEO信息
    $site_title = '用户登录 - '.getSetting('site_title');
    $view->assign('site_title',$site_title);

    $view->display('user/login.php');
}