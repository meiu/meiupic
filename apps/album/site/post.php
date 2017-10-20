<?php 
defined('IN_MWEB') or die('access denied');

checkLogin();

if(isPost()){
    /*
    pic_ids[]:162
    pic_ids[]:163
    pic_ids[]:164
    pic_ids[]:165
    pic_ids[]:166
    pic_ids[]:167
    pic_ids[]:168
    pic_ids[]:169
    pic_ids[]:170
    title:gdfgdfgfdg
    cate_id:2
    priv_type:1
    description:fhfghfghfghfgh
    tags:ss
    */
    $title = getPost('title');
    $cate_id = intval(getPost('cate_id'));
    $priv_type = intval(getPost('priv_type'));
    $description = trim(getPost('description'));
    $tags = trim(getPost('tags'));

    $pic_ids = getPost('pic_ids');

    //创建相册
    //M('album_photos')->updateW(,array('cate_id'=>￥cate));
    $data['name'] = $title;
    $data['uid'] = $_G['user']['id'];
    $data['tags'] = $tags;
    $data['description'] = $description;
    $data['cate_id'] = $cate_id;
    $data['create_time'] = $data['up_time'] = time();
    $data['priv_type'] = $priv_type;
    
    if(!$data['name']){
        alert('相册名不能为空！');
    }
    if(!$data['cate_id']){
        alert('请选择分类！');
    }
    if($data['priv_type'] == '1'){
        if($data['priv_pass']==''){
            alert('请输入密码！');
        }
    }

    if($m_album->insert($data)){
        $rel_id = $m_album->insertId();
        app('album')->updateTags('album',$rel_id,$data['tags'],'',true);
        alert('添加相册成功！',true,'js_reload');
    }else{
        alert('添加相册失败！');
    }
}else{
    $cates = app('album')->getCateList();
    $view->assign('cates',$cates);
    //分类列表
    $view->display('album/post.php');
}