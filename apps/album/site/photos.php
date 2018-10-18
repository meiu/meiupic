<?php 
defined('IN_MWEB') or die('access denied');

$aid = intval(getGet('id'));
$page = getGet('page',1);

//获取相册信息
$albumInfo = M('albums')->load($aid);

if($_G['user']['id']!=$albumInfo['uid'] && $albumInfo['priv_type']!=0){
    showInfo('您无权查看该相册！','/','非法权限');
}

$m_photo = M('album_photos');

$where = 'deleted=0 and priv_type=0 and album_id='.$aid;
$urlparam = array('id'=>$aid,'page'=>'%page%');
$order = 'id desc';

$totalCount = $m_photo->count($where);
$pageurl = U('album','photos',$urlparam);

$pager = new Pager($page,C('pageset.default',15),$totalCount,$pageurl);
$pager->config(C('page'));
$limit = $pager->getLimit();
$view->assign('pagestr',$pager->html());

$rows = $m_photo->findAll(array(
    'where' => $where,
    'start' => $limit['start'],
    'limit' => $limit['limit'],
    'order' => $order
));

if($rows){
    $userInfos = M('users')->select('id in ('.implode(',',array_unique(array_column($rows, 'uid'))).')')->getAsIndex('id');
    if($_G['user']['id']){
        $likeIndexs = M('album_likes')->select('uid='.$_G['user']['id'].' and photo_id in ('.implode(',',array_column($rows, 'id')).')')->getAsIndex('photo_id');
    }
    foreach ($rows as $key => $value) {
        $rows[$key]['user'] = $userInfos[$value['uid']];
        $rows[$key]['liked'] = false;
        if($_G['user']['id'] && isset($likeIndexs[$value['id']])){
            $rows[$key]['liked'] = true;
        }
    }
}
$view->assign('rows',$rows);

if(isAjax()){
    echo json_encode(array('status'=>'ok','page'=>$page,'html'=>$view->fetch('album/normal_list.php'),'pagehtml'=>$pager->html()));
    exit;
}else{
    //获取专辑作者
    $authorInfo = M('users')->load($albumInfo['uid']);
    $site_title = $albumInfo['name'].' - '.getSetting('site_title');
    
    $cateInfo = M('album_cate')->load($albumInfo['cate_id']);
    $view->assign('cateInfo',$cateInfo);
    $view->assign('albumInfo',$albumInfo);
    $view->assign('authorInfo',$authorInfo);
    $view->assign('site_title',$site_title);
    $view->assign('totalCount',$totalCount);
    $view->display('album/photos.php');
}