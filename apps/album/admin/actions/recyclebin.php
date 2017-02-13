<?php
defined('IN_MWEB') or die('access denied');

require_once('_submenu.php');

class AlbumRecyclebin extends Adminbase{
    function indexAct(){
        $page = getGet('page',1);

        $search['type'] = trim(getRequest('type',1));

        
        $where = 'deleted=1';

        if($search['type']==2){
            $mod = M('album_photos');
            $totalCount = $photoNum = $mod->count($where);
            $albumNum = M('albums')->count($where);
        }else{
            $mod = M('albums');
            $totalCount = $albumNum = $mod->count($where);
            $photoNum = M('album_photos')->count($where);
        }
        
        
        $pageurl = U('album','recyclebin',array_merge($search,array('page'=>'%page%')));

        $pager = new Pager($page,C('pageset.admin',15),$totalCount,$pageurl);
        $pager->config(C('adminpage'));
        $limit = $pager->getLimit();
        $this->view->assign('pagestr',$pager->html());

        $rows = $mod->findAll(array(
            'where' => $where,
            'start' => $limit['start'],
            'limit' => $limit['limit'],
            'order' => 'id desc'
        ));
        //获取相册作者名字
        foreach($rows as $k=>$value){
            $user = M('users')->load($value['uid']);
            $rows[$k]['nickname'] = $user?$user['nickname']:'未知';
        }

        $this->view->assign('photoNum',$photoNum);
        $this->view->assign('albumNum',$albumNum);
        $this->view->assign('rows',$rows);
        $this->view->assign('search',$search);
        $this->view->display('recyclebin.php');
    }

    function restoreAct(){
        $id = intval(getGet('id'));
        $ids = getPost('ids');
        $type = getGet('type');
        if($type == 1){
            if($id){
                if(M('albums')->update($id,array('deleted'=>0))){
                    M('album_photos')->updateW('album_id='.$id,array('deleted'=>0));
                    alert('相册恢复成功！',true,'js_reload');
                }else{
                    alert('相册恢复失败！');
                }
            }elseif($ids){
                if(M('albums')->updateW('id in ('.implode(',', $ids).')',array('deleted'=>0))){
                    //同时将照片标示为删除状态，这种状态用2来表示
                    M('album_photos')->updateW('album_id in ('.implode(',', $ids).')',array('deleted'=>0));
                    alert('相册恢复成功！',true,'js_reload');
                }else{
                    alert('相册恢复失败！');
                }
            }
        }else{
            if($id){
                //取出图片
                $photoInfo = M('album_photos')->load($id);
                if(!$photoInfo){
                    alert('图片不存在！');
                }
                if(M('album_photos')->update($id,array('deleted'=>0))){
                    app('album')->updatePhotoNum($photoInfo['album_id']);
                    app('album')->checkCover($photoInfo['album_id']);

                    alert('恢复图片成功！',true,'js_reload');
                }else{
                    alert('恢复图片失败！');
                }
            }elseif($ids){
                $photos = M('album_photos')->findAll(array('where'=>'id in ('.implode(',', $ids).')','fields'=>'album_id'));
                //需要更新计数的相册id
                $album_ids = array_column($photos,'album_id');
                $album_ids = array_unique($album_ids);

                if(M('album_photos')->updateW('id in ('.implode(',', $ids).')',array('deleted'=>0))){
                    foreach($album_ids as $aid){
                        //重置图片数量
                        app('album')->updatePhotoNum($aid);
                        //重置封面
                        app('album')->checkCover($aid);
                    }

                    alert('恢复图片成功！',true,'js_reload');
                }else{
                    alert('恢复图片失败！');
                }
            }
        }
    }

    function delAct(){
        $id = intval(getGet('id'));
        $ids = getPost('ids');
        $type = intval(getGet('type'));
        if($type == 1){
            //删除相册及相关图片
            if($id){
                app('album')->delAlbum($id);
            }elseif(is_array($ids)){
                foreach($ids as $v){
                    app('album')->delAlbum($v);
                }
            }
            alert('彻底删除相册成功！',true,'js_reload');
        }else{
            //删除相册及相关图片
            if($id){
                app('album')->delPhoto($id);
            }elseif(is_array($ids)){
                foreach($ids as $v){
                    app('album')->delPhoto($v);
                }
            }
            alert('彻底删除图片成功！',true,'js_reload');
        }
    }

    function clearAct(){
        $albums = M('albums')->findAll('deleted=1');
        foreach($albums as $album){
            app('album')->delAlbum($album['id']);
        }
        $photos = M('album_photos')->findAll('deleted=1');
        foreach($photos as $photo){
            app('album')->delPhoto($photo['id']);
        }
        alert('清空回收站成功！',true,'js_reload');
    }
}