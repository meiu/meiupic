<?php

defined('IN_MWEB') || exit('Access denied!');

class StorageFile extends Storage
{
    private $_setting = array(
        'dirpath' => 'public/upfiles/',
        'url_pre' => '/public/upfiles/'
    );

    public function __construct($config){
        if(is_array($config)){
            $this->_setting = array_merge($this->_setting, $config);
        }
    }
    public function save($src,$dst,$delete_src=true){
        $realpath = $this->_setting['dirpath'].$dst;
        $newdir = dirname($realpath);
        //判断文件夹，不存在就创建
        if(!is_dir($newdir)){
            @mkdir($newdir,0755,true);
        }
        
        if(@copy($src,$realpath)){
            //删除原文件
            if($delete_src){
                @unlink($src);
            }
            //@chmod($realpath,0755);
            return true;
        }else{
            return false;
        }
    }

    public function delete($file)
    {
        return @unlink($this->_setting['dirpath'].$file);
    }

    public function localfile($path){
        return $this->_setting['dirpath'].$path;
    }

    public function getUrl($path){
        return $this->_setting['url_pre'].$path;
    }

    public function getThumb($path,$w,$h,$t,$placeholder){
        $realpath = $this->_setting['dirpath'].$path;

        if(empty($path) || !file_exists($realpath)) return $placeholder;

        $newimgpath = "thumb/{$w}_{$h}_{$t}/{$path}";

        $imgurl = $this->_setting['url_pre'].$path;
        $newrealpath = $this->_setting['dirpath'].$newimgpath;
        $newimgurl   = $this->_setting['url_pre'].$newimgpath;

        if(file_exists($newrealpath)){
            return $newimgurl;
        }
        $newdir = dirname($newrealpath);
        //判断文件夹，不存在就创建
        if(!is_dir($newdir)){
            @mkdir($newdir,0755,true);
        }

        list($width_t, $height_t, $type, $attr) = getimagesize($realpath);
        if($w>=$width_t && $h>=$height_t) return $imgurl;

        $img = image::instance();

        $img->load($realpath);
        if($t == 1){
            $img->resizeCut($w,$h);
        }elseif($t == 2){
            $img->resizeScale($w,$h);
        }else{
            $img->resize($w,$h);
        }
        $img->save($newrealpath);

        return $newimgurl;
    }
}