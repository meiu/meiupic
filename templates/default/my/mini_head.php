<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php if(isset($site_title)): echo $site_title; else: ?><?php echo getSetting('site_title');?> - <?php echo getSetting('site_sub_title');?><?php endif; ?></title>
    <meta name="keywords" content="<?php if(isset($site_keywords)){ echo $site_keywords; } ?>" />
    <meta name="description" content="<?php if(isset($site_description)){ echo $site_description; } ?>" />
    <script type="text/javascript" src="<?=S('base','js/jquery.min.js');?>"></script>
    <script src="<?php echo S('base','js/jquery.form.js');?>"></script>
    <script src="<?php echo S('base','dialog/jquery.artDialog.js?skin=modern');?>"></script>
    <script src="<?php echo S('base','dialog/plugins/iframeTools.js');?>"></script>
    <script src="<?php echo S('base','jquery-ui/jquery-ui.min.js');?>"></script>
    <script src="<?php echo S('user','js/my.js');?>"></script>
    <link rel="stylesheet" href="<?=ST('css/user.css')?>" type="text/css" />
    <style>
        html,body{background: #fff;}
    </style>
</head>
<body>