<?php 
defined('IN_MWEB') or die('access denied');

checkLogin();

$id = intval(getRequest('id'));
$act = getGet('act');

$info = M('album_photos')->load($id);
if($info['uid']!=$_G['user']['id']){
    alert('非法操作，没有权限！');
}

if($act == 'delete'){
    if(app('album')->delPhoto($id)){
        if($info['album_id']){
            //删除某个照片后更新相册数量
            app('album')->updatePhotoNum($info['album_id']);
        }
        alert('删除图片成功！',true,'js_reload');
    }else{
        alert('删除图片失败！');
    }
}elseif($act == 'setcover'){
    if(app('album')->updateCover($info['album_id'],$id)){
        alert('设置封面成功！',true);
    }else{
        alert('设置封面失败！');
    }
}