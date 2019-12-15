<?php 
defined('IN_MWEB') or die('access denied');

checkLogin();

if(!isPost()){
    exit;
}
$name = $_FILES['file']['name'];
$size = $_FILES['file']['size'];
$tmpfile = $_FILES['file']['tmp_name'];
$error = $_FILES['file']['error'];


$fileext = fileext($name);
if(!in_array($fileext,array('jpg','jpeg','gif','png'))){//如果不是支持的文件类别直接清除临时文件
    die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "不支持的文件格式."}, "id" : "id"}');
}
if($error>0){
    die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "上传失败."}, "id" : "id"}');
}

if($_G['user']['id']>100){
    $pre = substr($_G['user']['id'], 0,3);
}elseif($_G['user']['id']>10){
    $pre = substr($_G['user']['id'], 0,2);
}else{
    $pre = $_G['user']['id'];
}

$newfacever = $_G['user']['facever']+1;
$path = 'avatar/'.$pre.'/'.$_G['user']['id'].'_'.$newfacever.'.jpg';
$old_path = 'avatar/'.$pre.'/'.$_G['user']['id'].'_'.$_G['user']['facever'].'.jpg';

$img = image::instance();
$img->load($tmpfile);
$img->resizeCut(200,200);
$img->save($tmpfile);

$storagelib = storage::instance();
if($storagelib->save($tmpfile,$path)){//上传成功
    M('users')->update($_G['user']['id'],array('facever'=>$newfacever));
    //删除旧的背景
    $storagelib->delete($old_path);

    die('{"jsonrpc" : "2.0", "result" : "null", "id" : "id"}');
}else{
    die('{"jsonrpc" : "2.0", "error" : {"code": 104, "message": "上传失败！"}, "id" : "id"}');
}