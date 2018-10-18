<?php 
defined('IN_MWEB') or die('access denied');

$aid = intval(getGet('aid'));
$page = getGet('page',1);
$tag = getGet('tag');
$keyword = getGet('keyword');
$t = getGet('t');

$m_photo = M('album_photos');

$where = 'deleted=0 and priv_type=0';
$urlparam = array('page'=>'%page%');
if($tag){
    $urlparam['tag'] = $tag;

    //取出TAG
    $tagInfo = M('album_tags')->load($tag,'*','name');
    if(!$tagInfo){
        show404();
    }
    $where .=  " and id in (select rel_id from _mytbl::album_tag_rels where tag_id = ".$tagInfo['id']." and `type`='photo')";
}elseif($keyword){
    $urlparam['keyword'] = htmlspecialchars($keyword);
    $where .=  " and name like '%".$m_photo->escape($keyword,false)."%'";
}else{
    show404();
}

if($t == 'new'){
    $order = 'id desc';
}else{
    $order = 'hits desc';
}
$totalCount = $m_photo->count($where);
$pageurl = U('album','search',$urlparam);

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
    $site_title = '搜索 - '.getSetting('site_title');
    $view->assign('site_title',$site_title);
    $view->assign('totalCount',$totalCount);
    unset($urlparam['page']);
    $view->assign('urlparam',$urlparam);
    $view->display('album/search.php');
}