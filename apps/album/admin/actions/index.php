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
        foreach($rows as &$value){
            $user = M('users')->load($value['uid']);
            $value['nickname'] = $user?$user['nickname']:'未知';
            $value['catename'] = isset($cateIndex[$value['cate_id']])?$cateIndex[$value['cate_id']]['name']:'未知分类';
            if($value['album_id']){
                $album = M('albums')->load($value['album_id']);
                $value['albumname'] = $album?$album['name']:'';
            }else{
                $value['albumname'] = '';
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

    function trashAct(){
        $id = intval(getGet('id'));
        $ids = getPost('ids');
        if($id){
            if(M('album_photos')->update($id,array('deleted'=>1))){
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


        $cates = app('album')->getCateList();
        $this->view->assign('cates',$cates);
        $this->view->decorate(null,'_mini.php');
        $this->view->display('photo_move.php');
    }
}