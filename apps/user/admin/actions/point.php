<?php 
defined('IN_MWEB') or die('access denied');

require_once('_submenu.php');

class UserPoint extends Adminbase{
    function indexAct(){
        $page = getGet('page',1);

        $m_point = M('users_point');

        $totalCount = $m_point->count();
        $pageurl = U('user','point','page=%page%');

        $pager = new Pager($page,C('pageset.admin',15),$totalCount,$pageurl);
        $pager->config(C('adminpage'));
        $limit = $pager->getLimit();
        $this->view->assign('pagestr',$pager->html());

        $rows = $m_point->findAll(array(
            'start' => $limit['start'],
            'limit' => $limit['limit']
        ));

        $this->view->assign('rows',$rows);

        $this->view->display('point_index.php');
    }

    function delAct(){
        $id = intval(getGet('id'));

        $m_point = M('users_point');
        
        if($m_point->delete($id)){
            alert('删除成功！',true,U('user','point'));
        }else{
            alert('删除失败！');
        }
    }

    function addAct(){
        $m_point = M('users_point');
        if(isPost()){
            $data['pointkey'] = trim(getPost('pointkey'));
            $data['name'] = trim(getPost('name'));
            $data['points'] = intval(getPost('points'));
            $data['ac'] = intval(getPost('ac'));

            if(empty($data['name'])){
                alert('请输入积分说明！');
            }
            if(empty($data['pointkey'])){
                alert('请输入积分Key！');
            }
            
            if($m_point->insert($data)){
                alert('添加成功！',true,U('user','point'));
            }else{
                alert('添加失败！');
            }
        }
        $info = $m_point->loadDefault();
        $this->view->assign('info',$info);
        $this->view->display('point_edit.php');
    }

    function editAct(){
        $id = intval(getGet('id'));
        $m_point = M('users_point');
        if(isPost()){
            $data['pointkey'] = trim(getPost('pointkey'));
            $data['name'] = trim(getPost('name'));
            $data['points'] = intval(getPost('points'));
            $data['ac'] = intval(getPost('ac'));

            if(empty($data['name'])){
                alert('请输入积分说明！');
            }
            if(empty($data['pointkey'])){
                alert('请输入积分Key！');
            }
            
            if($m_point->update($id,$data)){
                alert('编辑成功！',true,U('user','point'));
            }else{
                alert('编辑失败！');
            }
        }

        $info = $m_point->load($id);

        if(!$info){
            alert('您要编辑的项不存在！');
        }

        $this->view->assign('info',$info);
        $this->view->display('point_edit.php');
    }
}
