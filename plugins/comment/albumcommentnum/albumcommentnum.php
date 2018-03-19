<?php
/*
Plugin Name: 相册更新评论数
Version: 1.0
Plugin URL:
Description: 更新相册及照片中的评论数。
Author: Meiu
Author URL: http://www.meiu.cn
*/
defined('IN_MWEB') or die('access denied');

addAction('comment_saved', 'update_album_comment');

function update_album_comment($data,$cid){
    if($data['mod'] == 'album'){
        M('albums')->update($data['rel_id'], array('comments_num'=>array('exp','comments_num+1')) );
    }elseif($data['mod'] == 'album_photo'){
       
        M('album_photos')->update($data['rel_id'], array('comments_num'=>array('exp','comments_num+1')) );
    }
}