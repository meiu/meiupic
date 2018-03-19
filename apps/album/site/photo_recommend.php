<?php 
defined('IN_MWEB') or die('access denied');

checkLogin();

$id = intval(getGet('id'));

if($_G['user']['level']<80){
    alert('非法操作，没有权限！');
}
if(M('album_photos')->update($id,array('recommended'=>1,'recommend_time'=>time()))){
    alert('推荐图片成功！',true);
}else{
    alert('推荐图片失败！');
}