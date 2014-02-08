<?php
defined('IN_MWEB') or die('access denied');

return array(
    'base_url' => '/meiupic/',
    'public_url' => '/meiupic/public/',
    'database' => array(
        'adapter'  => 'mysqli',
        'host'     => 'localhost',
        'port'     => '3306',
        'dbuser'   => 'root',
        'dbpass'   => '',
        'dbname'   => 'simplecms',
        'pconnect' => false,
        'charset'  => 'utf8',
        'pre'      => ''
    ),
    'cache' => array(
        'adapter' => 'file', //缓存引擎，目前支持file,memcached两种
        'policy' => array(
            'life_time' => 900,
            'cache_dir' => ROOT_DIR.'data/cache',
        )
    ),
    'image' => array(
        'engine' => 'gd' //图像处理引擎，目前支持gd,imagick两种
    ),
    'upload' => array(
        'dirpath' => ROOT_DIR.'public/upfiles/',
        'dir' => '/public/upfiles/',
        'url_pre' => 'http://newpic.meiu.cn/public/upfiles/'
    ),
    /*Cookie相关设置*/
    'cookie' => array(
        'auth_name' => 'CMS_A',
        'auth_key' => 'add23dsFJFh$$#2sa$33#%&&@sss',
        'domain' => '',
        'path'  => '/'
    ),

    'pageset' => array(
        'default' => 15,
        'admin' => 15,
        'filelist'=>10
    ),
    'page' => array(
            'showSinglePage'   => false,
            'prefix'=>'<div class="pages">',
            'current'=>'<a class="on"><em><i>%page%</i></em></a>',
            'page'=>'<a href="%link%"><em><i>%page%</i></em></a>',
            'prev'=>'<a href="%link%"><em><i>上一页</i></em></a>',
            'next'=>'<a href="%link%"><em><i>下一页</i></em></a>',
            'first'=> '<a href="%link%"><em><i>%page%</i></em></a><span>...</span>',
            'last'=> '<span>...</span><a href="%link%"><em><i>%page%</i></em></a>',
    ),
    'upfiles' => array(
        'image' => array('title' => '请选择图片','ext' => 'jpg,jpeg,gif,png','max' => '5m','memo' => '请选择小于5M的图片，支持jpg,jpeg,gif,png'),
        'flash' => array('title' => '请选择Flash','ext' => 'swf','max' => '5m','memo' => '请选择小于5M的Flash'),
        'audio' => array('title' => '请选择音频','ext' => 'mp3,wma','max' => '20m','memo' => '请选择小于20M的音频，支持mp3,wma'),
        'video' => array('title' => '请选择视频','ext' => 'flv,rmvb,wmv,avi','max' => '100m','memo' => '请选择小于100M的视频，支持flv,rmvb,wmv,avi'),
        'attach' => array('title' => '请选择附件','ext' => 'zip,rar,7z,doc,xls,ppt,docx,xlsx,pptx','max' => '10m','memo' => '请选择小于10M的附件')
    ), 
    'types' => include('define_types.php'),
);