<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php if(isset($site_title)): echo $site_title; else: ?><?php echo getSetting('site_title');?> - <?php echo getSetting('site_sub_title');?><?php endif; ?></title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
    <meta name="keywords" content="<?php if(isset($site_keywords)){ echo $site_keywords; } ?>" />
    <meta name="description" content="<?php if(isset($site_description)){ echo $site_description; } ?>" />
    <script type="text/javascript" src="<?php echo S('base','js/jquery.min.js');?>"></script>
    <script src="<?php echo S('base','js/jquery.form.js');?>"></script>
    <script src="<?php echo S('base','dialog/jquery.artDialog.js?skin=modern');?>"></script>
    <script src="<?php echo S('base','dialog/plugins/iframeTools.js');?>"></script>
    <script src="<?php echo S('base','jquery-ui/jquery-ui.min.js');?>"></script>
    <link rel="stylesheet" href="<?php echo S('base','jquery-ui/jquery-ui.min.css');?>" />
    <script type="text/javascript" src="<?php echo S('album','js/jquery.justifiedGallery.min.js'); ?>"></script>
    <script src="<?php echo S('user','js/my.js');?>"></script>
    <link rel="stylesheet" href="<?php echo ST('css/user.css')?>" type="text/css" />
    <link rel="stylesheet" href="<?php echo S('album','css/justifiedGallery.min.css')?>" type="text/css" />
    <script type="text/javascript" src="<?php echo ST('js/main.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo S('comment','js/comment.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo ST('js/screenfull.js'); ?>"></script>
    <?php if($_G['user']): ?>
        <link rel="stylesheet" href="<?php echo S('base','plupload/jquery.ui.plupload/css/jquery.ui.plupload.css'); ?>" type="text/css" />
        <script type="text/javascript" src="<?php echo S('base','plupload/plupload.full.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo S('base','plupload/jquery.ui.plupload/jquery.ui.plupload.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo S('base','plupload/i18n/zh_CN.js'); ?>"></script>
        <?php
        //七牛直传
        $c = C('storage');
        if($c['adapter'] == 'qiniu' && $c['setting']['direct']):
        $storlib = Storage::instance();
        $uptoken = $storlib->uploadToken($c['setting']['bucket']);
        ?>
        <meta name="uptoken" content="<?php echo $uptoken;?>" />
        <meta name="updomain" content="<?php echo $c['setting']['url_pre'];?>" />
        <script type="text/javascript" src="<?php echo ST('js/qiniu.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo ST('js/upload.qiniu.js'); ?>"></script>
        <script type="text/javascript">
            var qiniu_area = qiniu.region.<?php echo $c['setting']['area'];?>;
        </script>
        <?php else: ?>
        <script type="text/javascript" src="<?php echo ST('js/upload.normal.js'); ?>"></script>
        <?php endif; ?>
    <?php endif; ?>
    <script>
        var site_title= '<?php echo getSetting('site_title');?>';
        var PUBLIC_URL= '<?php echo C('public_url');?>';
    </script>
    <?php // echo x_comment_helper::initJS(); ?>
</head>
<body>
<div class="my-header">
    <div class="inner clearfix">
        <div class="head-user">
            <?php if($_G['user']): ?>
            <ul class="main-list">
                <li class="sub-list-trigger"><a class="user-info" href="<?php echo U('space','index','id='.$_G['user']['id'])?>"><img src="<?php echo app('user')->getAvatar($_G['user'],'small')?>" /><?php echo $_G['user']['nickname'];?></a>
                    <ul class="sub-list">
                        <li><a href="<?php echo U('space','setting')?>">用户设置</a></li>
                        <li><a href="<?php echo U('user','login','a=logout')?>">退出</a></li>
                    </ul>
                </li>
                <li><a href="<?php echo U('album','post')?>" class="publish-btn">上传</a></li>
            </ul>
            <?php else: ?>
            <div class="unlogin">
            <a href="<?php echo U('user','login')?>">登录</a> <a class="reg" href="<?php echo U('user','register')?>">注册</a>
            </div>
            <?php endif; ?>
        </div>
        <div class="head-search"><form method="get" action="<?php echo U('album','search');?>"><input type="search" placeholder="搜索作品" value="<?php echo getGet('keyword');?>" autocomplete="off" name="keyword" /></form></div>
        <a class="head-logo" href="<?php echo U('base','index')?>"></a>
        <div class="head-nav">
            <ul class="main-list">
                <li <?php if($_G['uri']['app']=='base'){echo 'class="active"';}?>><a href="<?php echo U('base','index')?>">首页</a></li>
                <li <?php if($_G['uri']['app']=='album' && $_G['uri']['m']!='cate' && $_G['uri']['m']!='photos'){echo 'class="active"';}?>><a href="<?php echo U('album','index')?>">发现</a></li>
                <li <?php if($_G['uri']['app']=='user'){echo 'class="active"';}?>><a href="<?php echo U('user','index')?>">摄影师</a></li>
                <li class="sub-list-trigger  <?php if($_G['uri']['app']=='album' && ($_G['uri']['m']=='cate' || $_G['uri']['m']=='photos')){echo 'active';}?>"><a href="javascript:void(0)">分类</a>
                    <ul class="sub-list">
                    <?php 
                    $catelist = app('album')->getCateList(0); 
                    foreach($catelist as $cate): ?>
                        <li><a href="<?php echo app('album')->makeCateLink($cate);?>"><?php echo $cate['name']; ?></a></li>
                    <?php endforeach; ?>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>