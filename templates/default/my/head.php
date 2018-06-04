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
    <link rel="stylesheet" href="<?php echo S('base','plupload/jquery.ui.plupload/css/jquery.ui.plupload.css'); ?>" type="text/css" />
    <script type="text/javascript" src="<?php echo S('base','plupload/plupload.full.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo S('base','plupload/jquery.ui.plupload/jquery.ui.plupload.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo S('base','plupload/i18n/zh_CN.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo S('album','js/jquery.justifiedGallery.min.js'); ?>"></script>
    <script src="<?php echo S('user','js/my.js');?>"></script>
    <link rel="stylesheet" href="<?php echo ST('css/user.css')?>" type="text/css" />
    <link rel="stylesheet" href="<?php echo S('album','css/justifiedGallery.min.css')?>" type="text/css" />
    <script type="text/javascript" src="<?php echo ST('js/main.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo ST('js/screenfull.js'); ?>"></script>
    <?php
    //七牛直传
    $c = C('storage');
    if($c['adapter'] == 'qiniu'):
    $storlib = new Storage::instance();
    $uptoken = $storlib->uploadToken($c['setting']['bucket']);
    ?>
    <meta name="uptoken" content="<?php echo $uptoken;?>" />
    <script type="text/javascript" src="<?php echo ST('js/upload.normal.js'); ?>"></script>
    <?php else: ?>
    <script type="text/javascript" src="<?php echo ST('js/upload.normal.js'); ?>"></script>
    <?php endif; ?>
    <script>
        var site_title= '<?php echo getSetting('site_title');?>';
        var PUBLIC_URL= '<?php echo C('public_url');?>';
    </script>
    <?php  echo x_comment_helper::initJS(); ?>
</head>
<body>
<div class="my-header">
    <div class="inner clearfix">
        <div class="head-user">
            <?php if($_G['user']): ?>
            <ul class="main-list">
                <li class="sub-list-trigger"><a class="user-info" href="<?php echo U('my','index')?>"><img src="<?php echo app('user')->getAvatar($_G['user'],'small')?>" /><?php echo $_G['user']['nickname'];?></a>
                    <ul class="sub-list">
                        <li><a href="<?php echo U('my','index')?>">用户中心</a></li>
                        <li><a href="<?php echo U('my','setting')?>">用户设置</a></li>
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

        <a class="head-logo" href="<?php echo U('base','index')?>"><i>用户中心</i><em>返回首页</em></a>
        <div class="head-nav">
            <?php 
            $applist = app('my')->getAppMenus();
            ?>
            <ul class="main-list">
                <?php foreach ($applist as $key => $value): 
                    if($value['ismy']):
                    ?>
                    <li <?php if ($value['id']==$_G['uri']['app']){echo 'class="active"';}?>><a href="<?php echo U($value['id'],'my'); ?>"><?php echo $value['name']; ?></a></li>
                <?php 
                    endif;
                endforeach ?>
            </ul>
        </div>
    </div>
</div>


<div class="user-banner" style="background-image: url(<?php echo app('user')->getBannerBg($_G['user']);?>)">
    <div class="banner-info">
        <a href="javascript:void(0);" class="banner-user-avatar">
            <img src="<?php echo app('user')->getAvatar($_G['user'],'large');?>">
        </a>
        <div class="banner-user-info">
            <div>
                <span class="info-name"> <?php echo $_G['user']['nickname']; ?> </span>
            </div>
            <div class="desc">
                <?php echo $_G['user']['description']; ?>
            </div>
            <ul>
                <li> 
                    <a href="<?php echo U('friend','my_friends'); ?>"> 关注 <?php echo $_G['user']['friends']; ?> </a> 
                </li>
                <li> 
                    <a href="<?php echo U('friend','my_followers'); ?>"> 粉丝 <?php echo $_G['user']['followers']; ?> </a> 
                </li>
            </ul>
        </div>
        <div class="banner-act">
            <label id="change-banner">
                编辑封面 &gt; 
            </label>
        </div>
    </div>
</div>
<script>
    uploadBanner('<?php echo U("my","savebg")?>');
</script>