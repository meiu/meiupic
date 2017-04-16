<?php
defined('IN_MWEB') or die('access denied');

require_once('_submenu.php');

class AlbumIndex extends Adminbase{
    function indexAct(){
        $page = getGet('page',1);

        $search['name'] = trim(getRequest('name'));
        $search['uid']  = getRequest('uid');
        $search['cate_id']  = getRequest('cate_id');
        $search['aid']  = getRequest('aid');
        $search['notinablum'] = getRequest('notinablum');

        if(isPost()){
            redirect(U('album','index',$search));
            return false;
        }
        
        $m_photo = M('album_photos');

        $where = 'deleted=0';
        if( $search['name'] ){
            if( preg_match('/^\*\d+$/', $search['name'])){
                $where .= ' and id ='.intval(trim($search['name'],'*'));
            }else{
                $keyword = $search['name'];
                $where .= " and name like '%".$m_photo->escape($keyword,false)."%'";
            }
        }
        if( $search['uid'] ){
            $where .= ' and uid ='.intval($search['uid']);
        }
        if( $search['cate_id'] ){
            $catIds = app('album')->catIds(intval($search['cate_id']));
            $where .= ' and cate_id in ('.implode(',', $catIds).')';
        }
        if( $search['aid'] ){
            $where .= ' and album_id='.intval($search['aid']);
            $albumInfo = M('albums')->load(intval($search['aid']));
            $this->view->assign('albumInfo',$albumInfo);
        }elseif($search['notinablum']){
            $where .= ' and album_id=0';
        }
        $totalCount = $m_photo->count($where);
        $pageurl = U('album','index',array_merge($search,array('page'=>'%page%')));

        $pager = new Pager($page,C('pageset.admin',15),$totalCount,$pageurl);
        $pager->config(C('adminpage'));
        $limit = $pager->getLimit();
        $this->view->assign('pagestr',$pager->html());

        $rows = $m_photo->findAll(array(
            'where' => $where,
            'start' => $limit['start'],
            'limit' => $limit['limit'],
            'order' => 'id desc'
        ));

        $cateIndex = app('album')->getCateIndex();
        //获取相册作者名字
        foreach($rows as $k=>$value){
            $user = M('users')->load($value['uid']);
            $rows[$k]['nickname'] = $user?$user['nickname']:'未知';
            $rows[$k]['catename'] = isset($cateIndex[$value['cate_id']])?$cateIndex[$value['cate_id']]['name']:'未知分类';
            if($value['album_id']){
                $album = M('albums')->load($value['album_id']);
                $rows[$k]['albumname'] = $album?$album['name']:'';
            }else{
                $rows[$k]['albumname'] = '';
            }
        }

        $this->view->assign('rows',$rows);
        $this->view->assign('search',$search);

        $this->view->assign('cates',app('album')->getCateList(0));
        $this->view->display('photo.php');
    }

    function editAct(){
        $id = intval(getGet('id'));
        $m_photo = M('album_photos');

        if(isPost()){
            $data['name'] = safestr(trim(getPost('name')));
            $data['tags'] = trim(getPost('tags'));
            $data['description'] = trim(getPost('description'));
            $data['cate_id'] = intval(getPost('cate_id'));
            $data['priv_type'] = intval(getPost('priv_type'));

            if(!$data['name']){
                alert('图片名不能为空！');
            }

            if($m_photo->update($id,$data)){
                $info = $m_photo->load($id);
                
                app('album')->updateTags('photo',$id,$data['tags'],$info['path'],false);
                alert('修改图片成功！',true,'js_reload');
            }else{
                alert('修改图片失败！');
            }
        }

        $info = $m_photo->load($id);
        $this->view->assign('info',$info);
        $cates = app('album')->getCateList();
        $this->view->decorate(null,'_mini.php');
        $this->view->assign('cates',$cates);
        $this->view->display('photo_edit.php');
    }

