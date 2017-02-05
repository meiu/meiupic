<?php
defined('IN_MWEB') or die('access denied');

require_once('_submenu.php');

class AlbumSetting extends Adminbase{
    function indexAct(){
        $m_setting = M('settings');
        if(isPost()){
            $settings = getPost('setting');
            foreach ($settings as $key => $value) {
                $data = array(
                    'name' => $key,
                    'value' => trim($value),
                    'autoload' => 'yes'
                );
                $m_setting->insert($data,true);
            }
            //刷新缓存
            app('base')->refreshSettings();
            alert('修改设置成功！',true,U('album','setting'));
        }

        $settings_info = app('base')->getAllSettings();
        $this->view->assign('settings_info',$settings_info);
        $this->view->display('setting.php');
    }
}