<?php 
defined('IN_MWEB') or die('access denied');

$aid = intval(getGet('aid'));
$page = getGet('page',1);
$t = getGet('t');

$m_photo = M('album_photos');

$where = 'deleted=0 and priv_type=0';
$urlparam = array('page'=>'%page%');
if($t){
    $urlparam['t'] = $t;
}

if($t=='editor'){
    $where .=  ' and recommended=1';
    $order = 'recommend_time desc';
}elseif($t == 'fresh'){
    $order = 'id desc';
}else{
    $order = 'hits desc';
}
$totalCount = $m_photo->count($where);
$pageurl = U('album','index',$urlparam);

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
    $site_title = '发现 - '.getSetting('site_title');
    $view->assign('site_title',$site_title);
    $view->assign('totalCount',$totalCount);
    $view->display('album/index.php');
}