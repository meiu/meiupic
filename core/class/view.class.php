<?php
defined('IN_MWEB') || exit('Access denied!');

/**
 *  模板解析缓存
 */
class View{
    private $_config = array(
        'views_dir' => 'views'
    );
    private $_data = array();
    private $_decorate = false;
    private $_decorate_tpl = '';

    public function __construct($config = null){
        if(is_array($config)){
            $this->_config = array_merge($this->_config, $config);
        }
    }
    //修饰 display 页面修饰
    public function decorate($view_obj,$tpl=''){
        $this->_decorate = $view_obj;
        $this->_decorate_tpl = $tpl;
    }

    public function assign($key,$value=null){
        if(is_array($key) ){
            $this->_data = $key + $this->_data;
        }else{
            $this->_data[$key] = $value;
        }
    }

    public function display($tpl_file,$data=null){
        header("Content-Type:text/html;charset=utf-8");
        if($this->_decorate){
            $content = $this->fetch($tpl_file,$data);
            $this->assign('_bodycontent',$content);

            echo $this->_decorate->fetch($this->_decorate_tpl,$this->_data);
        }else{
            echo $this->fetch($tpl_file,$data);
        }
    }

    public function fetch($file,$data=null){
        global $_G;
        $ofile = $file;
        if(!ADMINPAGE && IS_MOBILE){
            $file = str_replace('.php', '_mobile.php', $file);
        }
        
        if($data){
            extract($data);
        }else{
            extract($this->_data);
        }
        $tpl_path = $this->_config['views_dir'].DS.$file;
        $file_exists  = file_exists($tpl_path);
        if(!$file_exists && strpos($file, '_mobile.php')){
            $tpl_path = $this->_config['views_dir'].DS.$ofile;
            if(!file_exists($tpl_path)){
                trace('模版"'.$file.'"不存在！','View','ERR');
            }
        }elseif(!$file_exists){
            trace('模版"'.$file.'"不存在！','View','ERR');
        }
        ob_start();
        include $tpl_path;
        $content = ob_get_clean();
        return $content;
    }
}
?>