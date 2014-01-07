<?php

defined('IN_MWEB') or die('access denied');

$mod = safestr(getGet('mod'));
$id = intval(getGet('id'));

$setting = app('base')->getSetting('comment_setting',true);

if($setting['open_comment']){
    $page = getGet('page',1);

    $m_comment = M('comments');

    $where = "`mod`=".$m_comment->escape($mod)." and rel_id=".$id." and status=1";
    //取出所有当前评论及页数
    $page = intval(getGet('page'));
    $totalCount = $m_comment->count($where);
    $pageurl = U('comment','list','mod='.$mod.'&id='.$id.'&page=%page%');

    $pager = new Pager($page,$setting['comments_perpage'],$totalCount,$pageurl);
    $pager->config(C('page'));
    $limit = $pager->getLimit();

    $pager_html = $pager->html();

    $rows = $m_comment->findAll(array(
        'where' => $where,
        'start' => $limit['start'],
        'limit' => $limit['limit'],
        'order' => 'id desc'
    ));
    foreach ($rows as $key => $value) {
        $rows[$key]['addtime'] = date('c',$value['addtime']);
        $rows[$key]['avatar'] = app('user')->getAvatar($value['uid'],$value['email']);
        //读取上级
        if($value['parent_id']){
            $prow = $m_comment->load($value['parent_id']);
            if($prow)
                $rows[$key]['content'] = '<a class="mc-comment-context" data-parent-id="'.$value['id'].'" data-post-id="'.$prow['id'].'">回复 '.$prow['author'].':</a> '.$value['content']; 
        }
    }

    //获取当前登录用户信息
    if($_G['user']){
        $user = array(
            'id' => $_G['user']['id'],
            'nickname' => $_G['user']['nickname'],
            'email' => $_G['user']['email'],
            'avatar' => app('user')->getAvatar($_G['user']['id'],$_G['user']['email'])
        );
    }else{
        $user = array(
            'id' => -1,
            'nickname' => '游客',
            'avatar' => app('user')->getAvatar(0)
        );
    }



    $data = array(
        'status'=>'ok',
        'totalCount'=>$totalCount,
        'pagerHtml' => $pager_html,
        'List' => $rows,
        'user' => $user,
        'options' => array( //基本设置
            'allow_guest'    => $setting['allow_guest']?true:false,
            'allow_reply'    => $setting['allow_reply']?true:false,
            'allow_support'  => $setting['allow_support']?true:false,
            'allow_object'   => $setting['allow_object']?true:false,
            'enable_captcha' => $setting['enable_captcha']?true:false,
        )
    );
}else{
    $data = array(
        'status' => 'closed'
    );
}
echo json_encode($data);