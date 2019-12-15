<?php 
defined('IN_MWEB') or die('access denied');

$set_id = intval(getGet('set_id'));
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

$is_followed = true;
//判断用户是否关注了
if($_G['user']['id'] && $photoInfo['uid'] != $_G['user']['id']){
    $is_followed = M('users_follow')->findRow('follow_uid='.$_G['user']['id'].' and uid='.$photoInfo['uid']);
}

$photoInfo['liked'] = false;
//判断是否喜欢了当前照片
if($_G['user']['id'] && M('album_likes')->findRow('uid='.$_G['user']['id'].' and photo_id='.$id)){
    $photoInfo['liked'] = true;
}

//查找当前的图集的所有照片
$photos = $m_photo->findAll(array(
    'field'=>'p.id,p.path,p.width,p.height',
    'table' => '#album_set_photos as sp inner join #album_photos as p on sp.photo_id = p.id',
    'where' => 'p.deleted=0 AND set_id='.$set_id,
    'order' => 'sp.add_time desc'
));

$view->assign('is_followed',$is_followed);
$view->assign('set_id',$set_id);
$view->assign('photoInfo',$photoInfo);
$view->assign('photos',$photos);
$view->assign('authorInfo',$authorInfo);
$view->assign('cateIndex',app('album')->getCateIndex());
$site_title = $photoInfo['name'].' - '.getSetting('site_title');
$view->assign('site_title',$site_title);
$view->display('album/sets_view.php');
