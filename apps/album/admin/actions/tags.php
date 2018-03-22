<?php
defined('IN_MWEB') or die('access denied');

require_once('_submenu.php');

class AlbumTags extends Adminbase{
    function indexAct(){
        $page = getGet('page',1);

        $m_tag = M('album_tags');

        $totalCount = $m_tag->count();
        $pageurl = U('album','tags','page=%page%');

        $pager = new Pager($page,C('pageset.admin',15),$totalCount,$pageurl);
        $pager->config(C('adminpage'));
        $limit = $pager->getLimit();

        $rows = $m_tag->findAll(array(
            'order' => 'id desc',
            'start' => $limit['start'],
            'limit' => $limit['limit']
        ));
        $this->view->assign('rows',$rows);

        $this->view->assign('pagestr',$pager->html());
        $this->view->display('tags.php');
    }

    function gettagsAct(){
        $m_tag = M('album_tags');
        $term = trim(getGet('term'));

        $all = $m_tag->findAll(array(
            'fields' => 'name',
            'where' => "name like '".$m_tag->escape($term,false)."%'",
            'order' => 'album_num desc,photo_num desc',
            'limit' => 10
        ));

        echo json_encode(array_column($all,'name'));
        exit;
    }

    function editAct(){
        $m_tag = M('album_tags');
        $id = intval(getGet('id'));
        if(isPost()){
            $data['name'] = trim(getPost('name'));
            $data['image'] = trim(getPost('image'));
            $data['description'] = trim(getPost('description'));
            $data['recommended'] = intval(getPost('recommended'));
            $data['sort'] = intval(getPost('sort'));

            if($m_tag->update($id,$data)){
                alert('保存成功！',true,U('album','tags'));
            }else{
                alert('保存失败！');
            }
        }
        $item = $m_tag->load($id);
        
        if(!$item){
            alert('您要编辑的标签不存在！');
        }

        $this->view->assign('item',$item);
        $this->view->display('tags_edit.php');
    }

    function addAct(){
        $m_tag = M('album_tags');
        if(isPost()){
            $data['name'] = trim(getPost('name'));
            $data['image'] = trim(getPost('image'));
            $data['description'] = trim(getPost('description'));
            $data['recommended'] = intval(getPost('recommended'));
            $data['sort'] = intval(getPost('sort'));

            if(empty($data['name'])){
                alert('请输入标签名！');
            }

            if($m_tag->insert($data)){
                alert('保存成功！',true,U('album','tags'));
            }else{
                alert('保存失败！');
            }
        }
        $item = $m_tag->loadDefault();

        $this->view->assign('item',$item);
        $this->view->display('tags_edit.php');
    }
}