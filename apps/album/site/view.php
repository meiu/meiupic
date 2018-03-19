<?php 
defined('IN_MWEB') or die('access denied');

//checkLogin();

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
if($_G['user']['id'] && $photoInfo['uid'] != $_G['user']['id']){
    //TODO: 判断是否关注了好友
    
}

$photoInfo['liked'] = false;
//判断是否喜欢了当前照片
if($_G['user']['id'] && M('album_likes')->findRow('uid='.$_G['user']['id'].' and photo_id='.$id)){
    $photoInfo['liked'] = true;
}

//查看上一张和下一张(当前用户或其他用户开放的图片)
if($photoInfo['uid'] == $_G['user']['id']){
    $where = 'uid = '.$_G['user']['id'];
}else{
    $where = 'priv_type = 0';
}
$prevInfo = $m_photo->findRow(array(
    'where' => $where.' and id>'.$id,
    'order' => 'id asc',
    'limit' => 1
));
$nextInfo = $m_photo->findRow(array(
    'where' => $where.' and id<'.$id,
    'order' => 'id desc',
    'limit' => 1
));
$view->assign('prevInfo',$prevInfo);
$view->assign('nextInfo',$nextInfo);

$view->assign('photoInfo',$photoInfo);
$view->assign('authorInfo',$authorInfo);
$view->assign('cateIndex',app('album')->getCateIndex());
$site_title = $photoInfo['name'].' - '.getSetting('site_title');
$view->assign('site_title',$site_title);
$view->display('album/photo_view.php');
