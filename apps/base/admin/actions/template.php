<?php 

defined('IN_MWEB') or die('access denied');


class BaseTemplate extends Adminbase{
    protected $_submenu = array(
        '风格' => array('index','filelist','fileedit'),
        '全局标签' => array('label','labeledit','labeladd')
    );

    function indexAct(){
        $template_rootdir = ROOT_DIR.'templates';
        $filelist = dirlist('',$template_rootdir);

        $rows = array();
        foreach ($filelist as $key => $value) {
            if($value['filename'] == '..'){
                continue;
            }
            $configfile = $template_rootdir.DS.$value['filename'].DS.'config.php';
            if(!file_exists($configfile)){
                continue;
            }
            $themeconfig = include($configfile);
            if($themeconfig['name']){
                $rows[] = array(
                    'id' => $themeconfig['dirname'],
                    'name' => $themeconfig['name'],
                    'author' => $themeconfig['author'],
                    'homepage'=>  $themeconfig['homepage']
                );
            }
        }
        $this->view->assign('rows',$rows);
        $this->view->display('template.php');
    }

    function previewAct(){
        $id = safestr(getGet('id'));
        $tplpath = ROOT_DIR.'templates'.DS.$id.DS.'preview.jpg';

        header('Content-type: image/jpeg');
        echo readfile($tplpath);
        exit;
    }

    function filelistAct(){
        $theme = getGet('theme');
        $dir   = getGet('dir'); //目录
        if(preg_match('/^\.\.?\/?/', $dir)){
            $dir = '';
        }
        $current_dir = str_replace('\\','/',trim($theme.'/'.$dir,'/\\'));

        $theme_setting = app('base')->getThemeSetting($theme);
        $files_info = isset($theme_setting['file_explan'][$current_dir])?$theme_setting['file_explan'][$current_dir]:array();

        $template_rootdir = ROOT_DIR.'templates'.DS.$theme;
        
        $filelist = dirlist($dir,$template_rootdir);
        
        if($filelist){
            foreach ($filelist as $key => $value) {
                if(isset($files_info[$value['filename']])){
                    $filelist[$key]['file_explan'] = $files_info[$value['filename']];
                }
                $filelist[$key]['tpl_type'] = '';
                if($dir == 'content'){
                    if(preg_match('/(list|page|index|detail|edit)\.php$/', $value['filename'],$matches)){
                        $filelist[$key]['tpl_type'] = $matches[1];
                    }
                }
            }
        }

        $this->view->assign('theme',$theme);
        $this->view->assign('filelist',$filelist);
        $this->view->assign('dir',$dir);
        $this->view->assign('current_dir',$current_dir);
        $this->view->display('template_files.php');
    }

    function renameAct(){
        $theme = getGet('theme');
        $dir   = getGet('dir'); //目录
        if(preg_match('/^\.\.?\/?/', $dir)){
            $dir = '';
        }
        $current_dir = str_replace('\\','/',trim($theme.'/'.$dir,'/\\'));
        $explans = getPost('file_explan');

        $theme_setting = app('base')->getThemeSetting($theme);
        $theme_setting['file_explan'][$current_dir] = $explans;
        app('base')->setThemeSetting($theme,$theme_setting);

        alert('保存成功！',true);
    }

    function newfileAct(){
        $theme = getGet('theme');
        $dir   = getGet('dir');
        $file  = getPost('file');

        if(!$theme){
            alert('丢失参数theme');
        }
        if(preg_match('/^\.\.?\/?/', $dir)){
            $dir = '';
        }
        if(empty($file)){
            alert('文件名不能为空！');
        }
        if(!preg_match('/[a-z0-9\-_]+/i', $file)){
            alert('文件名只能是数字、字母以及下划线！');
        }
        $fileext = fileext($file);
        if(!in_array($fileext,array('js','css','html','htm','php'))){
            alert('文件格式不正确！');
        }
        $filename = $file;

        $filepath = ROOT_DIR.'templates'.DS.$theme.DS.$dir.DS.$filename;
        if(file_exists($filepath)){
            alert('文件已存在！');
        }
        if(@touch($filepath)){
            alert('创建成功！',true,'js_reload');
        }else{
            alert('创建失败！');
        }
    }

