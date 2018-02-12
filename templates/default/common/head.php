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
    <link rel="stylesheet" href="<?=ST('css/main.css')?>" type="text/css" />
</head>
<body>
<div class="site-header">
    <div class="inner clearfix">
        <div class="head-user" id="user_status"></div>
        <div class="head-name">
            <a href="<?php echo U('base','index');?>">
                <img src="<?php echo ST('images/logo.png');?>" alt="<?php echo getSetting('site_title');?>">
            </a>
        </div>
        <div class="head-nav">
            
        </div>
    </div>
</div>