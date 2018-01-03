<?php

defined('IN_MWEB') or die('access denied');

$dirname = trim(getGet('dirname'));
$id = intval(getGet('id'));

$m_album_cate = M('album_cate');
if($dirname){
    $cateinfo = $m_album_cate->findRow("dirname=".$m_album_cate->escape($dirname));
}else{
    $cateinfo = $m_album_cate->load($id);
}
if(!$cateinfo){
    show404();
}
print_R($cateinfo);