<?php

Class UserClass{
    //初始化用户登录状态
    public function init(){
        $cookie_name = C('cookie.auth_name');
        $cookie_auth_key = C('cookie.auth_key');

        if(!isset($_COOKIE[$cookie_name])){
            return false;
        }

        $myauth = authcode($_COOKIE[$cookie_name],'DECODE',$cookie_auth_key);

        $auth = explode("\t", $myauth);
        $uid = isset($auth[0])?$auth[0]:0;
        $uname = isset($auth[1])?$auth[1]:0;
        $upass = isset($auth[2])?$auth[2]:'';

        $m_user = M('users');
        $user_info = $m_user->load($uid);

        if(!$user_info){
            return false;
        }else{
            if($user_info['userpass'] == $upass){    
                return $user_info;
            }else{
                return false;
            }
        }
    }
    //设置用户登录
    public function setLogin($username,$userpass,$expire_time=0,$is_admin = false){
        $m_user = M('users');
        $user_info = $m_user->findRow("username=".$m_user->escape($username));

        if(!$user_info){
            return array(false,'用户名不存在！','username');
        }

        if($user_info['userpass'] != $userpass){
            return array(false,'密码错误！','userpass');
        }

        if($is_admin && $user_info['level'] != '99'){
            return array(false,'你没有权限！','');
        }

        if($is_admin){
            $_SESSION['is_admin'] = true;
        }else{
            $_SESSION['is_admin'] = false;
        }

        $cookie_name = C('cookie.auth_name');
        $cookie_auth_key = C('cookie.auth_key');
        $cookie_domain = C('cookie.domain');
        $cookie_path = C('cookie.path');

        $myauth = authcode($user_info['id']."\t".$username."\t".$userpass,'ENCODE',$cookie_auth_key);
        setcookie($cookie_name,$myauth,$expire_time,$cookie_path,$cookie_domain);

        //修改最后登录时间
        $update['logintime'] = CURRENT_TIME;
        $update['loginip'] = getClientIp();
        $m_user->update($user_info['id'],$update);

        return array($user_info['id'],'','');
    }
    //设置用户退出
    public function setLogout($is_admin = false){
        if(!$is_admin){
            $cookie_name = C('cookie.auth_name');
            $cookie_domain = C('cookie.domain');
            $cookie_path = C('cookie.path');
            setcookie($cookie_name,'',- 86400 * 365,$cookie_path,$cookie_domain);
        }
        
        $_SESSION['is_admin'] = false;
        session_destroy();
        
        return array(true,'');
    }

    public function register($data){
        $m_user = M('users');
        //检查用户名是否重复
        if(0 < $m_user->count("username=".$m_user->escape($data['username'])) ){
            $ret = false;
            $msg = '该用户名已经被注册了！';
            $field = 'username';
            return array($ret,$msg,$field);
        }
        if(0 < $m_user->count("email=".$m_user->escape($data['email'])) ){
            $ret = false;
            $msg = '该Email已经被注册了！';
            $field = 'email';
            return array($ret,$msg,$field);
        }

        $data['regtime'] = CURRENT_TIME;
        $data['regip'] = getClientIp();

        $data['level'] = app('base')->getSetting('default_user_level');

        $ret = $m_user->insert($data);
        if($ret){
            $msg = '';
            return array($ret,$msg,'');
        }else{
            $msg = '注册失败，请检查原因后重新提交！';
            return array($ret,$msg,'');
        }
    }
    //检查后台用户状态
    public function checkAdminSession($uinfo){
        if(!$uinfo){
            return false;
        }
        if($uinfo['level'] != '99'){
            return false;
        }
        if(!isset($_SESSION['is_admin'])){
            return false;
        }
        if(!$_SESSION['is_admin']){
            return false;
        }
        return true;
    }

    //获取用户的头像
    public function getAvatar($uid,$email=''){
        return $email?'http://www.gravatar.com/avatar/'.md5($email).'?rating=G&size=48&d=mm':S('user','images/user_normal.png');
    }
}