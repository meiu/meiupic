<?php

defined('IN_MWEB') or die('access denied');

$code = getGet('code');
$sign = getGet('sign');

$row = app('user')->getCode($code,'email_active');
if(!$row || $row['active_time']){
    showInfo('激活链接已失效，请重新发送！',U('my','account'));
}
$userinfo = M('users')->load($row['uid']);
if(md5($code.$userinfo['email'])!=$sign){
    showInfo('非法的激活链接！',U('base','index'));
}

if(M('users')->update($row['uid'],array('email_actived'=>1))){
    app('user')->activeCode($row['id']);

    showInfo('激活成功！',U('my','account'));
}else{
    showInfo('激活失败！',U('my','account'));
}