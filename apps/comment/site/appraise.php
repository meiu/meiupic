<?php 
defined('IN_MWEB') or die('access denied');

$mod = safestr(getGet('mod'));
$id = intval(getGet('id'));

$postid = intval(getPost('postid'));
$type = getPost('type');

$m_comment = M('comments');

if($type == 'support'){
    $m_comment->update($postid,array('support'=>array('exp','support+1')) );
    $row = $m_comment->load($postid);
    $num = $row['support'];
}else{
    $m_comment->update($postid,array('object'=>array('exp','object+1')) );
    $row = $m_comment->load($postid);
    $num = $row['object'];
}

alert($num,true);