    function fileeditAct(){
        $theme = getGet('theme');
        $file = getGet('file');
        $dir = getGet('dir');
        
        if(isPost()){
            $data['theme'] = $theme;
            $data['file'] = trim(getPost('file'));
            $code = stripslashes(getPost('code'));

            if(!$data['theme']){
                alert('丢失参数theme！');
            }
            if(!$data['file']){
                alert('缺少文件名！');
            }
            $fileext = fileext($data['file']);
            $path = ROOT_DIR.'templates'.DS.$data['theme'].DS.$data['file'];
            
            if(@file_put_contents($path,$code)){
                alert('保存成功！',true);//,U('base','template','a=filelist&theme='.$theme.'&dir='.dirname($data['file'])));
            }else{
                alert('写入文件失败！');
            }
        }

        
        $file_info['theme'] = $theme;
        $file_info['file'] = $file;

        $fileext = fileext($file);

        if($file && !in_array($fileext,array('js','css','html','htm','php'))){
            alert('该文件格式不能编辑！');
        }

        $path = ROOT_DIR.'templates'.DS.$theme.DS.$file;
        if($file && file_exists($path)){
            $code = file_get_contents($path);
        }else{
            $code = '';
        }

        $this->view->assign('fileext',$fileext);
        $this->view->assign('file_info',$file_info);
        $this->view->assign('code',$code);
        $this->view->assign('dir',$dir);
        $this->view->display('template_fileedit.php');
    }

    function filedelAct(){
        $theme = getGet('theme');
        $file = getGet('file');

        $path = ROOT_DIR.'templates'.DS.$theme.DS.$file;

        if(@unlink($path)){
            alert('删除成功！',true,'js_reload');
        }else{
            alert('删除失败！可能是权限不足！');
        }
    }

    function enableAct(){
        $theme = getGet('theme');
        
        app('base')->setSetting('current_theme',$theme);
        
        alert('切换成功！',true,'js_reload');
    }

    function labelAct(){
        $page = getGet('page',1);

        $m_label = M('labels');

        $totalCount = $m_label->count();
        $pageurl = U('base','template','a=label&page=%page%');

        $pager = new Pager($page,C('pageset.admin',15),$totalCount,$pageurl);
        $pager->config(C('adminpage'));
        $limit = $pager->getLimit();

        $rows = $m_label->findAll(array(
            'start' => $limit['start'],
            'limit' => $limit['limit']
        ));
        $this->view->assign('rows',$rows);

        $this->view->assign('pagestr',$pager->html());
        $this->view->display('label.php');
    }

    function labeldelAct(){
        $id = intval(getGet('id'));

        $m_label = M('labels');
        
        if($m_label->delete($id)){
            alert('删除成功！',true,U('base','template','a=label'));
        }else{
            alert('删除失败！');
        }
    }

    function labeladdAct(){
        $m_label = M('labels');
        if(isPost()){
            $name = trim(getPost('name'));
            $pure_txt = intval(getPost('pure_txt'));
            if(empty($name)){
                alert('请输入标签名！');
            }

            $data = array(
                'name'=>$name,
                'pure_txt'=>$pure_txt,
                'data' => $pure_txt?trim(getPost('data_pure')):trim(getPost('data'))
            );

            if($m_label->insert($data)){
                app('base')->refreshLabel($name);
                alert('添加成功！',true,U('base','template','a=label'));
            }else{
                alert('添加失败！');
            }
        }
        $label_info = $m_label->loadDefault();
        $this->view->assign('label_info',$label_info);
        $this->view->display('label_edit.php');
    }

    function labeleditAct(){
        $m_label = M('labels');
        $id = intval(getGet('id'));
        if(isPost()){
            $name = trim(getPost('name'));
            $pure_txt = intval(getPost('pure_txt'));
        
            if(empty($name)){
                alert('请输入标签名！');
            }

            $data = array(
                'name'=>$name,
                'pure_txt'=>$pure_txt,
                'data' => $pure_txt?trim(getPost('data_pure')):trim(getPost('data'))
            );

            if($m_label->update($id,$data)){
                app('base')->refreshLabel($name);

                alert('保存成功！',true,U('base','template','a=label'));
            }else{
                alert('保存失败！');
            }
        }
        $label_info = $m_label->load($id);
        
        if(!$label_info){
            alert('您要编辑的标签不存在！');
        }
        //fix ckeditor bug
        $label_info['data'] = str_replace('&', '&amp;', $label_info['data']);

        $this->view->assign('label_info',$label_info);
        $this->view->display('label_edit.php');
    }

    
}
