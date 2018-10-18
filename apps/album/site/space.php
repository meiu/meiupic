<?php 
defined('IN_MWEB') or die('access denied');

$uid = intval(getGet('id'));
$aid = intval(getGet('aid'));
$page = getGet('page',1);
$m_photo = M('album_photos');

$uinfo = M('users')->load($uid);
$view->assign('uinfo',$uinfo);

$where = 'deleted=0';
$where .= ' and uid ='.$uid;
$urlparam = array('page'=>'%page%','id'=>$uid);
if($aid){
    $where .= ' and album_id = '.$aid;
    //获取相册信息
    $albumInfo = M('albums')->load($aid);

    if($albumInfo['priv_type'] != 0 && $_G['user']['id']!=$albumInfo['uid']){
        showInfo('您无权查看该相册！','/','非法权限');
    }
    $view->assign('albumInfo',$albumInfo);
    $urlparam['aid'] = $aid;
}
if($G_['user']['id'] != $uid){//访客，那么不取出私人照片
    $where .= ' and priv_type=0';
}

$totalCount = $m_photo->count($where);
$pageurl = U('album','space',$urlparam);

$pager = new Pager($page,C('pageset.default',15),$totalCount,$pageurl);
$pager->config(C('page'));
$limit = $pager->getLimit();
$view->assign('pagestr',$pager->html());

$rows = $m_photo->findAll(array(
    'where' => $where,
    'start' => $limit['start'],
    'limit' => $limit['limit'],
    'order' => 'id desc'
));
$view->assign('rows',$rows);
$view->assign('uid',$uid);

if(isAjax()){
    echo json_encode(array('status'=>'ok','page'=>$page,'html'=>$view->fetch('album/space_photo_list.php'),'pagehtml'=>$pager->html()));
    exit;
}else{
    $site_title = (empty($albumInfo)?'全部图片':$albumInfo['name']).' - 用户中心 - '.getSetting('site_title');
    $view->assign('site_title',$site_title);
    $view->assign('totalCount',$totalCount);
    $view->display('album/space.php');
}