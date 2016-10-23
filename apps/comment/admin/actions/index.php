<?php
defined('IN_MWEB') or die('access denied');

require_once('_submenu.php');

class CommentIndex extends Adminbase{
    function indexAct(){
        $page = getGet('page',1);
        $search['tab'] = getGet('tab');
        $search['keyword'] = getRequest('keyword');
        $search['mod'] = getRequest('mod');
        $search['rel_id'] = getRequest('rel_id');
        
        $m_comment = M('comments');

        $where = '1';
        if($search['tab'] && $search['tab'] == 1){
            $where .= ' and status=0';
        }elseif($search['tab'] && $search['tab'] == 2){
            $where .= ' and status=1';
        }elseif($search['tab'] && $search['tab'] == 3){
            $where .= ' and status=2';
        }
        if($search['keyword']){
            $where .= " and content like '%".$m_comment->escape($search['keyword'],false)."%'";
        }
        if($search['mod']){
            $where .= " and `mod`=".$m_comment->escape($search['mod']);
        }
        if($search['rel_id']){
            $where .= " and `rel_id`=".$search['rel_id'];
        }
        $par = $search;
        $par['page'] = '%page%';
        $totalCount = $m_comment->count($where);
        $pageurl = U('comment','index',$par);

        $pager = new Pager($page,C('pageset.admin',15),$totalCount,$pageurl);
        $pager->config(C('adminpage'));
        $limit = $pager->getLimit();
        $this->view->assign('pagestr',$pager->html());

        $rows = $m_comment->findAll(array(
            'where' => $where,
            'start' => $limit['start'],
            'limit' => $limit['limit'],
            'order' =>'id desc'
        ));

        $this->view->assign('search',$search);
        $this->view->assign('rows',$rows);
        $this->view->display('index.php');
    }

    function delAct(){
        $ids = getPost('ids');

        $m_comment = M('comments');
        $m_comment->deleteMany($ids);
        alert('删除成功！',true,'js_reload');
    }

    function auditAct(){
        $ids = getPost('ids');
        $status = getGet('status')==1?1:2;

        $data['status'] = $status;

        $m_comment = M('comments');
        $m_comment->updateW("id in (".implode(',', $ids).")",$data);
        alert('审核成功！',true,'js_reload');
    }
}