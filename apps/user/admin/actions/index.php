<?php
defined('IN_MWEB') or die('access denied');

require_once('_submenu.php');

$act = getGet('a','index');
$view->assign('act',$act);

switch ($act) {
    case 'index':
        $page = getGet('page',1);

        $m_user = M('users');

        $totalCount = $m_user->count();
        $pageurl = U('user','index','page=%page%');

        $pager = new Pager($page,C('pageset.admin',15),$totalCount,$pageurl);
        $pager->config(C('page'));
        $limit = $pager->getLimit();
        $view->assign('pagestr',$pager->html());

        $rows = $m_user->findAll(array(
                    'start' => $limit['start'],
                    'limit' => $limit['limit']
                ));

        $view->assign('rows',$rows);

        $view->display('index.php');
        break;
    case 'edit':
        $id = intval(getGet('id'));
        $m_user = M('users');

        if(isPost()){
            $data['username'] = safestr(trim(getPost('username')));
            $data['nickname'] = trim(getPost('nickname'));
            $data['email'] = trim(getPost('email'));
            $data['level'] = intval(getPost('level'));

            if(!$data['username']){
                alert('用户名不能为空！');
            }
            if(!$data['nickname']){
                alert('昵称不能为空！');
            }
            if(!$data['email']){
                alert('Email不能为空！');
            }
            //检查用户名是否重复
            if(0 < $m_user->count("username=".$m_user->escape($data['username']).' and id<>'.$id) ){
                alert('用户名重复！');
            }


            $userpass = getPost('userpass');
            if($userpass && $userpass!=getPost('userpass2')){
                alert('两次密码输入不一致！');
            }
            if($userpass){
                $data['userpass'] = md5($userpass);
            }
            $fields = app('base')->getSetting('user_fields',true);
            foreach($fields as $k=>$v){
                $data[$k] = trim(getPost($k));
            }

            if($m_user->update($id,$data)){
                alert('修改用户成功！',true,U('user','index'));
            }else{
                alert('修改用户失败！');
            }
        }

        $info = $m_user->load($id);

        $fields = app('base')->getSetting('user_fields',true);

        $view->assign('fields',$fields);
        $view->assign('info',$info);
        $view->display('index_edit.php');
        break;
    case 'add':
        $m_user = M('users');
        if(isPost()){
            $data['username'] = safestr(trim(getPost('username')));
            $data['nickname'] = trim(getPost('nickname'));
            $data['email'] = trim(getPost('email'));
            $data['level'] = intval(getPost('level'));

            if(!$data['username']){
                alert('用户名不能为空！');
            }
            if(!$data['nickname']){
                alert('昵称不能为空！');
            }
            if(!$data['email']){
                alert('Email不能为空！');
            }
            $userpass = getPost('userpass');
            if(!$userpass){
                alert('请输入密码！');
            }
            if($userpass!=getPost('userpass2')){
                alert('两次密码输入不一致！');
            }

            //检查用户名是否重复
            if(0 < $m_user->count("username=".$m_user->escape($data['username'])) ){
                alert('用户名重复！');
            }

            $data['userpass'] = md5($userpass);
            $data['regtime'] = time();
            $data['regip'] = getClientIp();

            $fields = app('base')->getSetting('user_fields',true);
            foreach($fields as $k=>$v){
                $data[$k] = trim(getPost($k));
            }
            

            if($m_user->insert($data)){
                alert('添加用户成功！',true,U('user','index'));
            }else{
                alert('添加用户失败！');
            }
        }

        
        $info = $m_user->loadDefault();

        $fields = app('base')->getSetting('user_fields',true);

        $view->assign('fields',$fields);
        $view->assign('info',$info);
        $view->display('index_edit.php');
        break;
    case 'del':
        $id = intval(getGet('id'));
        if($id == $_G['user']['id']){
            alert('你不能删除自己！');
        }

        if(M('users')->delete($id)){
            alert('删除用户成功！',true,U('user','index'));
        }else{
            alert('删除用户失败！');
        }
        break;
}