    function trashAct(){
        $id = intval(getGet('id'));
        $ids = getPost('ids');
        if($id){
            //取出图片
            $photoInfo = M('album_photos')->load($id);
            if(!$photoInfo){
                alert('图片不存在！');
            }
            if(M('album_photos')->update($id,array('deleted'=>1))){
                app('album')->updatePhotoNum($photoInfo['album_id']);
                app('album')->checkCover($photoInfo['album_id']);

                alert('移动图片到回收站成功！',true,'js_reload');
            }else{
                alert('移动图片到回收站失败！');
            }
        }elseif($ids){
            $photos = M('album_photos')->findAll(array('where'=>'id in ('.implode(',', $ids).')','fields'=>'album_id'));
            //需要更新计数的相册id
            $album_ids = array_column($photos,'album_id');
            $album_ids = array_unique($album_ids);

            if(M('album_photos')->updateW('id in ('.implode(',', $ids).')',array('deleted'=>1))){
                foreach($album_ids as $aid){
                    //重置图片数量
                    app('album')->updatePhotoNum($aid);
                    //重置封面
                    app('album')->checkCover($aid);
                }

                alert('移动图片到回收站成功！',true,'js_reload');
            }else{
                alert('移动图片到回收站失败！');
            }
        }
    }

    function setcoverAct(){
        $id =  intval(getGet('id'));
        //取出图片
        $photoInfo = M('album_photos')->load($id);
        if(!$photoInfo){
            alert('图片不存在！');
        }
        if(!$photoInfo['album_id']){
            alert('图片还不属于任何相册！');
        }
        app('album')->updateCover($photoInfo['album_id'],$photoInfo['id']);
        alert('设置成功！',true,'js_reload');
    }

    function moveAct(){
        $page = getGet('page',1);

        $search['a'] = trim(getRequest('a'));
        $search['ids'] = trim(getRequest('ids'));
        $search['name'] = trim(getRequest('name'));
        $search['winid'] = trim(getRequest('winid'));
        $search['cate_id']  = getRequest('cate_id');

        $m_album = M('albums');
        $where = 'deleted=0';
        if( $search['name'] ){
            if( is_numeric($search['name']) ){
                $where .= ' and id ='.intval($search['name']);
            }else{
                $keyword = trim($search['name'],'*');
                $where .= " and name like '%".$m_album->escape($keyword,false)."%'";
            }
        }
        if( $search['cate_id'] ){
            $catIds = app('album')->catIds(intval($search['cate_id']));
            $where .= ' and cate_id in ('.implode(',', $catIds).')';
        }
        $totalCount = $m_album->count($where);
        $pageurl = U('album','index',array_merge($search,array('page'=>'%page%')));
       
        $pager = new Pager($page,C('pageset.admin',15),$totalCount,$pageurl);
        $pager->config(C('adminpage'));
        $limit = $pager->getLimit();
        $this->view->assign('pagestr',$pager->html());

        $rows = $m_album->findAll(array(
            'where' => $where,
            'start' => $limit['start'],
            'limit' => $limit['limit'],
            'order' => 'id desc'
        ));

        $cates = app('album')->getCateList();
        $this->view->assign('cates',$cates);
        $this->view->assign('rows',$rows);
        $this->view->assign('search',$search);
        $this->view->decorate(null,'_mini.php');
        $this->view->display('photo_move.php');
    }

    function domoveAct(){
        $ids = trim(getRequest('ids'));
        $album_id = intval(getRequest('album_id'));
        if(!$album_id){
            alert('请选择相册！');
        }
        $albumInfo = M('albums')->load($album_id);
        if(!$albumInfo){
            alert('相册不存在！');
        }
        if($albumInfo['deleted']!=0){
            alert('相册在回收站中，无法操作！');
        }
        //同时修改图片分类
        $updata = array('album_id'=>$album_id,'cate_id'=>$albumInfo['cate_id']);
        if($albumInfo['priv_type']==1){//如果相册本身是私有的，那么拉进来的图片也置为私有
            $updata['priv_type'] = 1;
        }

        $photos = M('album_photos')->findAll(array('where'=>'id in ('.$ids.') and deleted=0','fields'=>'album_id'));
        //需要更新计数的相册id
        $album_ids = array_column($photos,'album_id');
        $album_ids[] = $album_id;
        $album_ids = array_unique($album_ids);

        if( M('album_photos')->updateW('id in ('.$ids.')',$updata) ){
            
            foreach($album_ids as $aid){
                //重置图片数量
                app('album')->updatePhotoNum($aid);
                //重置封面
                if($aid != $album_id){
                    app('album')->updateCover($aid);
                }elseif(!$albumInfo['cover_id']){
                    app('album')->updateCover($aid);
                }
            }

            alert('移动图片成功！',true,'js_reload');
        }else{
            alert('移动失败！');
        }
    }
}