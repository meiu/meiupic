<?php
defined('IN_MWEB') or die('access denied');

return array(
    'name'      => '系统',
    'version'   => '1.0',
    'desc'      => '系统核心模块',
    'author'    => 'Lingter',
    'author_url'=> 'http://www.meiu.cn',
    'issystem'  => '1',
    'adminmenu' => array(
        array('name'=>'首页','app'=>'base','mod'=>'index','fixed'=>true),
        array('name'=>'模版','app'=>'base','mod'=>'template','fixed'=>true),
        array('name'=>'设置','app'=>'base','mod'=>'setting','fixed'=>true)
    )
);