<?php
defined('IN_MWEB') or die('access denied');

checkLogin();

function pluploadProcess($fileName,$append=true,$fullPath=false){
    $fileName = preg_replace('/[^\w\._]+/', '', $fileName);

    $targetDir = C('tmp_dir');
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

@set_time_limit(5*60);
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$chunk = getRequest('chunk',0);
$chunks = getRequest('chunks',0);
$filename = getRequest('name','');

if( 
    (@$_G['settings']['album_email_notactive_cannotpost'] && !$_G['user']['email_actived'] ) ||
    (@$_G['settings']['album_mobile_notactive_cannotpost'] && !$_G['user']['mobile_actived']) 
){
    $return = array(
        'jsonrpc'=>'2.0',
        'error'=>array(
            'code'=>105,
            'message'=>'不允许上传.'
        ),
        'id'=>'id');
    echo json_encode($return);
    exit;
}
$status = pluploadProcess($filename,$chunk!=0);

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


if($chunk+1 == $chunks){
    $targetDir =  C('tmp_dir');
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

    $m_photos = M('album_photos');
    $exiflib = new exif;
    $imagelib = image::instance();
    $storagelib = storage::instance();

    $fileext = fileext($filename);
    $tmpfile = $targetDir . DS . $filename;
    $path = 'albums/'.date('Ymd').'/'.str_replace('.','',microtime(true)).rand(10,99).'.'.$fileext;

    $filetype = C('upfiles.image');
    if($filetype && !in_array($fileext,explode(',', $filetype['ext']))){//如果不是支持的文件类别直接清除临时文件
        @unlink($tmpfile);
        $return = array(
        'jsonrpc'=>'2.0',
        'error'=>array(
            'code'=>103,
            'message'=>'不支持的格式.'
        ),
        'id'=>'id');
        echo json_encode($return);
        exit;
    }

    //保存图片并返回图片
    $data = array();
    $data['uid'] = $_G['user']['id'];
    $data['cate_id'] =  0;
    $data['album_id'] = 0;
    $data['create_time'] = time();
    $data['priv_type'] = 1;
    $data['path'] = $path;
    if (isset($_FILES['file']) && isset($_FILES['file']['tmp_name'])){
        $data['name'] = htmlspecialchars($_FILES['file']['name']);
    }else{
        $data['name'] = $data['create_time'];//默认名称直接改为时间戳
    }
    $data['tags'] = '';
    $data['taken_time'] = 0;
    $data['exif'] = '';

    $imginfo = @getimagesize($tmpfile);
    $data['width'] = @$imginfo[0];
    $data['height'] = @$imginfo[1];

    //引入一个变量一个图片只加载一次
    $imgloaded = false;
    if( @$imginfo[2] == 2){//如果图片是jpg
        $exif = $exiflib->get_exif($tmpfile);
        if($exif){
            $data['exif'] = serialize($exif);
            $taken_time = strtotime($exif['DateTimeOriginal']);
            $data['taken_time'] = $taken_time;
            $exif['Orientation'] = isset($exif['Orientation'])?$exif['Orientation']:0;
            //修正手机拍摄的照片旋转角度的问题
            if($exif['Orientation']==6){
                $imagelib->load($tmpfile);
                $imagelib->rotate(-90);
                $imgloaded = true;
                //交换宽高
                $tmp = $data['width'];
                $data['width'] = $data['height'];
                $data['height'] = $tmp;
            }elseif($exif['Orientation']==8){
                $imagelib->load($tmpfile);
                $imagelib->rotate(90);
                $imgloaded = true;
                //交换宽高
                $tmp = $data['width'];
                $data['width'] = $data['height'];
                $data['height'] = $tmp;
            }elseif($exif['Orientation']==3){
                $imagelib->load($tmpfile);
                $imagelib->rotate(180);
                $imgloaded = true;
            }
        }
    }
    if(@$_G['settings']['album_resize_img']){//开启了缩放
        $max_width = intval($_G['settings']['album_resize_img_w']);
        $max_height = intval($_G['settings']['album_resize_img_h']);
        if($data['width'] > $max_width || $data['height'] > $max_height){
            if(!$imgloaded){
                $imagelib->load($tmpfile);
            }
            $imagelib->resizeScale($max_width,$max_height);
            $imgloaded = true;
            $arr['width'] = $imagelib->getWidth();
            $arr['height'] = $imagelib->getHeight();
        }
    }
    if(@$_G['settings']['album_enable_watermark']){//开启了水印
        if(!$imgloaded){
            $imagelib->load($tmpfile);
        }
        $imagelib->waterMarkSetting(array(
            'water_mark_image' => $storagelib->localfile($_G['settings']['album_watermark_path']),
            'water_mark_pos' => $_G['settings']['album_water_mark_pos']
        ));
        $imagelib->waterMarkImg();
        $imgloaded = true;
    }
    if($imgloaded){
        $imagelib->save($tmpfile);
    }
    if(!$storagelib->save($tmpfile,$path)){
        $return = array(
        'jsonrpc'=>'2.0',
        'error'=>array(
            'code'=>104,
            'message'=>'保存失败.'
        ),
        'id'=>'id');
        echo json_encode($return);
        exit;
    }
    
    $m_photos->insert($data);
    $id = $m_photos->insertId();

    $return = array('jsonrpc'=>'2.0','result'=>['path'=>D($path),'width'=>$data['width'],'height'=>$data['height']],'id'=>$id);
}
echo json_encode($return);