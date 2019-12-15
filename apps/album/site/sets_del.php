<?php 
defined('IN_MWEB') or die('access denied');

checkLogin();

$id = intval(getGet('id'));

$info = M('album_sets')->load($id);
if($info['uid']!=$_G['user']['id']){
    alert('非法操作，没有权限！');
}

if(app('album')->delSet($id)){
    alert('删除图集成功！',true,'js_reload');
}else{
    alert('删除图集失败！');
}