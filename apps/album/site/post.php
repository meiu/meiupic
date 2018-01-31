<?php 
defined('IN_MWEB') or die('access denied');

checkLogin();

if(isPost()){
    $title = getPost('title');
    $cate_id = intval(getPost('cate_id'));
    $priv_type = intval(getPost('priv_type'));
    $description = trim(getPost('description'));
    $tags = trim(getPost('tags'));

    $pic_ids = getPost('pic_ids');

    //创建相册 
    $data['name'] = $title;
    $data['uid'] = $_G['user']['id'];
    $data['tags'] = $tags;
    $data['description'] = $description;
    $data['cate_id'] = $cate_id;
    $data['create_time'] = $data['up_time'] = time();
    $data['priv_type'] = $priv_type;
    
    if(!$data['name']){
        alert('标题不能为空！');
    }
    if(!$data['cate_id']){
        alert('请选择分类！');
    }

    $m_album =  M('albums');
    $m_photos =  M('album_photos');
    if($m_album->insert($data)){
        $album_id = $m_album->insertId();
        
        //保存图片信息
        $m_photos->updateW('id in ('.implode(',', $pic_ids).')',array('name'=>$title,'cate_id'=>$cate_id,'album_id'=>$album_id,'priv_type'=>$priv_type));
        app('album')->updatePhotoNum($album_id);
        app('album')->updateCover($album_id);

        if($data['tags']){
            //保存图片tag
            foreach ($pic_ids as $picid) {
                $info = $m_photos->load($picid);
                app('album')->updateTags('photo',$picid,$data['tags'],$info['path'],true);
            }

            app('album')->updateTags('album',$album_id,$data['tags'],'',true);
        }

        alert('保存成功！',true,U('album','my'));
    }else{
        alert('保存失败！');
    }
}else{
    if(@$_G['settings']['album_email_notactive_cannotpost'] && !$_G['user']['email_actived']){
        showInfo('Email未激活不允许上传！',U('my','account'));
    }
    if(@$_G['settings']['album_mobile_notactive_cannotpost'] && !$_G['user']['mobile_actived']){
        showInfo('手机未绑定不允许上传！',U('my','account'));
    }

    $cates = app('album')->getCateList();
    $view->assign('cates',$cates);
    //分类列表
    $view->display('album/post.php');
}