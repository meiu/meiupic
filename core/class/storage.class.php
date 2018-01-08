<?php

defined('IN_MWEB') || exit('Access denied!');

class Storage{

    static function &instance($config = ''){
        static $instance;

        $key = toGuidString($config);
        if (is_null($instance) || !isset($instance[$key])) 
            $instance[$key] = self::factory($config);
        return $instance[$key];
    }

    static function factory($config = ''){
        if(!$config){
            $config = C('storage');
        }
        $filename = strtolower($config['adapter']);
        $class = 'Storage'.ucfirst($filename);
        
        if(is_file(CORE_PATH.'driver/storage/'.$filename.'.php')){
            require_once(CORE_PATH.'driver/storage/'.$filename.'.php');
            $obj = new $class($config['setting']);
        }else{
            trace('Storage Driver“'.$config['adapter'].'”未找到！','Core','ERR');
        }
        return $obj;
    }

    public function save($src,$dst,$delete_src=true){
        return false;
    }
    public function delete($file)
    {
        return false;
    }

    public function localfile($path){
        return '';
    }

    public function getUrl($path){
        return '';
    }

    public function getThumb($path,$w,$h,$t,$placeholder){
        return '';
    }

    public function download($path){
        return '';
    }
}