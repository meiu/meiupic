<?php
defined('IN_MWEB') or die('access denied');

$act = getGet('a');
if($act == 'dologin'){
    $username = safestr(trim(getPost('username')));
    $userpass = getPost('userpass');

    if(empty($username)){
        redirect(U('user','login','flag=3'));
    }
    if(empty($userpass)){
        redirect(U('user','login','flag=4'));
    }
    $user_setting = getSetting('user_setting',true);
    if($user_setting['enable_admin_captcha']){
        $captcha_code = getPost('captcha');
        $captcha = new Captcha;
        if(!$captcha_code){
            redirect(U('user','login','flag=6'));
        }
        if(!$captcha->check($captcha_code)){
            redirect(U('user','login','flag=7'));
        }
    }
    list($ret,$msg) = app('user')->setLogin($username,md5($userpass),0,true);
    if($ret){
        redirect(U('base','index'));
    }else{
        redirect(U('user','login','flag=5'));
    }
}elseif($act == 'logout'){
    app('user')->setLogout(true);
    
    redirect(U('user','login','flag=1'));
}else{
    $flag = getGet('flag');

    $view->decorate(false);
    $view->assign('flag',$flag);
    $view->display('login.php');
}