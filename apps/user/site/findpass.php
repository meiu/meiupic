<?php

defined('IN_MWEB') or die('access denied');

$act = getGet('a');

if($act == 'step2'){
    if(isPost()){
        $captcha_code = getPost('captcha');
        $newpass = getPost('newpass');
        if(!$_SESSION['FINDPASS_USER']){
            alert('请重新发送验证码!',false,U('user','findpass'));
        }
        if(empty($newpass)){
            alert('请输入密码!',false);
        }
        if(strlen($newpass) < 6){
            alert('密码不得少于6位!',false);
        }

        $username = $_SESSION['FINDPASS_USER'];
        $m_user = M('users');
        if(isEmail($username)){
            $userinfo = $m_user->findRow("email=".$m_user->escape($username));
        }elseif(isMobile($username)){
            $userinfo = $m_user->findRow("mobile=".$m_user->escape($username));
        }else{
            alert('非法操作！');
        }
        //验证验证码
        $row = app('user')->getCode($captcha_code,'findpass',$userinfo['id']);
        if(!$row || $row['active_time']){
            $_SESSION['FINDPASS_USER'] = '';

            alert('验证码已失效，请重新发送验证码!',false,U('user','findpass'));
        }
        //成功那么下一步
        $data['userpass'] = md5( md5( $newpass ).$userinfo['salt']);
        if(M('users')->update($userinfo['id'],$data)){
            $_SESSION['FINDPASS_USER'] = '';

            app('user')->activeCode($row['id']);
            app('user')->setLogout();

            alert('修改密码成功，请重新登录!',true,U('user','login'));
        }else{
            alert('修改失败！');
        }
    }
    if(!$_SESSION['FINDPASS_USER']){
        redirect(U('user','findpass'));
    }
    $site_title = '找回密码 - '.getSetting('site_title');
    $view->assign('site_title',$site_title);
    $view->assign('username',$_SESSION['FINDPASS_USER']);
    $view->display('user/findpass2.php');
}else{
    if(isPost()){
        $username = trim(getPost('username'));
        $captcha_code = getPost('captcha');
        $captcha = new Captcha;
        if(!$captcha_code){
            alert('请输入验证码!');
        }
        if(!$captcha->check($captcha_code)){
            alert('验证码输入错误!');
        }

        $m_user = M('users');

        if(isEmail($username)){
            $userinfo = $m_user->findRow("email=".$m_user->escape($username));
        }elseif(isMobile($username)){
            $userinfo = $m_user->findRow("mobile=".$m_user->escape($username));
        }else{
            alert('请输入邮箱或手机号码！');
        }
        if(!$userinfo){
            alert('未找到相关的用户信息！');
        }
        //创建验证码
        $code = rand(100000,999999);

        //验证码记录
        if(!app('user')->newCode($userinfo['id'],$code,'findpass')){
            alert('操作过于频繁，请1分钟后再试！');
        }

        $_SESSION['FINDPASS_USER'] = $username;
        $msg = '';
        if(isEmail($username)){
            $data = app('base')->parseMail('sendcode.html',array('username'=>$userinfo['userinfo'],'nickname'=>$userinfo['nickname'],'code'=>$code));

            if( app('base')->sendMail($username,$data['title'],$data['content']) ){
                alert('发送成功！',true,U('user','findpass','a=step2'));
            }else{
                alert('发送失败！');
            }
        }else{//发送手机号码
            if(!app('sms')->sendCode($username,$code,$msg)){
                alert($msg);
            }else{
                alert('发送成功！',true,U('user','findpass','a=step2'));
            }
        }

    }
    $site_title = '找回密码 - '.getSetting('site_title');
    $view->assign('site_title',$site_title);

    $view->display('user/findpass.php');
}