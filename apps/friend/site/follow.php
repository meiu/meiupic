<?php 
defined('IN_MWEB') or die('access denied');

checkLogin();

$id = intval(getGet('id'));

if(!$id){
    alert('缺少参数id！');
}

$act = getPost('a');
if($act == 'cancel'){
    $followrow = M('users_follow')->findRow('follow_uid='.$_G['user']['id'].' and uid='.$id);
    if(!$followrow){
        alert('还未关注过，无法取关注！');
    }
    if(time()-$followrow['follow_time']<60){
        alert('操作过于频繁！');
    }
    M('users_follow')->deleteW('follow_uid='.$_G['user']['id'].' and uid='.$id);
    //减少点赞次数
    M('users')->update($id , array('followers'=>array('exp','followers-1')) );
    M('users')->update($_G['user']['id'] , array('friends'=>array('exp','friends-1')) );
    alert('取消关注成功！',true);
}else{
    $m_user = M('users');
    $info = $m_user->load($id);

    if($info['id']==$_G['user']['id']){
        alert('你不能关注你自己！');
    }
    if(M('users_follow')->findRow('follow_uid='.$_G['user']['id'].' and uid='.$id)){
        alert('你已经关注过了！');
    }

    if(M('users_follow')->insert(array('follow_uid'=>$_G['user']['id'],'uid'=>$id,'follow_time'=>CURRENT_TIME))){
        //增加点赞次数
        $m_user->update($id , array('followers'=>array('exp','followers+1')) );
        $m_user->update($_G['user']['id'] , array('friends'=>array('exp','friends+1')) );
        
        alert('关注成功！',true);
    }else{
        alert('关注失败！');
    }
}