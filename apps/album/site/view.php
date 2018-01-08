<?php 
defined('IN_MWEB') or die('access denied');

checkLogin();

$id = intval(getGet('id'));
$m_photo = M('album_photos');
$photoInfo = $m_photo->load($id);
if(!$photoInfo){
    show404();
}
//是否本人可见
if($photoInfo['priv_type']==1 && $photoInfo['uid']!=$_G['user']['id']){
    showInfo('该照片设置了私有，您无权查看！','/','非法权限');
}

$authorInfo = M('users')->load($photoInfo['uid']);

//增加计数
$m_photo->update($id , array('hits'=>array('exp','hits+1')) );

if($photoInfo['exif']){
    $photoInfo['exif'] = (new exif)->parse_exif(unserialize($photoInfo['exif']));
}

$is_followed = false;
//判断用户是否关注了
if($photoInfo['uid'] != $_G['user']['id']){
    //TODO: 判断是否关注了好友
    
}

$view->assign('photoInfo',$photoInfo);
$view->assign('authorInfo',$authorInfo);
$view->assign('cateIndex',app('album')->getCateIndex());
$site_title = '用户中心 - '.getSetting('site_title');
$view->assign('site_title',$site_title);
$view->display('album/photo_view.php');
