<?php
defined('IN_MWEB') or die('access denied');

require_once('_submenu.php');

class SmsIndex extends Adminbase{
    function indexAct(){
        $page = getGet('page',1);

        $m_sms = M('sms_logs');

        $where = '1';

        $totalCount = $m_sms->count($where);
        $pageurl = U('sms','index','page=%page%');

        $pager = new Pager($page,C('pageset.admin',15),$totalCount,$pageurl);
        $pager->config(C('adminpage'));
        $limit = $pager->getLimit();
        $this->view->assign('pagestr',$pager->html());

        $rows = $m_sms->findAll(array(
                    'where' => $where,
                    'order' => 'id desc',
                    'start' => $limit['start'],
                    'limit' => $limit['limit']
                ));

        $this->view->assign('rows',$rows);
        $this->view->display('index.php');
    }
}