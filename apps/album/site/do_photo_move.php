<?php 
defined('IN_MWEB') or die('access denied');

checkLogin();

$id = intval(getRequest('id'));
$album_id = intval(getRequest('album_id'));
if(!$id){
    alert('请选择图片！');
}
if(!$album_id){
    alert('请选择相册！');
}
$albumInfo = M('albums')->load($album_id);
if(!$albumInfo){
    alert('相册不存在！');
}
if($albumInfo['deleted']!=0){
    alert('相册已被删除，无法操作！');
}
if($albumInfo['uid']!=$_G['user']['id']){
    alert('非法操作，目标相册不属于您！');
}

//同时修改图片分类
$updata = array('album_id'=>$album_id,'cate_id'=>$albumInfo['cate_id']);
if($albumInfo['priv_type']==1){//如果相册本身是私有的，那么拉进来的图片也置为私有
    $updata['priv_type'] = 1;
}
$photoInfo = M('album_photos')->load($id);
if($photoInfo['uid']!=$_G['user']['id']){
    alert('非法操作，该图片不属于您！');
}

if($photoInfo['album_id'] == $album_id){
    alert('操作成功，图片位置未发生变化！');
}

$aids = array($photoInfo['album_id'],$album_id);

if( M('album_photos')->update($id,$updata) ){
    foreach($aids as $aid){
        //重置图片数量
        app('album')->updatePhotoNum($aid);
        //重置封面
        if($aid!= $album_id){
            app('album')->updateCover($aid);
        }
    }
    if(!$albumInfo['cover_id']){
        app('album')->updateCover($albumInfo['id']);
    }
    alert('移动图片成功！',true,'js_reload');
}else{
    alert('移动失败！');
}