<?php 
defined('IN_MWEB') or die('access denied');

checkLogin();

$a = getGet('a');

if(isPost()){
    if($a=='savebind'){//绑定
        $data = array();
        $m_user = M('users');
        if(!$_G['user']['email_actived']){//未激活的可以修改
            $data['email'] = trim(getPost('email'));
            if(empty($data['email'])){
                alert('请输入Email！');
            }
            if(!isEmail($data['email'])){
                alert('Email格式错误！');
            }
            //验证是否重复
            if(0 < $m_user->count("email=".$m_user->escape($data['email']).' and id<>'.$_G['user']['id']) ){
                alert('Email已经有人使用了！');
            }
        }
        if(!$_G['user']['mobile_actived']){//未绑定的可以修改
            $data['mobile'] = trim(getPost('mobile'));
            if(empty($data['mobile'])){
                alert('请输入手机号！');
            }
            if(!isMobile($data['mobile'])){
                alert('手机号格式错误！');
            }
            if(0 < $m_user->count("mobile=".$m_user->escape($data['mobile']).' and id<>'.$_G['user']['id']) ){
                alert('手机号已经有人使用了！');
            }
            $mobile_code = getPost('mobile_code');
            if(empty($data['email'])){
                alert('请输入手机验证码！');
            }
            //验证手机验证码
            if(!app('sms')->checkCode($data['mobile'],$mobile_code)){
                alert('手机验证码输入错误！',false);
            }
            $data['mobile_actived'] = 1;
        }
        if($data){
            $m_user->update($_G['user']['id'],$data);
        }
        alert('保存成功！',true,U('my','account'));
    }elseif($a=='send_activecode'){//   
        if($_G['user']['email_actived']){
            alert('您的帐号已激活！');
        }
        //创建激活码
        $code = createRandStr(16);
        //验证码记录
        if(!app('user')->newCode($_G['user']['id'],$code,'email_active')){
            alert('操作过于频繁，请1分钟后再试！');
        }

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        //链接
        $link1 = $protocol.$_SERVER["HTTP_HOST"].U('user','active','code='.$code.'&sign='.md5($code.$_G['user']['email']));

        $data = app('base')->parseMail('sendactive.html',array('username'=>$_G['user']['username'],'nickname'=>$_G['user']['nickname'],'link1'=>$link1));
        if( app('base')->sendMail($_G['user']['email'],$data['title'],$data['content']) ){
            alert('发送激活邮件成功！',true);
        }else{
            alert('发送激活邮件失败！');
        }

    }else{
        $uid  = $_G['user']['id'];

        $m_user = M('users');

        $data['oldpass'] = getPost('oldpass');
        $data['newpass'] = getPost('newpass');
        $data['passagain'] = getPost('passagain');

        if(empty($data['oldpass'])){
            alert('请输入旧密码！');
        }
        if(empty($data['newpass'])){
            alert('请输入新密码！');
        }
        if($data['newpass']!=$data['passagain']){
            alert('两次密码输入不一致！');
        }
        $user_info = $m_user->load($uid);
        if($user_info['userpass'] != md5(md5($data['oldpass']).$user_info['salt'])){
            alert('旧密码输入错误！');
        }
        if(strlen($data['newpass'])<6){
            alert('密码不得少于6位！');
        }
        $updata['salt'] = substr(uniqid(rand()), -6); 
        $updata['userpass'] = md5(md5($data['newpass']).$updata['salt']);
        if($m_user->update($uid,$updata)){
            alert('修改成功！',true,U('user','login'));
        }else{
            alert('修改失败！');
        }
    }
}else{
    $view->display('my/account.php');
}
