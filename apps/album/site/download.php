<?php 
defined('IN_MWEB') or die('access denied');

checkLogin();

$id = intval(getGet('id'));
$m_photo = M('album_photos');

$info = $m_photo->load($id);

if(!$info){
    alert('图片不存在！');
}
if($info['priv_type']==1 && $info['uid']!=$_G['user']['id']){
    alert('非法操作，没有权限！');
}

//读取原图下载
$storage = storage::instance();
$storage->download($info['path']);