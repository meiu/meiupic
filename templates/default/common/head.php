<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php if(isset($site_title)): echo $site_title; else: ?><?php echo getSetting('site_title');?> - <?php echo getSetting('site_sub_title');?><?php endif; ?></title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
    <meta name="keywords" content="<?php if(isset($site_keywords)){ echo $site_keywords; } ?>" />
    <meta name="description" content="<?php if(isset($site_description)){ echo $site_description; } ?>" />
    <script type="text/javascript" src="<?=S('base','js/jquery.min.js');?>"></script>
    <script src="<?php echo S('base','js/jquery.form.js');?>"></script>
    <script src="<?php echo S('base','dialog/jquery.artDialog.js?skin=modern');?>"></script>
    <script src="<?php echo S('base','dialog/plugins/iframeTools.js');?>"></script>
    <script src="<?php echo S('base','jquery-ui/jquery-ui.min.js');?>"></script>
    <link rel="stylesheet" href="<?php echo S('base','jquery-ui/jquery-ui.min.css');?>" />
    <link rel="stylesheet" href="<?php echo S('base','plupload/jquery.ui.plupload/css/jquery.ui.plupload.css'); ?>" type="text/css" />
    <script type="text/javascript" src="<?php echo S('base','plupload/plupload.full.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo S('base','plupload/jquery.ui.plupload/jquery.ui.plupload.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo S('base','plupload/i18n/zh_CN.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo S('album','js/jquery.justifiedGallery.min.js'); ?>"></script>
    <script src="<?php echo S('user','js/my.js');?>"></script>
    <link rel="stylesheet" href="<?=ST('css/user.css')?>" type="text/css" />
    <link rel="stylesheet" href="<?=S('album','css/justifiedGallery.min.css')?>" type="text/css" />
    <script type="text/javascript" src="<?php echo ST('js/main.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo ST('js/screenfull.js'); ?>"></script>
    <script>
        var site_title= '<?php echo getSetting('site_title');?>';
    </script>
    <?php  echo x_comment_helper::initJS(); ?>
</head>
<body>
<div class="my-header">
    <div class="inner clearfix">
        <div class="head-user">
            <?php if($_G['user']): ?>
            <ul class="main-list">
                <li class="sub-list-trigger"><a class="user-info" href="<?=U('my','index')?>"><img src="<?=app('user')->getAvatar($_G['user'],'small')?>" /><?php echo $_G['user']['nickname'];?></a>
                    <ul class="sub-list">
                        <li><a href="<?=U('my','index')?>">用户中心</a></li>
                        <li><a href="<?=U('my','setting')?>">用户设置</a></li>
                        <li><a href="<?=U('user','login','a=logout')?>">退出</a></li>
                    </ul>
                </li>
                <li><a href="<?=U('album','post')?>" class="publish-btn">上传</a></li>
            </ul>
            <?php else: ?>
            <div class="unlogin">
            <a href="<?=U('user','login')?>">登录</a> <a class="reg" href="<?=U('user','register')?>">注册</a>
            </div>
            <?php endif; ?>
        </div>

        <a class="head-logo" href="<?=U('base','index')?>"></a>
        <div class="head-nav">
            <ul class="main-list">
                <li <?php if($_G['uri']['app']=='base'){echo 'class="active"';}?>><a href="<?=U('base','index')?>">首页</a></li>
                <li <?php if($_G['uri']['app']=='album'){echo 'class="active"';}?>><a href="<?=U('album','index')?>">发现</a></li>
                <li <?php if($_G['uri']['app']=='user'){echo 'class="active"';}?>><a href="<?=U('user','index')?>">摄影师</a></li>
                <li <?php if($_G['uri']['app']=='album' && $_G['uri']['m']=='cate'){echo 'class="active"';}?>><a href="#">分类</a></li>
            </ul>
        </div>
    </div>
</div>