<?php 
defined('IN_MWEB') or die('access denied');

checkLogin();

$id = intval(getGet('id'));

$m_photo = M('album_photos');
$info = $m_photo->load($id);
if(isPost()){
    $data['name'] = safestr(trim(getPost('name')));
    $data['tags'] = trim(getPost('tags'));
    $data['description'] = trim(getPost('description'));
    $data['cate_id'] = intval(getPost('cate_id'));//允许图片单独设置分类
    $data['priv_type'] = intval(getPost('priv_type'));

    if($info['uid']!=$_G['user']['id']){
        alert('非法操作，没有权限！');
    }
    if(!$data['name']){
        alert('相册名不能为空！');
    }
    if(!$data['cate_id']){
        alert('请选择分类！');
    }

    $info = $m_photo->load($id);
    if($m_photo->update($id,$data)){
        app('album')->updateTags('photo',$id,$data['tags'],$info['cover_path'],false);
        
        alert('修改图片成功！',true,'js_reload');
    }else{
        alert('修改图片失败！');
    }
}

$view->assign('info',$info);
$view->assign('cates',app('album')->getCateList());

$view->display('album/photo_edit.php');