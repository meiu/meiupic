<?php 
defined('IN_MWEB') or die('access denied');

checkLogin();

$id = intval(getGet('id'));

$m_album = M('albums');
$info = $m_album->load($id);
if(isPost()){
    $data['name'] = safestr(trim(getPost('name')));
    $data['tags'] = trim(getPost('tags'));
    $data['description'] = trim(getPost('description'));
    $data['cate_id'] = intval(getPost('cate_id'));
    $data['up_time'] = time();
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

    $info = $m_album->load($id);
    if($m_album->update($id,$data)){
        //获取相册封面
        if($info['priv_type']!=$data['priv_type']){//更新所有图片的权限
            M('album_photos')->updateW('album_id='.$id,array('priv_type'=>$data['priv_type']));
        }
        app('album')->updateTags('album',$id,$data['tags'],$info['cover_path'],false);
        
        alert('修改相册成功！',true,'js_reload');
    }else{
        alert('修改相册失败！');
    }
}

$view->assign('info',$info);
$view->assign('cates',app('album')->getCateList());

$view->display('album/album_edit.php');