<?php 
defined('IN_MWEB') or die('access denied');

//checkLogin();

$id = intval(getGet('id'));
$m_album = M('albums');
$albumInfo = $m_album->load($id);
if(!$albumInfo){
    show404();
}
//是否本人可见
if($albumInfo['priv_type']==1 && $albumInfo['uid']!=$_G['user']['id']){
    showInfo('该作品设置了私有，您无权查看！','/','非法权限');
}

$authorInfo = M('users')->load($albumInfo['uid']);

//增加计数
$m_album->update($id , array('hits'=>array('exp','hits+1')) );

$is_followed = true;
//判断用户是否关注了
if($_G['user']['id'] && $albumInfo['uid'] != $_G['user']['id']){
    $is_followed = M('users_follow')->findRow('follow_uid='.$_G['user']['id'].' and uid='.$albumInfo['uid']);
}

$albumInfo['liked'] = false;
//判断是否喜欢了当前照片
if($_G['user']['id'] && M('album_likes')->findRow('uid='.$_G['user']['id'].' and album_id='.$id)){
    $albumInfo['liked'] = true;
}

$m_photo = M('album_photos');

//查看上一个作品和下一个作品(当前用户或其他用户开放的作品)
if($albumInfo['uid'] == $_G['user']['id']){
    $where = 'uid = '.$_G['user']['id'];
}else{
    $where = 'priv_type = 0';
}

$prevInfo = $m_album->findRow(array(
    'where' => $where.' and id>'.$id,
    'order' => 'id asc',
    'limit' => 1
));
$nextInfo = $m_album->findRow(array(
    'where' => $where.' and id<'.$id,
    'order' => 'id desc',
    'limit' => 1
));

$view->assign('prevInfo',$prevInfo);
$view->assign('nextInfo',$nextInfo);

//取出所有的照片吧
$photos = $m_photo->findAll(array(
    'where' => "album_id=".$albumInfo['id']." AND deleted=0",
    'fields' => 'id,path,width,height,exif',
    'order' => 'id asc'
));

$exif_lib = new exif;
foreach ($photos as $key => $value) {
    if($value['exif']){
        $photos[$key]['exif'] = $exif_lib->parse_exif(unserialize($value['exif']));
    }
}

$view->assign('is_followed',$is_followed);
$view->assign('albumInfo',$albumInfo);
$view->assign('authorInfo',$authorInfo);
$view->assign('photoInfo',$photos[0]);
$view->assign('photos',$photos);
$view->assign('cateIndex',app('album')->getCateIndex());

$site_title = $albumInfo['name'].' - '.getSetting('site_title');
$view->assign('site_title',$site_title);
$view->display('album/album_detail.php');
