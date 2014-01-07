<?php

defined('IN_MWEB') || exit('Access denied!');

class Cache{

    static function &instance($config = ''){
        static $instance;

        $key = toGuidString($config);
        if (is_null($instance) || !isset($instance[$key])) 
            $instance[$key] = self::factory($config);
        return $instance[$key];
    }

    static function factory($config = ''){
        if(!$config){
            $config = C('cache');
        }
        $filename = strtolower($config['adapter']);
        $class = 'Cache'.ucfirst($filename);
        
        if(is_file(CORE_PATH.'driver/cache/'.$filename.'.php')){
            require_once(CORE_PATH.'driver/cache/'.$filename.'.php');
            $cache = new $class($config['policy']);
        }else{
            trace('Cache Driver“'.$config['adapter'].'”未找到！','Core','ERR');
        }
        return $cache;
    }
}