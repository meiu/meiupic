<?php 
defined('IN_MWEB') or die('access denied');

$id = intval(getGet('id'));
$m_photo = M('album_photos');
$m_album = M('albums');
$photoInfo = $m_photo->load($id);
if(!$photoInfo){
    exit;
}
$albumInfo = $m_album->load($photoInfo['album_id']);
$authorInfo = M('users')->load($photoInfo['uid']);

//增加计数
$is_followed = true;
//判断用户是否关注了
if($_G['user']['id'] && $photoInfo['uid'] != $_G['user']['id']){
    $is_followed = M('users_follow')->findRow('follow_uid='.$_G['user']['id'].' and uid='.$photoInfo['uid']);
}

$exif_lib = new exif;

//获取封面信息
if($photoInfo['exif']){
    $photoInfo['exif'] = $exif_lib->parse_exif(unserialize($photoInfo['exif']));
}

$view->assign('is_followed',$is_followed);
$view->assign('albumInfo',$albumInfo);
$view->assign('authorInfo',$authorInfo);
$view->assign('photoInfo',$coverInfo);

$view->display('album/photo_side_info.php');
