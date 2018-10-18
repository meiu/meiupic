<?php 
defined('IN_MWEB') or die('access denied');

$uid = intval(getGet('id'));

$page = getGet('page',1);
$m_users_follow = M('users_follow');

$uinfo = M('users')->load($uid);
$view->assign('uinfo',$uinfo);

$totalCount = $m_users_follow->count('follow_uid ='.$uid);
$pageurl = U('friend','friends',array('page'=>'%page%','id'=>$uid));

$pager = new Pager($page,C('pageset.default',15),$totalCount,$pageurl);
$pager->config(C('page'));
$limit = $pager->getLimit();
$view->assign('pagestr',$pager->html());

$rows = $m_users_follow->findAll(array(
    'field'=>'u.*',
    'table' => '#users_follow as f inner join #users as u on f.uid = u.id',
    'where' => 'f.follow_uid='.$uid,
    'order' => 'f.follow_time desc'
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
$view->assign('uid',$uid);

if(isAjax()){
    echo json_encode(array('status'=>'ok','page'=>$page,'html'=>$view->fetch('user/user_list.php'),'pagehtml'=>$pager->html()));
    exit;
}else{
    $site_title = '关注列表 - '.getSetting('site_title');
    $view->assign('site_title',$site_title);
    $view->assign('totalCount',$totalCount);
    $view->display('friend/friends.php');
}