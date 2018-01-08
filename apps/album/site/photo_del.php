<?php 
defined('IN_MWEB') or die('access denied');

checkLogin();

$id = intval(getGet('id'));


$m_photo = M('album_photos');
$info = $m_photo->load($id);
if($info['uid']!=$_G['user']['id']){
    alert('非法操作，没有权限！');
}
if(app('album')->delPhoto($id)){
    alert('删除图片成功！',true,'js_reload');
}else{
    alert('删除图片失败！');
}