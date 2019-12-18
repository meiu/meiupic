<?php 
defined('IN_MWEB') or die('access denied');

checkLogin();

$photo_id = intval(getGet('photo_id'));
$set_id = intval(getGet('set_id'));

$setInfo = M('album_sets')->load($set_id);
if(!$setInfo){
    alert('图集不存在！');
}
if($setInfo['uid']!=$_G['user']['id']){
    alert('非法操作，目标图集不属于您！');
}

if(M('album_set_photos')->deleteW('set_id='.$set_id.' AND photo_id='.$photo_id)){
    $setData = array(
        'photos_num'=>array('exp','photos_num-1')
    );
    if($setInfo['cover_id']==$photo_id){//如果目标图片是封面
        //取最新的作为封面吧
        $newest = M('album_set_photos')->findRow([
            'where' => 'set_id='.$set_id,
            'order' => 'add_time desc'
        ]);
        if($newest){
            $photoInfo = M('album_photos')->load($newest['photo_id']);
            $setData['cover_id'] = $photoInfo['id'];
            $setData['cover_path'] = $photoInfo['path'];
        }else{
            $setData['cover_id'] = 0;
            $setData['cover_path'] = '';
        }
    }
    M('album_sets')->update($set_id,$setData);
    
    alert('删除成功！',true,'js_reload');
}else{
    alert('删除失败！');
}