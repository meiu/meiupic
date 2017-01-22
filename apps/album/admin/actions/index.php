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
            if( is_numeric($search['name']) ){
                $where .= ' and id ='.intval($search['name']);
            }else{
                $keyword = trim($search['name'],'*');
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
            $data['description'] = trim(getPost('description'));
            $data['cate_id'] = intval(getPost('cate_id'));
            $data['priv_type'] = intval(getPost('priv_type'));

            if(!$data['name']){
                alert('图片名不能为空！');
            }
            if(!$data['cate_id']){
                alert('请选择分类！');
            }

            if($m_photo->update($id,$data)){
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

    function addAct(){
        global $_G;

        $m_photos = M('album_photos');
        if(isPost()){
            $name = safestr(trim(getPost('name')));
            $tags = trim(getPost('tags'));
            $data['uid'] = $_G['user']['id'];
            $data['description'] = trim(getPost('description'));
            $data['cate_id'] = intval(getPost('cate_id'));
            $data['album_id'] = 0;
            $data['create_time'] = time();
            $data['priv_type'] = intval(getPost('priv_type'));

            $upload_setting = C('upload');
            $dirpath = $upload_setting['dirpath'];

            $photopaths = getPost('photopaths');
            $photonames = getPost('photonames');
            $exiflib = new exif;
            foreach($photopaths as $k=>$path){
                $data['path'] = $path;
                $data['name'] = $name?$name.'('.($k+1).')':$photonames[$k];
                $data['tags'] = $tags;
                $data['path'] = $path;
                $data['taken_time'] = 0;
                $data['exif'] = '';

                $imginfo = @getimagesize($dirpath.$path);
                $data['width'] = @$imginfo[0];
                $data['height'] = @$imginfo[1];
                if( @$imginfo[2] == 2){//如果图片是jpg
                    $exif = $exiflib->get_exif($dirpath.$path);
                    if($exif){
                        $data['exif'] = serialize($exif);
                        $taken_time = strtotime($exif['DateTimeOriginal']);
                        $data['taken_time'] = $taken_time;
                    }
                }
                $m_photos->insert($data);
            }
            alert('添加照片成功！',true,'js_reload');
        }

        $ids = trim(getGet('ids'),',');
        //获取待上传的列表
        if($ids){
            $id_arr = explode(',', $ids);

            //取出图片
            $photos = M('upfiles')->findAll(array(
                'where'=>'id in ('.implode(',', $id_arr).')',
                'order' => 'SUBSTRING_INDEX(\''.implode(',', $id_arr).'\',id,1)'
            ));
        }else{
            $photos = array();
        }

        $cates = app('album')->getCateList();
        $this->view->decorate(null,'_mini.php');
        $this->view->assign('photos',$photos);
        $this->view->assign('cates',$cates);
        $this->view->display('photo_add.php');
    }

    function addtoalbumAct(){
        global $_G;

        $m_photos = M('album_photos');
        if(isPost()){
            $album_id = intval(getGet('aid'));
            $albumInfo = M('albums')->load($album_id);
            if(!$albumInfo){
                alert('相册不存在！');
            }

            $name = safestr(trim(getPost('name')));
            $tags = trim(getPost('tags'));
            $data['uid'] = $_G['user']['id'];
            $data['description'] = trim(getPost('description'));
            $data['cate_id'] = $albumInfo['cate_id'];
            $data['album_id'] = $album_id;
            $data['create_time'] = time();
            $data['priv_type'] = intval(getPost('priv_type'));

            $upload_setting = C('upload');
            $dirpath = $upload_setting['dirpath'];

            $photopaths = getPost('photopaths');
            $photonames = getPost('photonames');
            $exiflib = new exif;
            foreach($photopaths as $k=>$path){
                $data['path'] = $path;
                $data['name'] = $name?$name.'('.($k+1).')':$photonames[$k];
                $data['tags'] = $tags;
                $data['path'] = $path;
                $data['taken_time'] = 0;
                $data['exif'] = '';

                $imginfo = @getimagesize($dirpath.$path);
                $data['width'] = @$imginfo[0];
                $data['height'] = @$imginfo[1];
                if( @$imginfo[2] == 2){//如果图片是jpg
                    $exif = $exiflib->get_exif($dirpath.$path);
                    if($exif){
                        $data['exif'] = serialize($exif);
                        $taken_time = strtotime($exif['DateTimeOriginal']);
                        $data['taken_time'] = $taken_time;
                    }
                }
                $m_photos->insert($data);
            }
            //重置图片数量
            app('album')->updatePhotoNum($album_id);
            if(!$albumInfo['cover_id']){
                app('album')->updateCover($album_id,0);
            }
            alert('添加照片成功！',true,'js_reload');
        }

        $ids = trim(getGet('ids'),',');
        //获取待上传的列表
        if($ids){
            $id_arr = explode(',', $ids);

            //取出图片
            $photos = M('upfiles')->findAll(array(
                'where'=>'id in ('.implode(',', $id_arr).')',
                'order' => 'SUBSTRING_INDEX(\''.implode(',', $id_arr).'\',id,1)'
            ));
        }else{
            $photos = array();
        }
        $this->view->decorate(null,'_mini.php');
        $this->view->assign('photos',$photos);
        $this->view->display('photo_addtoalbum.php');
    }


    function trashAct(){
        $id = intval(getGet('id'));
        $ids = getPost('ids');
        if($id){
            if(M('album_photos')->update($id,array('deleted'=>1))){
                //取出图片


                alert('移动图片到回收站成功！',true,'js_reload');
            }else{
                alert('移动图片到回收站失败！');
            }
        }elseif($ids){
            if(M('album_photos')->updateW('id in ('.implode(',', $ids).')',array('deleted'=>1))){
                alert('移动图片到回收站成功！',true,'js_reload');
            }else{
                alert('移动图片到回收站失败！');
            }
        }
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
        //同时修改图片分类
        $updata = array('album_id'=>$album_id,'cate_id'=>$albumInfo['cate_id']);
        if($albumInfo['priv_type']==1){//如果相册本身是私有的，那么拉进来的图片也置为私有
            $updata['priv_type'] == 1;
        }
        if( M('album_photos')->updateW('id in ('.$ids.')',$updata) ){
            //重置图片数量
            app('album')->updatePhotoNum($album_id);
            if(!$albumInfo['cover_id']){
                app('album')->updateCover($album_id,0);
            }
            alert('移动图片成功！',true,'js_reload');
        }else{
            alert('移动失败！');
        }
    }
}