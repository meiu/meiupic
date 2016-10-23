<?php
defined('IN_MWEB') || exit('Access denied!');

class Adminbase {
    protected $view;
    protected $cache;
    protected $_G;

    function __construct(){
        global $_G;

        $this->_G =& $_G;
        $this->view =& $_G['runtime']['view'];
        $this->cache =& $_G['runtime']['cache'];

        if(isset($this->_submenu)){
            $this->view->assign('submenu',$this->_submenu);
        }
    }

    function __call($name, $arg){
        trace('404 Page Not Found!','ROUTE','ERR');
    }
}