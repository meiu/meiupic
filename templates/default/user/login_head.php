<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php if(isset($site_title)): echo $site_title; else: ?><?php echo getSetting('site_title');?> - <?php echo getSetting('site_sub_title');?><?php endif; ?></title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="<?php if(isset($site_keywords)){ echo $site_keywords; } ?>" />
    <meta name="description" content="<?php if(isset($site_description)){ echo $site_description; } ?>" />
    <script type="text/javascript" src="<?php echo S('base','js/jquery.min.js');?>"></script>
    <script type="text/javascript" src="<?php echo S('base','js/jquery.placeholder.js');?>"></script>
    <link rel="stylesheet" href="<?php echo ST('css/login.css')?>" type="text/css" />
</head>
<body>