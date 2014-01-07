<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php if(isset($site_title)): echo $site_title; else: ?><?php echo getSetting('site_title');?> - <?php echo getSetting('site_sub_title');?><?php endif; ?></title>
    <meta name="keywords" content="<?php if(isset($site_keywords)){ echo $site_keywords; } ?>" />
    <meta name="description" content="<?php if(isset($site_description)){ echo $site_description; } ?>" />
    <script type="text/javascript" src="<?php echo S('base','js/jquery.min.js');?>"></script>
    <link rel="stylesheet" href="<?php echo S('_tpl','css/main.css');?>" type="text/css" />
</head>
<body>
<div class="site-header">
    <div class="inner clearfix">
        <div class="head-user" id="user_status"></div>
        <script type="text/javascript">
            $('#user_status').load('<?php echo U('user','status');?>');
        </script>
        <div class="head-name">
            <a href="<?php echo U('base','index');?>">
                <img src="<?php echo S('_tpl','images/logo.png');?>" alt="<?php echo getSetting('site_title');?>">
            </a>
        </div>
        <div class="head-nav">
            
        </div>
    </div>
</div>