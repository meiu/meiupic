<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo (isset($admin_title)?$admin_title.' - ':'');?></title>
    <link rel="stylesheet" href="<?php echo S('base','admin/css/admin.css');?>" />
    <script src="<?php echo S('base','js/jquery.min.js');?>"></script>
    <script src="<?php echo S('base','js/jquery.form.js');?>"></script>
    <script src="<?php echo S('base','admin/js/common.js');?>"></script>
    <script src="<?php echo S('base','dialog/jquery.artDialog.js?skin=modernblue');?>"></script>
    <script src="<?php echo S('base','dialog/plugins/iframeTools.js');?>"></script>
    <script src="<?php echo S('base','js/jquery.dragsort.min.js');?>"></script>
    <script src="<?php echo S('base','chosen/chosen.jquery.min.js');?>"></script>
    <link rel="stylesheet" href="<?php echo S('base','chosen/chosen.min.css');?>" />
    <script>
    var UPFILE_PRE = "<?php echo C('storage.setting.url_pre'); ?>";
    var ADMIN_BASE_URL = "<?php echo C('admin_base'); ?>";
    </script>
    <?php doAction('adm_head'); ?>
    <style>
        html{background: #fff;}
        body{margin:0;}
    </style>
</head>
<body>
<div class="body">
 <?php echo $_bodycontent; ?>
</div>
</body>
</html>