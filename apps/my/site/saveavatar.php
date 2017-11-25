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
$path = 'avatar/'.$pre.'/'.$_G['user']['id'].'/large.jpg';
$smallpath = 'avatar/'.$pre.'/'.$_G['user']['id'].'/small.jpg';
$smalltmpfile = $tmpfile.'.small.jpg';
$img = image::instance();
$img->load($tmpfile);
$img->resizeCut(200,200);
$img->save($tmpfile);

$img->resizeCut(48,48);
$img->save($smalltmpfile);

$image = 
$storagelib = storage::instance();
if($storagelib->save($tmpfile,$path) && $storagelib->save($smalltmpfile,$smallpath) ){//上传成功
    @unlink($tmpfile);@unlink($smalltmpfile);
    M('users')->update($_G['user']['id'],array('facever'=>array('exp','facever+1')));

    die('{"jsonrpc" : "2.0", "result" : "null", "id" : "id"}');
}else{
    die('{"jsonrpc" : "2.0", "error" : {"code": 104, "message": "上传失败！"}, "id" : "id"}');
}