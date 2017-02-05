<?php
class AlbumUpload extends Adminbase{
	function indexAct(){
		$aid = getGet('aid');
		$filetype = C('upfiles.image');
	    $this->view->assign('filetype',$filetype);
		$this->view->decorate(false);
		$this->view->assign('aid',$aid);
	    $this->view->assign('upload_dir',C('upload.dir'));
	    $this->view->display('upload.php');
	}

	function saveAct(){
		global $_G;

		@set_time_limit(120);
        $filetype = C('upfiles.image');

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
        $attachdir = 'albums/'.$date;

        $files_count = intval(getPost('muilti_uploader_count'));
        $aid = intval(getGet('aid'));

        $albumInfo = $aid?M('albums')->load($aid):false;
        $uploaded_photos = array();

        $m_photos = M('album_photos');
        $exiflib = new exif;
        $imagelib = image::instance();
        $storagelib = storage::instance();
        for($i=0;$i<$files_count;$i++){
            $tmpfile = $targetDir . DS . getPost("muilti_uploader_{$i}_tmpname");
            $filename = getPost("muilti_uploader_{$i}_name");
            $status =  getPost("muilti_uploader_{$i}_status");
            $fileext = fileext($filename);
            $path = $attachdir.'/'.str_replace('.','',microtime(true)).rand(10,99).'.'.$fileext;
            
            if($status == 'done' && file_exists($tmpfile)){
                if(!in_array($fileext,explode(',', $filetype['ext']))){//如果不是支持的文件类别直接清除临时文件
                    @unlink($tmpfile);
                    continue;
                }
                
                $data = array();
                $data['uid'] = $_G['user']['id'];
	            $data['cate_id'] =  $albumInfo?$albumInfo['cate_id']:0;
	            $data['album_id'] = $aid;
	            $data['create_time'] = time();
	            $data['priv_type'] = $albumInfo?$albumInfo['priv_type']:0;
                $data['path'] = $path;
                $data['name'] = $filename;
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
                    continue;
                }
                
                $m_photos->insert($data);
                $data['id'] = $m_photos->insertId();
                $uploaded_photos[] = $data;
            }
        }

        if($aid && $albumInfo){
        	app('album')->updatePhotoNum($aid);
        	if(!$albumInfo['cover_id']){
        		app('album')->updateCover($aid);
        	}
        }
        $this->view->decorate(null,'_mini.php');

        $cates = app('album')->getCateList();
        $this->view->assign('photos',$uploaded_photos);
        $this->view->assign('aid',$aid);
        $this->view->assign('cates',$cates);
        $this->view->display('photo_add.php');
	}

	function saveinfoAct(){
    	$m_photos = M('album_photos');

    	$cate_id = intval(getPost('cate_id'));
        $name = safestr(trim(getPost('name')));
        $tags = trim(getPost('tags'));
        $data['description'] = trim(getPost('description'));
        if($cate_id){
        	$data['cate_id'] = $cate_id;
        }
        $data['priv_type'] = intval(getPost('priv_type'));
        $photonames = getPost('photonames');

        foreach($photonames as $id=>$photoname){
            $data['name'] = $name?$name:$photoname;
            $data['tags'] = $tags;

            $m_photos->update($id,$data);
        }

        alert('保存成功！',true,'js_reload');
    }

    function thumbAct(){
    	$path = getGet('path');
    	redirect(thumb($path,180,180,2));
    }
}