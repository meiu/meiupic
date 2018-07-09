<?php 
defined('IN_MWEB') or die('access denied');

checkLogin();

if(isPost()){
    $uid  = $_G['user']['id'];

    $m_user = M('users');
    $data['nickname'] = trim(getPost('nickname'));
    $data['gender'] = getPost('gender');
    $data['description'] = getPost('description');
    
    if(!$data['nickname']){
        alert('昵称不能为空！');
    }
    //检查用户名是否重复
    if(0 < $m_user->count("nickname=".$m_user->escape($data['nickname']).' and id<>'.$uid) ){
        alert('昵称重复！');
    }
    if($m_user->update($uid,$data)){
        //更新用户相关信息
        $fields = app('base')->getSetting('user_fields',true);
        $infodata = array();
        foreach($fields as $k=>$v){
            $infodata[$k] = trim(getPost($k));
        }
        M('users_info')->updateW('uid='.$uid,$infodata);

        alert('修改基本信息成功！',true,U('space','setting'));
    }else{
        alert('修改基本信息失败！');
    }

}else{        
    $fields = app('base')->getSetting('user_fields',true);
    $view->assign('fields',$fields);
    $view->assign('site_title','账户资料 - '.getSetting('site_title'));

    $iinfo = M('users_info')->load($_G['user']['id'],'*','uid');
    if(!$iinfo) $iinfo = M('users_info')->loadDefault();
    $view->assign('iinfo',$iinfo);
    $view->display('space/setting.php');
}
