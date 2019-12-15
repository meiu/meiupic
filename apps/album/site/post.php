<?php 
defined('IN_MWEB') or die('access denied');

checkLogin();

if(isPost()){
    //
    $id = getGet('id');
    $title = getPost('title');
    $cate_id = intval(getPost('cate_id'));
    $priv_type = intval(getPost('priv_type'));
    $description = trim(getPost('description'));
    $tags = trim(getPost('tags'));

    $pic_ids = array_intval(getPost('pic_ids'));

    //创建相册 
    $data['name'] = $title;
    $data['uid'] = $_G['user']['id'];
    $data['tags'] = $tags;
    $data['description'] = $description;
    $data['cate_id'] = $cate_id;
    $data['create_time'] = $data['up_time'] = CURRENT_TIME;
    $data['priv_type'] = $priv_type;
    
    if(!$data['name']){
        alert('标题不能为空！');
    }
    if(!$data['cate_id']){
        alert('请选择分类！');
    }

    $m_album =  M('albums');
    $m_photos =  M('album_photos');
    if($id){
        //保存基本信息并更新照片排序
        if($m_album->update($id,$data)){
            $info = $m_album->load($id);
            app('album')->updateTags('album',$id,$data['tags'],$info['cover_path'],false);

            //TODO: 更新排序
            alert('保存成功！',true,U('album','space','id='.$_G['user']['id']));
        }else{
            alert('保存失败！');
        }
    }else{
        if($m_album->insert($data)){
            $album_id = $m_album->insertId();
            
            //保存图片信息
            $m_photos->updateW('id in ('.implode(',', $pic_ids).')',array('name'=>$title,'cate_id'=>$cate_id,'album_id'=>$album_id,'priv_type'=>$priv_type,'tags'=>$data['tags']));
            app('album')->updatePhotoNum($album_id);
            app('album')->updateCover($album_id);

            if($data['tags']){
                //保存图片tag，取消保存图片tag吧
                /*foreach ($pic_ids as $picid) {
                    $info = $m_photos->load($picid);
                    app('album')->updateTags('photo',$picid,$data['tags'],$info['path'],true);
                }*/
                app('album')->updateTags('album',$album_id,$data['tags'],'',true);
            }

            alert('保存成功！',true,U('album','space','id='.$_G['user']['id']));
        }else{
            alert('保存失败！');
        }
    }
}else{
    if(@$_G['settings']['album_email_notactive_cannotpost'] && !$_G['user']['email_actived']){
        showInfo('Email未激活不允许上传！',U('space','account'));
    }
    if(@$_G['settings']['album_mobile_notactive_cannotpost'] && !$_G['user']['mobile_actived']){
        showInfo('手机未绑定不允许上传！',U('space','account'));
    }

    $album_id = intval(getGet('id'));

    //分类列表
    $cates = app('album')->getCateList();
    $view->assign('cates',$cates);

    $view->assign('albumInfo',M('albums')->load($album_id));
    $view->assign('album_id',$album_id);

    //取出当前相册的所有的图片(或不属于任何相册的图片)
    $photo_list = M('album_photos')->findAll('album_id='.$album_id);
    $view->assign('photo_list',$photo_list);

    $view->display('album/post.php');
}