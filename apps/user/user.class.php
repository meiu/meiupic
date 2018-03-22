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
        if(isEmail($username)){
            $user_info = $m_user->findRow("email=".$m_user->escape($username));   
        }elseif(isMobile($username)){
            $user_info = $m_user->findRow("mobile=".$m_user->escape($username)); 
        }else{
            $user_info = $m_user->findRow("username=".$m_user->escape($username)); 
        }

        if(!$user_info){
            return array(false,'用户名不存在！','username');
        }
        if($user_info['status']!=1){
            return array(false,'用户名已被停用！','username');
        }
        if($user_info['userpass'] != md5($userpass.$user_info['salt'])){
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

        $myauth = authcode($user_info['id']."\t".$username."\t".$user_info['userpass'],'ENCODE',$cookie_auth_key);
        setcookie($cookie_name,$myauth,$expire_time,$cookie_path,$cookie_domain);

        //修改最后登录时间
        $update['logintime'] = CURRENT_TIME;
        $update['loginip'] = getClientIp();
        $m_user->update($user_info['id'],$update);

        doAction('user_login',$username,$user_info['id']);
        
        //登录增加积分 ,一天只增加一次
        if($user_info['logintime'] < strtotime(date('Y-m-d'))){
            $this->doPoints($user_info['id'],'user_login');
        }

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
        if(0 < $m_user->count("mobile=".$m_user->escape($data['mobile'])) ){
            $ret = false;
            $msg = '该手机号已经被注册了！';
            $field = 'mobile';
            return array($ret,$msg,$field);
        }

        $data['salt'] = substr(uniqid(rand()), -6); 
        $data['userpass'] = md5($data['userpass'].$data['salt']);

        $data['regtime'] = CURRENT_TIME;
        $data['regip'] = getClientIp();

        $data['level'] = app('base')->getSetting('default_user_level');
        
        $ret = $m_user->insert($data);
        if($ret){
            $uid = $m_user->insertId();

            doAction('user_register',$data['username'],$uid);
            //注册增加积分
            $this->doPoints($uid,'user_register');

            $msg = '';
            return array($uid,$msg,'');
        }else{
            $msg = '注册失败，请检查原因后重新提交！';
            return array($ret,$msg,'');
        }
    }
    //检查后台用户Session
    public function checkAdminSession($uinfo,$level=99){
        if(!$uinfo){
            return false;
        }
        if($uinfo['level'] < $level){
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
    //检查后台登录情况
    public function checkAdminLogin($app,$m){
        global $_G;
        $flag = true;
        if(!$this->checkAdminSession($_G['user'],99) && !in_array($app.'.'.$m, array('user.login','base.captcha','base.upfile'))){
            $flag = false;
        }
        if(!$this->checkAdminSession($_G['user'],88) && $app.'.'.$m=='base.upfile'){
            $flag = false;
        }
        
        if(!$flag){
            if(isAjax()){
                alert('请先登录！',false,U('user','login'));
            }else{
                redirect(U('user','login'));
            }
        }
    }

    //获取用户的头像
    public function getAvatar($user,$size='small'){
        if($user['facever']){
            if($user['id']>100){
                $pre = substr($user['id'], 0,3);
            }elseif($user['id']>10){
                $pre = substr($user['id'], 0,2);
            }else{
                $pre = $user['id'];
            }
            return D('avatar/'.$pre.'/'.$user['id'].'/'.$size.'.jpg?'.$user['facever']);
        }else{
            return S('user','images/avatar_'.$size.'.png');
        }
    }

    public function getGravatar($email){
        return 'https://secure.gravatar.com/avatar/'.md5($email).'?rating=G&size=48&d=mm';
    }

    public function getBannerBg($user){
        if($user['bgver']){
            if($user['id']>100){
                $pre = substr($user['id'], 0,3);
            }elseif($user['id']>10){
                $pre = substr($user['id'], 0,2);
            }else{
                $pre = $user['id'];
            }
            return D('banner/'.$pre.'/'.$user['id'].'.jpg?'.$user['bgver']);
        }else{
            return S('user','images/banner_bg.jpg');
        }
    }
    //增加/减少积分
    //ac==0，增加积分 否则减少积分
    public function changePoints($uid,$points,$ac=0,$name=''){
        $points = intval($points);

        //增加积分日志
        $m_point_log = M('users_point_log');
        $m_point_log->insert(array(
            'uid'=>$uid,
            'name'=>$name,
            'points'=>$points,
            'ac'=>$ac,
            'addtime'=>CURRENT_TIME
            ));

        $m_user = M('users');
        return $m_user->update($uid , array('points'=>array('exp','points'.($ac?'-':'+').$points)) );
    }

    //操作积分
    public function doPoints($uid,$action){
        $m_point = M('users_point');
        $r = $m_point->findRow('pointkey='.$m_point->escape($action));
        if($r){
            return $this->changePoints($uid,$r['points'],$r['ac'],$r['name']);
        }
        return false;
    }

    public function newCode($uid,$code,$type){
        $m = M('users_codes');
        $row = $m->findRow("uid=".intval($uid)." AND code_type=".$m->escape($type)." AND add_time>".(CURRENT_TIME-60));
        if($row){
            return false;
        }

        return $m->insert(array(
            'uid' => $uid,
            'expire_time' => CURRENT_TIME+60*15,
            'code' => $code,
            'code_type' => $type,
            'add_time' => CURRENT_TIME,
            'add_ip' => getClientIp()
        ));
    }

    public function getCode($code,$type,$uid = 0){
        $m = M('users_codes');

        $row = $m->findRow(array(
            'where'=>'code='.$m->escape($code).' AND code_type='.$m->escape($type).' AND expire_time>'.CURRENT_TIME.( $uid?' AND uid='.$uid:'' ),
            'order' => 'id desc'
            )
        );
        return $row;
    }

    public function activeCode($rowid){
        return M('users_codes')->update($rowid,array('active_time'=>CURRENT_TIME,'active_ip'=>getClientIp()));
    }
}