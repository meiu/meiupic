<?php 
defined('IN_MWEB') or die('access denied');

checkLogin();

$id = intval(getGet('id'));

$page = getGet('page',1);

$search['id'] = $id;
$search['name'] = trim(getRequest('name'));
$search['winid'] = trim(getRequest('winid'));

$m_album = M('albums');
$where = 'deleted=0 and uid='.intval($_G['user']['id']);
if( $search['name'] ){
    if( is_numeric($search['name']) ){
        $where .= ' and id ='.intval($search['name']);
    }else{
        $keyword = trim($search['name'],'*');
        $where .= " and name like '%".$m_album->escape($keyword,false)."%'";
    }
}
$totalCount = $m_album->count($where);
$pageurl = U('album','photo_move',array_merge($search,array('page'=>'%page%')));

$pager = new Pager($page,12,$totalCount,$pageurl);
$pager->config(C('adminpage'));
$limit = $pager->getLimit();
$view->assign('pagestr',$pager->html());

$rows = $m_album->findAll(array(
    'where' => $where,
    'start' => $limit['start'],
    'limit' => $limit['limit'],
    'order' => 'id desc'
));

$view->assign('rows',$rows);
$view->assign('search',$search);
$view->assign('totalCount',$totalCount);
$view->display('album/photo_move.php');