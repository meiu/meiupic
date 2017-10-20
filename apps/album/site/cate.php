<?php

defined('IN_MWEB') or die('access denied');

$dirname = trim(getGet('dirname'));
$id = intval(getGet('id'));

$m_album_cate = M('album_cate');
if($dirname){
    $catainfo = $m_album_cate->findRow("dirname=".$m_album_cate->escape($dirname));
}else{
    $catainfo = $m_album_cate->load($id);
}

print_R($catainfo);