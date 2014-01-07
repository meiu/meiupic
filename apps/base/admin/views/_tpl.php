<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo (isset($admin_title)?$admin_title.' - ':'').'MeiuPic后台管理';?></title>
    <link rel="stylesheet" href="<?php echo S('base','admin/css/admin.css');?>" />
    <script src="<?php echo S('base','js/jquery.min.js');?>"></script>
    <script src="<?php echo S('base','js/jquery.form.js');?>"></script>
    <script src="<?php echo S('base','admin/js/common.js');?>"></script>
    <script src="<?php echo S('base','dialog/jquery.artDialog.js?skin=default');?>"></script>
    <script src="<?php echo S('base','dialog/plugins/iframeTools.js');?>"></script>
    <script src="<?php echo S('base','js/jquery.dragsort.min.js');?>"></script>
    <script>
    var UPFILE_PRE = "<?php echo C('upload.url_pre'); ?>";
    </script>
</head>
<body>
<?php if ( !isset($ismini) || !$ismini): ?>
    <div id="head">
        <div id="logo">MeiuCms</div>
        <ul id="menu">
            <?php
            $admin_menu = getSetting('admin_menu',true);
            $tmp = explode('_', $current_menu);
            $current_menu_app = array_shift($tmp);
            foreach ($admin_menu as $key => $value):
                if(!$value['enable']) continue;
            ?>
                <li <?php
                if(isset($value['mod'])):
                    if($current_menu == $value['app'].'_'.$value['mod']):
                ?>class="current"<?php 
                    endif;
                elseif($current_menu_app == $value['app']):
                ?>class="current"<?php 
                endif;
                ?>><a href="<?php echo U($value['app'],isset($value['mod'])?$value['mod']:'index');?>"><span><?php echo $value['name'];?></span></a></li>
            <?php 
            endforeach;
            ?>
        </ul>
        <a href="<?php echo U('base','setting','a=menu');?>" class="editmenu" title="编辑菜单">编辑</a>
        <ul id="rightmenu">
            <li <?php if($current_menu == 'base_apps'):?>class="current"<?php endif; ?>><a href="<?php echo U('base','apps');?>"><span>应用</span></a></li>
            <li><a href="/" target="_blank"><span>浏览网站</span></a></li>
            <li><a href="<?php echo U('user','login','a=logout');?>"><span>退出</span></a></li>
        </ul>
    </div>
    <?php if(isset($submenu)): ?>
    <div id="submenu">
        <ul>
            <?php foreach ($submenu as $key => $value): 
            if(is_array($value)):
            ?>
                <li <?php if(in_array($act ,$value)):?>class="current"<?php endif; ?>><a href="<?php echo U($_G['uri']['app'],$_G['uri']['m'],'a='.$value[0]);?>"><span><?php echo $key;?></span></a></li>
            <?php else: ?>
                <li <?php if($_G['uri']['m'] == $value):?>class="current"<?php endif; ?>><a href="<?php echo U($_G['uri']['app'],$value);?>"><span><?php echo $key;?></span></a></li>
            <?php 
            endif;
            endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
<?php endif ?>

<div class="body">
<?php echo $_bodycontent; ?>
</div>
<?php if ( !isset($ismini) || !$ismini): ?>
<div id="footer" > Powered by <a href="http://www.meiu.cn" target="_blank">MeiuPic 3.0</a> , processed in <?php echo G('begin','end',4); ?>s</div>
<?php endif; ?>
</body>
</html>