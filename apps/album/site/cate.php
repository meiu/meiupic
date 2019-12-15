<?php

defined('IN_MWEB') or die('access denied');

$dirname = trim(getGet('dirname'));
$id = intval(getGet('id'));

$m_album_cate = M('album_cate');
if($dirname){
    $cateinfo = $m_album_cate->findRow("dirname=".$m_album_cate->escape($dirname));
}else{
    $cateinfo = $m_album_cate->load($id);
}
if(!$cateinfo){
    show404();
}

$t = getGet('t');
$page = getGet('page',1);
$m_album = M('albums');

if($t=='new'){
    $order = 'id desc';
}else{
    $order = 'hits desc';
}

$where = 'deleted=0 and priv_type=0';
$where .= ' and cate_id ='.intval($cateinfo['id']);

if($t=='editor'){
    $where .=  ' and recommended=1';
    $order = 'recommend_time desc';
}else{
    $order = 'id desc';
}

$totalCount = $m_album->count($where);
$pageurl = app('album')->makeCateLink($cateinfo,array('page'=>'%page%'));

$pager = new Pager($page,C('pageset.default',15),$totalCount,$pageurl);
$pager->config(C('page'));
$limit = $pager->getLimit();
$view->assign('pagestr',$pager->html());

$rows = $m_album->findAll(array(
    'where' => $where,
    'start' => $limit['start'],
    'limit' => $limit['limit'],
    'order' => $order
));
if($rows){
    $userInfos = M('users')->select('id in ('.implode(',',array_unique(array_column($rows, 'uid'))).')')->getAsIndex('id');
    foreach ($rows as $key => $value) {
        $rows[$key]['user'] = $userInfos[$value['uid']];
    }
}

$view->assign('rows',$rows);

if(isAjax()){
    echo json_encode(array('status'=>'ok','page'=>$page,'html'=>$view->fetch('album/normal_list.php'),'pagehtml'=>$pager->html()));
    exit;
}else{
    $site_title = $cateinfo['name'].' - '.getSetting('site_title');
    $view->assign('site_title',$site_title);
    $view->assign('totalCount',$totalCount);
    $view->assign('cateinfo',$cateinfo);
    $view->display('album/cate.php');
}