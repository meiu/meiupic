<?php

defined('IN_MWEB') or die('access denied');

$mod = safestr(getGet('mod'));
$id = intval(getGet('id'));

//判断是否关闭了评论
$setting = app('base')->getSetting('comment_setting',true);
if(!$setting['open_comment']){
    alert('对不起，评论已关闭！');
}
//判断是否登录
if($_G['user']){
    $data['uid'] = $_G['user']['id'];
    $data['author'] = $_G['user']['nickname'];
    $data['email'] = $_G['user']['email'];
}else{
    $data['uid'] = 0;
    $data['author'] = safestr(trim(getPost('author')));
    $data['email'] = safestr(trim(getPost('email')));

    if(!$setting['allow_guest']){
        alert('请先登录！');
    }
    if(!$data['author']){
        alert('请输入昵称！');
    }
    if(!$data['email']){
        alert('请输入Email！');
    }
    if(!isEmail($data['email'])){
        alert('请输入正确的Email！');
    }
}
if($setting['enable_captcha']){
    $captcha_code = getPost('captcha');
    if(!$captcha_code){
        alert('请输入验证码！');
    }
    $captcha = new Captcha();
    if(!$captcha->check($captcha_code)){
        alert('验证码输入错误！');
    }
}
$data['content'] = safestr(trim(getPost('content')));
if(!$data['content']){
    alert('评论内容不能为空！');
}

$data['mod'] = $mod;
$data['rel_id'] = $id;
$data['parent_id'] = intval(getPost('par_id',0));//上级id

if(!$data['mod'] || !$data['rel_id']){
    alert('参数有误！');
}
$data['addtime'] = time();
$data['ip'] = getClientIp();

//判断是否需要审核
$needaudit = $setting['need_audit'];
if($needaudit){
    $data['status'] = 0;
}else{
    $data['status'] = 1;
}

$m_comment = M('comments');
doAction('comment_post', $data);
if($m_comment->insert($data)){
    $cid = $m_comment->insertId();
    doAction('comment_saved', $data, $cid);
    
    alert('评论成功！'.($needaudit?'需要审核通过后才能看到您的评论！':''),true);
}else{
    alert('评论失败！');
}