<?php 
defined('IN_MWEB') or die('access denied');

checkLogin();

$id = intval(getGet('id'));

$m_album = M('albums');
$info = $m_album->load($id);
if($info['uid']!=$_G['user']['id']){
    alert('非法操作，没有权限！');
}
if(app('album')->delAlbum($id)){
    alert('修改相册成功！',true,U('album','album'));
}else{
    alert('修改相册失败！');
}