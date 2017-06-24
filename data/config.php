<?php
defined('IN_MWEB') or die('access denied');

return array(
    'base_url' => '/',
    'public_url' => '/public/',
    'tmp_dir' => DATA_PATH.'cache/tmp',
    'database' => array(
        'adapter'  => 'pdomysql',
        'host'     => '127.0.0.1',
        'port'     => '3306',
        'dbuser'   => 'root',
        'dbpass'   => 'dragon',
        'dbname'   => 'newmeiu',
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
    'storage' => array(
        'adapter' => 'file', //存储引擎，目前支持file,qiniu两种
        'setting' => array(
            'dirpath' => ROOT_DIR.'public/upfiles/',
            'url_pre' => '/public/upfiles/'
        )
    ),
    'image' => array(
        'engine' => 'gd' //图像处理引擎，目前支持gd,imagick两种
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
        'admin' => 18,
        'filelist'=>10
    ),
    'adminpage' => array(
            'showSinglePage'   => false,
            'prefix'=>'<div class="pages">',
            'current'=>'<a class="on"><em><i>%page%</i></em></a>',
            'page'=>'<a href="%link%"><em><i>%page%</i></em></a>',
            'prev'=>'<a href="%link%"><em><i>上一页</i></em></a>',
            'next'=>'<a href="%link%"><em><i>下一页</i></em></a>',
            'first'=> '<a href="%link%"><em><i>%page%</i></em></a><span>...</span>',
            'last'=> '<span>...</span><a href="%link%"><em><i>%page%</i></em></a>',
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
        'image' => array('title' => '请选择图片','ext' => 'jpg,jpeg,gif,png','max' => '15m','memo' => '请选择小于15M的图片，支持jpg,jpeg,gif,png'),
        'watermark' => array('title' => '请选择水印图片','ext' => 'png','max' => '1m','memo' => '请选择小于1M的图片，仅支持png'),
        'flash' => array('title' => '请选择Flash','ext' => 'swf','max' => '5m','memo' => '请选择小于5M的Flash'),
        'audio' => array('title' => '请选择音频','ext' => 'mp3,wma','max' => '20m','memo' => '请选择小于20M的音频，支持mp3,wma'),
        'video' => array('title' => '请选择视频','ext' => 'flv,rmvb,wmv,avi','max' => '100m','memo' => '请选择小于100M的视频，支持flv,rmvb,wmv,avi'),
        'attach' => array('title' => '请选择附件','ext' => 'zip,rar,7z,doc,xls,ppt,docx,xlsx,pptx','max' => '10m','memo' => '请选择小于10M的附件')
    ), 
    'types' => include('define_types.php'),
);