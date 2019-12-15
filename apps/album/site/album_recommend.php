<?php 
defined('IN_MWEB') or die('access denied');

checkLogin();

$id = intval(getGet('id'));

if($_G['user']['level']<80){
    alert('非法操作，没有权限！');
}
if(M('albums')->update($id,array('recommended'=>1,'recommend_time'=>CURRENT_TIME))){
    alert('推荐作品成功！',true);
}else{
    alert('推荐作品失败！');
}