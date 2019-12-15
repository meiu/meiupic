<?php 
defined('IN_MWEB') or die('access denied');

$id = intval(getGet('id'));
$page = getGet('page',1);

//获取图集信息
$setsInfo = M('album_sets')->load($id);

$m_photo = M('album_photos');

$where = 'set_id='.$id;
$urlparam = array('id'=>$id,'page'=>'%page%');
$order = 'sp.add_time desc';

$totalCount =  M('album_set_photos')->count($where);
$pageurl = U('album','sets_photos',$urlparam);

$pager = new Pager($page,C('pageset.default',15),$totalCount,$pageurl);
$pager->config(C('page'));
$limit = $pager->getLimit();
$view->assign('pagestr',$pager->html());

$rows = $m_photo->findAll(array(
    'field'=>'p.*',
    'table' => '#album_set_photos as sp inner join #album_photos as p on sp.photo_id = p.id',
    'where' => $where,
    'start' => $limit['start'],
    'limit' => $limit['limit'],
    'order' => $order
));

if($rows){
    $userInfos = M('users')->select('id in ('.implode(',',array_unique(array_column($rows, 'uid'))).')')->getAsIndex('id');
    foreach ($rows as $key => $value) {
        $rows[$key]['user'] = $userInfos[$value['uid']];
        $rows[$key]['liked'] = false;
    }
}
$view->assign('set_id',$id);
$view->assign('rows',$rows);

if(isAjax()){
    echo json_encode(array('status'=>'ok','page'=>$page,'html'=>$view->fetch('album/sets_photo_list.php'),'pagehtml'=>$pager->html()));
    exit;
}else{
    //获取专辑作者
    $authorInfo = M('users')->load($setsInfo['uid']);
    $site_title = $setsInfo['name'].' - '.getSetting('site_title');
    
    $view->assign('setsInfo',$setsInfo);
    $view->assign('authorInfo',$authorInfo);
    $view->assign('site_title',$site_title);
    $view->assign('totalCount',$totalCount);
    $view->display('album/sets_photos.php');
}