<?php 
defined('IN_MWEB') or die('access denied');

checkLogin();

$aid = intval(getGet('aid'));
$page = getGet('page',1);

$m_like = M('album_likes');

$select = array(
    'table' => '#album_likes as l inner join #album_photos as ph on l.photo_id = ph.id',
    'where' => 'ph.deleted=0 and l.uid='.intval($_G['user']['id']),
    'order' => 'l.addtime desc'
);

$urlparam = array('page'=>'%page%');

$totalCount = $m_like->select($select+array('field'=>'count(1) as cnt'))->getOne();
$pageurl = U('album','like',$urlparam);

$pager = new Pager($page,C('pageset.default',15),$totalCount,$pageurl);
$pager->config(C('page'));
$limit = $pager->getLimit();
$view->assign('pagestr',$pager->html());

$rows = $m_like->findAll($select+
            array(
            'field'=>'ph.*',
            'start' => $limit['start'],
            'limit' => $limit['limit']
        ));
if($rows){
    $userInfos = M('users')->select('id in ('.implode(',',array_unique(array_column($rows, 'uid'))).')')->getAsIndex('id');
    foreach ($rows as $key => $value) {
        $rows[$key]['user'] = $userInfos[$value['uid']];
        $rows[$key]['liked'] = true;
    }
}

$view->assign('rows',$rows);

if(isAjax()){
    echo json_encode(array('status'=>'ok','page'=>$page,'html'=>$view->fetch('album/photo_list.php'),'pagehtml'=>$pager->html()));
    exit;
}else{
    $site_title = '喜欢的图片 - 用户中心 - '.getSetting('site_title');
    $view->assign('site_title',$site_title);
    $view->assign('totalCount',$totalCount);
    $view->display('album/my_like.php');
}