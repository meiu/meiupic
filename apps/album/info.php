<?php
defined('IN_MWEB') or die('access denied');

return array(
    'name'      => '图片模块',
    'version'   => '1.0',
    'desc'      => '图片模块',
    'author'    => 'Lingter',
    'author_url'=> 'http://www.meiu.cn',
    'issystem'  => '1',
    'adminmenu' => array(
        array('name'=>'相册','app'=>'album','mod'=>'index','fixed'=>false),
        array('name'=>'照片','app'=>'album','mod'=>'photo','fixed'=>false),
    )
);