<?php 
defined('IN_MWEB') or die('access denied');

checkLogin();

$id = intval(getGet('id'));

$info = M('album_photos')->load($id);
if($info['uid']!=$_G['user']['id']){
    alert('非法操作，没有权限！');
}
if(app('album')->updateCover($info['album_id'],$id)){
    alert('设置封面成功！',true);
}else{
    alert('设置封面失败！');
}