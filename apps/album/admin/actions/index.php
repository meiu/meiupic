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
            $where .= ' and id ='.intval($search['uid']);
        }
        if( $search['cate_id'] ){
            $catIds = app('album')->catIds(intval($search['cate_id']));
            $where .= ' and cate_id in ('.implode(',', $catIds).')';
        }
        if( $search['aid'] ){
            $where .= ' and album_id='.intval($search['aid']);
        }
        $totalCount = $m_photo->count($where);
        $pageurl = U('album','photo',array_merge($search,array('page'=>'%page%')));

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
        }

        $this->view->assign('rows',$rows);
        $this->view->assign('search',$search);

        $this->view->assign('cates',app('album')->getCateList(0));
        $this->view->display('photo.php');
    }

    function editAct(){
        $id = intval(getGet('id'));
        $m_album = M('albums');

        if(isPost()){
            $data['name'] = safestr(trim(getPost('name')));
            $data['desc'] = trim(getPost('desc'));
            $data['cate_id'] = intval(getPost('cate_id'));
            $data['up_time'] = time();
            $data['priv_type'] = getPost('priv_type');
            $data['priv_pass'] = getPost('priv_pass');
            $data['enable_comment'] = intval(getPost('enable_comment'));

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

            if($m_album->update($id,$data)){
                alert('修改相册成功！',true,'js_reload');
            }else{
                alert('修改相册失败！');
            }
        }

        $info = $m_album->load($id);
        $this->view->assign('info',$info);
        $cates = app('album')->getCateList();
        $this->view->decorate(null,'_mini.php');
        $this->view->assign('cates',$cates);
        $this->view->display('album_edit.php');
    }

    function addAct(){
        global $_G;

        $m_photos = M('photos');
        if(isPost()){
            $name = safestr(trim(getPost('name')));
            $data['uid'] = $_G['user']['id'];
            $data['description'] = trim(getPost('description'));
            $data['cate_id'] = intval(getPost('cate_id'));
            $data['create_time'] = time();
            $data['priv_type'] = intval(getPost('priv_type'));

            /*if($m_album->insert($data)){
                alert('添加相册成功！',true,'js_reload');
            }else{
                alert('添加相册失败！');
            }*/
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
            if(M('albums')->update($id,array('deleted'=>1))){
                alert('移动相册到回收站成功！',true,'js_reload');
            }else{
                alert('移动相册到回收站失败！');
            }
        }elseif($ids){
            if(M('albums')->updateW('id in ('.implode(',', $ids).')',array('deleted'=>1))){
                alert('移动相册到回收站成功！',true,'js_reload');
            }else{
                alert('移动相册到回收站失败！');
            }
        }
    }
}