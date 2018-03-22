<?php 
defined('IN_MWEB') or die('access denied');

$page = getGet('page',1);

$m_tag = M('album_tags');

$where = 'recommended=1';
$urlparam = array('page'=>'%page%');

$totalCount = $m_tag->count($where);
$pageurl = U('album','tags',$urlparam);

$pager = new Pager($page,C('pageset.default',15),$totalCount,$pageurl);
$pager->config(C('page'));
$limit = $pager->getLimit();
$view->assign('pagestr',$pager->html());

$rows = $m_tag->findAll(array(
    'where' => $where,
    'start' => $limit['start'],
    'limit' => $limit['limit'],
    'order' => 'sort desc'
));

$view->assign('rows',$rows);

if(isAjax()){
    echo json_encode(array('status'=>'ok','page'=>$page,'html'=>$view->fetch('album/tag_list.php'),'pagehtml'=>$pager->html()));
    exit;
}else{
    $site_title = '热门标签 - '.getSetting('site_title');
    $view->assign('site_title',$site_title);
    $view->display('album/tags.php');
}