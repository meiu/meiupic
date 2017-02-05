<?php 
defined('IN_MWEB') or die('access denied');


class BaseUpfile extends Adminbase{
    function indexAct(){
        $num = getGet('num',1);
        $type = getGet('type','image');
        $attach = getGet('attach','1');

        $this->view->assign('num',$num);
        $this->view->assign('type',$type);
        $this->view->assign('attach',$attach);

        $filetype = C('upfiles.'.$type);

        if(!$filetype){
            alert('不支持`'.$type.'`类文件上传！');
        }

        $this->view->assign('filetype',$filetype);

        $this->view->decorate(false);
        $this->view->assign('CKEditor',getGet('CKEditor'));
        $this->view->assign('CKEditorFuncNum',getGet('CKEditorFuncNum'));
        $this->view->assign('upload_dir',C('upload.dir'));
        $this->view->display('upfile.php');
    }

    function filelistAct(){
        $num = getGet('num',1);
        $type = getGet('type','image');
        $name = getRequest('name');
        $date = getRequest('date');

        $page = getGet('page',1);

        $m_upfile = M('upfiles');

        $where = "isthumb=0 and filetype=".$m_upfile->escape($type);
        if($name){
            $where .= " and name like '".$m_upfile->escape($name,false)."%'";
        }
        if($date){
            $where .= " and addtime between ".strtotime($date.' 00:00:00')." and ".strtotime($date.' 23:59:59');
        }

        $totalCount = $m_upfile->count($where);
        $pageurl = U('base','upfile','a=filelist&num='.$num.'&name='.$name.'&date='.$date.'&type='.$type.'&page=%page%');

        $pager = new Pager($page,C('pageset.filelist',10),$totalCount,$pageurl);
        $pager->config(C('adminpage'));
        $limit = $pager->getLimit();
        $this->view->assign('pagestr',$pager->html());

        $rows = $m_upfile->findAll(array(
            'where' => $where,
            'start' => $limit['start'],
            'limit' => $limit['limit'],
            'order' => 'id desc'
        ));

        $this->view->assign('rows',$rows);
        $this->view->assign('num',$num);
        $this->view->assign('name',$name);
        $this->view->assign('date',$date);

        $this->view->decorate(false);
        $this->view->display('upfile_filelist.php');
    }

    //上传进程
    function _uploadProcess($fileName,$append=true,$fullPath=false){
        $fileName = preg_replace('/[^\w\._]+/', '', $fileName);

        $targetDir = DATA_PATH.'cache/tmp';
        if (!file_exists($targetDir))
            @mkdir($targetDir);
            
        if($fullPath){
            $filePath = $fileName;
        }else{
            $filePath = $targetDir . DS . $fileName;
        }

        // Look for the content type header
        if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
            $contentType = $_SERVER["HTTP_CONTENT_TYPE"];

        if (isset($_SERVER["CONTENT_TYPE"]))
            $contentType = $_SERVER["CONTENT_TYPE"];
        
        // Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
        if (strpos($contentType, "multipart") !== false) {
            if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
                // Open temp file
                $out = fopen($filePath, !$append ? "wb" : "ab");
                if ($out) {
                    $in = fopen($_FILES['file']['tmp_name'], "rb");

                    if ($in) {
                        while ($buff = fread($in, 4096))
                            fwrite($out, $buff);
                    } else
                        return 101;
                    fclose($in);
                    fclose($out);
                    @unlink($_FILES['file']['tmp_name']);
                } else
                    return 102;
            } else
                return 0;
        }else{
            $out = @fopen($filePath, !$append ? "wb" : "ab");
            if ($out) {
                $in = @fopen("php://input", "rb");
                if ($in) {
                    while ($buff = fread($in, 4096))
                        fwrite($out, $buff);
                } else
                    return 101;
                fclose($in);
                fclose($out);
            } else{
                return 102;
            }
            return 0;
        }
    }

    function uploadprocessAct(){
        @set_time_limit(5*60);
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        
        $chunk = getRequest('chunk',0);
        $chunks = getRequest('chunks',0);
        $filename = getRequest('name','');

        $this->_uploadProcess($filename,$chunk!=0);
        
        switch($status){
            case 100:
            $return = array(
                'jsonrpc'=>'2.0',
                'error'=> array( 
                    'code'=>$status,
                    'message'=>'不能打开临时目录.'
                 ),
                 'id'=>'id');
            break;
            case 101:
            $return = array(
                'jsonrpc'=>'2.0',
                'error'=>array(
                     'code'=>$status,
                     'message'=>'不能打开输入流.'
                 ),
                 'id'=>'id');
            break;
            case 102:
            $return = array(
                'jsonrpc'=>'2.0',
                'error'=>array(
                    'code'=>$status,
                    'message'=>'不能打开输出流.'
                 ),
                 'id'=>'id');
            break;
            case 0:
            $return = array('jsonrpc'=>'2.0','result'=>null,'id'=>'id');
        }

        echo json_encode($return);
    }

    function savefilesAct(){
        @set_time_limit(120);

        $type = getGet('type');

        if(isPost()){
            $m_upfile = M('upfiles');

            $filetype = C('upfiles.'.$type);

            if(!$filetype){
                echo '不支持的文件类型！';exit;
            }

            $targetDir =  DATA_PATH.'cache/tmp';
            //清除很久之前的临时文件,发生概率10%
            if(lucker(10)){
                if (is_dir($targetDir) && ($dir = opendir($targetDir))) {
                    while (($file = readdir($dir)) !== false) {
                        $tmpfilePath = $targetDir . DS . $file;

                        // 删除5个小时之前的临时文件
                        if (filemtime($tmpfilePath) < time() - 18000) {
                            @unlink($tmpfilePath);
                        }
                    }
                    closedir($dir);
                }
            }

            $date = date('Ymd');
            $attachdir = $type.'/'.$date;

            $files_count = intval(getPost('muilti_uploader_count'));
            $uploaded_files = array();

            $storagelib = storage::instance();
            for($i=0;$i<$files_count;$i++){
                $tmpfile = $targetDir . DS . getPost("muilti_uploader_{$i}_tmpname");
                $filename = getPost("muilti_uploader_{$i}_name");
                $status =  getPost("muilti_uploader_{$i}_status");
                $fileext = fileext($filename);
                $path = $attachdir.'/'.str_replace('.','',microtime(true)).rand(10,99).'.'.$fileext;
                $filesize = filesize($tmpfile);
                //$realpath = $upload_setting['dirpath'].$path;
                if($status == 'done' && file_exists($tmpfile)){
                    if(!in_array($fileext,explode(',', $filetype['ext']))){//如果不是支持的文件类别直接清除临时文件
                        @unlink($tmpfile);
                        continue;
                    }
                    if(!$storagelib->save($tmpfile,$path)){
                        continue;
                    }

                    $insert_data = array(
                        'name' =>$filename,
                        'filetype' => $type,
                        'ext' => $fileext,
                        'path' => $path,
                        'size' => $filesize,
                        'isthumb' => 0,
                        'addtime' => time()
                    );
                    $m_upfile->insert($insert_data);
                    $insert_data['id'] = $m_upfile->insertId();
                    $uploaded_files[] = $insert_data;
                }
            }
            $this->view->assign('uploaded_files',$uploaded_files);
            $this->view->assign('num',getGet('num'));
            $this->view->decorate(false);
            $this->view->display('upfile_uploaded.php');
        }
    }
}