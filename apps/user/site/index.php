<?php 
defined('IN_MWEB') or die('access denied');

$page = getGet('page',1);
$t = getGet('t');

$m_user = M('users');

$where = 'status=1';
$urlparam = array('page'=>'%page%');
if($t){
    $urlparam['t'] = $t;
}

if($t=='new'){
    $order = 'id desc';
}else{
    $order = 'followers desc';
}
$totalCount = $m_user->count($where);
$pageurl = U('user','index',$urlparam);

$pager = new Pager($page,C('pageset.default',15),$totalCount,$pageurl);
$pager->config(C('page'));
$limit = $pager->getLimit();
$view->assign('pagestr',$pager->html());

$rows = $m_user->findAll(array(
    'where' => $where,
    'start' => $limit['start'],
    'limit' => $limit['limit'],
    'order' => $order
));

if($rows){
    if($_G['user']['id']){
        $followIndexs = M('users_follow')->select('follow_uid='.$_G['user']['id'].' and uid in ('.implode(',',array_column($rows, 'id')).')')->getAsIndex('uid');
    }
    foreach ($rows as $key => $value) {
        $rows[$key]['followed'] = false;
        if($_G['user']['id'] && isset($followIndexs[$value['id']])){
            $rows[$key]['followed'] = true;
        }
    }
}
$view->assign('rows',$rows);

if(isAjax()){
    echo json_encode(array('status'=>'ok','page'=>$page,'html'=>$view->fetch('user/user_list.php'),'pagehtml'=>$pager->html()));
    exit;
}else{
    $site_title = '摄影师 - '.getSetting('site_title');
    $view->assign('site_title',$site_title);
    $view->display('user/index.php');
}