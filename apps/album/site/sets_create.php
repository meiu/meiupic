<?php 
defined('IN_MWEB') or die('access denied');

checkLogin();

$m_sets = M('album_sets');
if(isPost()){
    $from = getGet('from');

    $data['name'] = safestr(trim(getPost('name')));
    $data['description'] = trim(getPost('description'));
    $data['create_time'] = CURRENT_TIME;
    $data['uid'] = $_G['user']['id'];
    $data['priv_type'] = intval(getPost('priv_type'));

    if(!$data['name']){
        alert('图集名不能为空！');
    }
    if($m_sets->insert($data)){
        alert('创建图集成功！',true,'js_reload',$from?array('parent_winid'=>$from):array());
    }else{
        alert('创建图集失败！');
    }
}
$view->display('album/sets_edit.php');