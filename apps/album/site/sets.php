<?php 
defined('IN_MWEB') or die('access denied');

$page = getGet('page',1);
$m_album_sets = M('album_sets');

$uinfo = M('users')->load($uid);
$view->assign('uinfo',$uinfo);

$where = 'priv_type=0';

$totalCount = $m_album_sets->count($where);
$pageurl = U('album','sets',array('page'=>'%page%','id'=>$uid));

$pager = new Pager($page,C('pageset.default',15),$totalCount,$pageurl);
$pager->config(C('page'));
$limit = $pager->getLimit();
$view->assign('pagestr',$pager->html());

$rows = $m_album_sets->findAll(array(
    'where' => $where,
    'start' => $limit['start'],
    'limit' => $limit['limit'],
    'order' => 'id desc'
));

if($rows){
    $userInfos = M('users')->select('id in ('.implode(',',array_unique(array_column($rows, 'uid'))).')')->getAsIndex('id');
    foreach ($rows as $key => $value) {
        $rows[$key]['user'] = $userInfos[$value['uid']];
    }
}
$view->assign('rows',$rows);
$view->assign('uid',$uid);

if(isAjax()){
    echo json_encode(array('status'=>'ok','page'=>$page,'html'=>$view->fetch('album/sets_list.php'),'pagehtml'=>$pager->html()));
    exit;
}else{
    $site_title = '图集列表 - '.getSetting('site_title');
    $view->assign('site_title',$site_title);
    $view->assign('totalCount',$totalCount);
    $view->display('album/sets.php');
}