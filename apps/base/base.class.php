<?php
defined('IN_MWEB') or die('access denied');

Class BaseClass{
    //template文件列表（分组）
    public function getTplFiles($theme){
        $theme_setting = app('base')->getThemeSetting($theme);
        $content_dir = $theme.'/content';
        $explans = isset($theme_setting['file_explan'][$content_dir])?$theme_setting['file_explan'][$content_dir]:array();

        $template_rootdir = ROOT_DIR.'templates'.DS.$theme;

        $filelist = dirlist('content',$template_rootdir);

        $data = array(
            'list' => array(),
            'page' => array(),
            'index'=> array(),
            'detail'=> array(),
            'edit' => array()
        );
        foreach ($filelist as $key => $value) {
            if($value['filename'] == '..'){
                continue;
            }
            if(preg_match('/(list|page|index|detail|edit)\.php$/', $value['filename'],$matches)){
                $type = $matches[1];
            }else{
                $type = 'other';
            }
            $data[$type][] = array('filename'=>$value['filename'],'text'=> (isset($explans[$value['filename']])?$explans[$value['filename']]:'').$value['filename']);
        }
        return $data;
    }
    //获取详情页自定义模版
    public function getCustomTpls($theme){
        $template_rootdir = ROOT_DIR.'templates'.DS.$theme;
        $filelist = dirlist('content/custom',$template_rootdir);

        $files = array();
        foreach ($filelist as $key => $value) {
            if($value['filename'] == '..'){
                continue;
            }
            $files[] = $value['filename'];
        }
        return $files;
    }

    //重新刷新标签
    public function refreshLabel($name){
        $cache = Cache::instance();
        $cache->remove('label_'.$name);
    }
    
    //重新刷新设置
    public function refreshSettings(){
        $cache = Cache::instance();
        $cache->remove('global_settings');
    }
    //重新生成路由缓存
    public function refreshRoute(){
        $m_route = M('routes');
        $rules = $m_route->findAll(array('order'=>'sort asc'));
        $data = array();

        $actions = array();

        foreach ($rules as $key => $value) {
            parse_str($value['params'],$rowarr);
            $actions[$rowarr['app'].'.'.$rowarr['m']] = 1;

            $need_replace = array();
            $rule = str_replace('/', '\/',$value['route']);

            if(preg_match_all('/\{(:?[a-z0-9\_\-]+)\}/i', $value['route'],$matches)){
                $need_replace = $matches[1];
                foreach($matches[1] as $v){
                    if($v[0] == ':'){
                        $v = ltrim($v,':');
                        $rowarr[$v] = ':@';
                    }else{
                        $rowarr[$v] = '@@';
                    }

                    $rule = str_replace('{:'.$v.'}', '([^\/]+)?',$rule);
                    $rule = str_replace('{'.$v.'}', '([^\/]+)',$rule);
                }
            }
            $regex = '/^'.$rule.'$/i';

            $data[$value['route']] = array(
                'params' => $rowarr,
                'needreplace' => $need_replace,
                'regex' => $regex
            );
        }

        $route_setting_file = DATA_PATH.'routes.php';

        $route_setting = array(
            'mods' => array_keys($actions),
            'data'    => $data
        );
        $content = '<?php return '.var_export($route_setting,true).';';
        return file_put_contents($route_setting_file, $content);
    }

    //获取模版配置
    public function getThemeSetting($theme){
        $conf_file = ROOT_DIR.'templates'.DS.$theme.DS.'config.php';
        return include($conf_file);
    }
    //写入模版配置
    public function setThemeSetting($theme,$conf){
        $conf_file = ROOT_DIR.'templates'.DS.$theme.DS.'config.php';

        $content = '<?php return '.var_export($conf,true).';';
        return file_put_contents($conf_file, $content);
    }

    //获取某项配置
    public function getSetting($name,$array=false){
        global $_G;
        if(isset($_G['settings'][$name])){//先看看自动加载的设置是否存在
            if($array){
                $value = sc_decode($_G['settings'][$name]);
            }else{
                $value = $_G['settings'][$name];
            }
            return $value;
        }

        $cache = Cache::instance();
        $data = $cache->get('o_settings_'.$name);
        if($data !== false){
            return $data;
        }else{
            $m_setting = M('settings');
            $settings = $m_setting->load($name,'*','name');
            if($array){
                if($settings){
                    $value = sc_decode($settings['value']);
                }else{
                    $value = array();
                }
            }else{
                if($settings){
                    $value = $settings['value'];
                }else{
                    $value = '';
                }
            }
            $cache->set('o_settings_'.$name,$value,array('life_time'=>86400));
            return $value;
        }
    }
    //设置某项
    public function setSetting($name,$value,$autoload='yes'){
        $m_setting = M('settings');

        $insert_data['name'] = $name;
        $insert_data['value'] = is_array($value)?sc_encode($value):$value;
        $insert_data['autoload'] = ($autoload=='yes'?'yes':'no');
        
        $ret = $m_setting->insert($insert_data,true);
        if($ret){
            $cache = Cache::instance();
            $cache->remove('o_settings_'.$name);
            $cache->remove('global_settings');
            return true;
        }else{
            return false;
        }
    }

    //获取全部设置，只会获取自动加载的部分
    public function getAllSettings(){
        $cache = Cache::instance();
        $data = $cache->get('global_settings');
        if($data === false){
            $m_setting = M('settings');
            $data = $m_setting->select(array('where'=>"autoload='yes'",'fields'=> 'name,value'))->getAssoc();
            $cache->set('global_settings',$data,array('life_time'=>86400));
        }
        return $data;
    }
    
    //获取全局标签
    public function getLabel($name){
        $cache = Cache::instance();
        $label = $cache->get('label_'.$name);
        if($label===false){
            $m_label = M('labels');
            $label = $m_label->findRow("name=".$m_label->escape($name));
            $cache->set('label_'.$name,$label);
        }
        if(!$label){
            return '';
        }
        return $label['data'];
    }

    public function sendMail($email,$subject,$content){
        $setting = app('base')->getSetting('mail_setting',true);
        if($setting['protocol'] == 'none'){
            return false;
        }
        $init = array(
            'protocol' => $setting['protocol'],
            'smtp_host' => $setting['smtp_host'],
            'smtp_user' => $setting['smtp_username'],
            'smtp_pass' => $setting['smtp_password'],
            'smtp_port' => $setting['smtp_port'],
            'smtp_crypto' => $setting['smtp_crypto']
        );
        $email_obj = new email($init);
        $email_obj->set_mailtype('html');

        $email_obj->from($setting['send_email'], $setting['send_name']);
        $email_obj->to($email);
        $email_obj->subject($subject);
        $email_obj->message($content);

        if($email_obj->send()){
            return true;
        }else{
            return false;
        }
    }

    public function parseMail($tpl,$data){
        $tpl_path = CORE_PATH.'mail_tpls'.DS.$tpl;
        $tpl_content = file_get_contents($tpl_path);

        $title = '';
        //取出title
        if(preg_match('/<title>(.+?)<\/title>/i', $tpl_content,$matches)){
            $title = $matches[1];
        }
        //开始替换
        $title = str_replace(array('#昵称#','#手机号#','#用户名#','#验证码#','#链接1#','#链接2#'), array(@$data['nickname'],@$data['mobile'],@$data['username'],@$data['code'],@$data['link1'],@$data['link2']), $title);
        $content = str_replace(array('#昵称#','#手机号#','#用户名#','#验证码#','#链接1#','#链接2#'), array(@$data['nickname'],@$data['mobile'],@$data['username'],@$data['code'],@$data['link1'],@$data['link2']), $tpl_content);

        return array('title'=>$title,'content'=>$content);
    }
}