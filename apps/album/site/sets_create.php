<?php 
defined('IN_MWEB') or die('access denied');

checkLogin();

//$id = intval(getGet('id'));

$m_sets = M('album_sets');
//$info = $m_sets->load($id);
if(isPost()){
    $data['name'] = safestr(trim(getPost('name')));
    $data['description'] = trim(getPost('description'));
    $data['create_time'] = CURRENT_TIME;
    $data['uid'] = $_G['user']['id'];
    $data['priv_type'] = intval(getPost('priv_type'));

    /*if($info['uid']!=$_G['user']['id']){
        alert('非法操作，没有权限！');
    }*/
    if(!$data['name']){
        alert('图集名不能为空！');
    }
    if($m_sets->insert($data)){
        alert('创建图集成功！',true,'js_reload');
    }else{
        alert('创建图集失败！');
    }
}

//$view->assign('info',$info);
$view->display('album/sets_edit.php');