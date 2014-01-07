<?php 
defined('IN_MWEB') or die('access denied');

$mod = safestr(getGet('mod'));
$id = intval(getGet('id'));

$m_comment = M('comments');

$where = "`mod`=".$m_comment->escape($mod)." and rel_id=".$id." and status=1";
$count = $m_comment->count($where);

echo 'document.write("'.$count.'");';