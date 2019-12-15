<?php 
defined('IN_MWEB') or die('access denied');

checkLogin();

$id = intval(getGet('id'));

$act = getPost('a');
if($act == 'cancel'){
    $likerow = M('album_likes')->findRow('uid='.$_G['user']['id'].' and album_id='.$id);
    if(!$likerow){
        alert('还未点赞过，无法取消点赞！');
    }
    if(CURRENT_TIME-$likerow['addtime']<60){
        alert('操作过于频繁！');
    }
    M('album_likes')->deleteW('uid='.$_G['user']['id'].' and album_id='.$id);
    //减少点赞次数
    M('albums')->update($id , array('like_num'=>array('exp','like_num-1')) );

    alert('取消点赞成功！',true);
}else{
    $m_album = M('albums');
    $info = $m_album->load($id);

    if($info['uid']==$_G['user']['id']){
        alert('把点赞的机会留给别人吧！');
    }
    if(M('album_likes')->findRow('uid='.$_G['user']['id'].' and album_id='.$id)){
        alert('您已经点赞过了！');
    }

    if(M('album_likes')->insert(array('uid'=>$_G['user']['id'],'album_id'=>$id,'addtime'=>CURRENT_TIME))){
        //增加点赞次数
        $m_album->update($id , array('like_num'=>array('exp','like_num+1')) );
        
        alert('点赞成功！',true);
    }else{
        alert('点赞失败！');
    }
}