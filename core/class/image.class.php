<?php
defined('IN_MWEB') || exit('Access denied!');

class Image{

    public static function &instance(){
        static $instance;
        if (is_null($instance)) 
            $instance = self::factory();
        return $instance;
    }

    public static function factory(){
        $image_engine = C('image.engine');
        $filename = strtolower($image_engine);
        $class = 'Image'.ucfirst($image_engine);
        if(is_file(CORE_PATH.'driver/image/'.$filename.'.php')){
            require_once(CORE_PATH.'driver/image/'.$filename.'.php');
            $view = new $class();
        }else{
            trace('图形引擎 Driver('.IMAGE_ENGINE.')未找到！','Core','ERR');
        }
        return $view;
    }
}