<?php 
defined('IN_MWEB') or die('access denied');

checkLogin();

if(isPost()){
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
}else{
    $view->display('my/account.php');
}