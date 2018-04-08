<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo (isset($admin_title)?$admin_title.' - ':'').'MeiuPic后台管理';?></title>
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
</head>
<body>
<?php if ( !isset($ismini) || !$ismini): ?>
    <?php doAction('adm_main_top'); ?>
    <div id="head">
        <div id="logo">Meiu</div>
        <a class="dropmenu">&nbsp;</a>
        <ul id="menu">
            <?php
            $admin_menu = getSetting('admin_menu',true);
            $tmp = explode('_', $current_menu);
            $current_menu_app = array_shift($tmp);
            foreach ($admin_menu as $key => $value):
                if(!$value['enable']) continue;
            ?>
                <li 
                <?php
                if(isset($value['cid']))://内容分类
                    if($current_menu == $value['app'].'_'.$value['mod'] && getGet('cid')==$value['cid']):
                ?>class="current"<?php
                    endif;
                elseif(isset($value['mod'])):
                    if($current_menu == $value['app'].'_'.$value['mod']):
                ?>class="current"<?php 
                    endif;
                elseif($current_menu_app == $value['app']):
                ?>class="current"<?php 
                endif;
                ?>><a href="<?php echo U($value['app'],isset($value['mod'])?$value['mod']:'index',isset($value['cid'])?'cid='.$value['cid']:'');?>"><span><?php echo $value['name'];?></span></a></li>
            <?php 
            endforeach;
            ?>
            
            <?php doAction('adm_menu'); ?>
            <li class="editmenu"><a href="<?php echo U('base','setting','a=menu');?>" title="编辑菜单">编辑</a></li>
            <li class="right"><a href="<?php echo U('user','login','a=logout');?>"><span>退出</span></a></li>
            <li class="right"><a href="/" target="_blank"><span>浏览网站</span></a></li>
            <li class="right<?php if($current_menu == 'base_apps'):?> current<?php endif; ?>"><a href="<?php echo U('base','apps');?>"><span>应用</span></a></li>
        </ul>
    </div>
<?php else: ?>
<style>
    html{background: #fff;}
</style>
<?php endif ?>

<div class="body">
    <?php if(isset($submenu)): ?>
    <div id="js-post-side" class="side">
        <div class="side-con">
            <div class="nav-aside nav-aside-large">
                <ul>
                    <?php 
                    $menu_i=0;
                    foreach ($submenu as $key => $value): 
                    if(is_array($value)):
                    ?>
                        <li class="<?php if($menu_i==0){ echo 'first';} if(in_array($act ,$value)):?> cur<?php endif; ?>"><a href="<?php echo U($_G['uri']['app'],$_G['uri']['m'],'a='.$value[0]);?>"><?php echo $key;?></a></li>
                    <?php else: ?>
                        <li class="<?php if($menu_i==0){ echo 'first';} if($_G['uri']['m'] == $value):?> cur<?php endif; ?>"><a href="<?php echo U($_G['uri']['app'],$value);?>"><?php echo $key;?></a></li>
                    <?php 
                    endif;
                    $menu_i++;
                    endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="side-bg"></div>
    <?php endif; ?>
    <div class="<?php if(isset($submenu)): ?>main<?php else:?>wmain<?php endif; ?>">
        <?php if ( !isset($ismini) || !$ismini): ?>
        <div class="main-con">
            <?php echo $_bodycontent; ?>
        </div>
        <?php else: ?>
        <div class="main-wcon">
            <?php echo $_bodycontent; ?>
        </div>
        <?php endif;?>
        <!--<div id="foot">processed in <?php echo G('begin','end',4); ?>s</div>-->
    </div>
</div>
<?php if ( !isset($ismini) || !$ismini): ?>
<?php doAction('adm_main_foot'); ?>
<?php endif; ?>
</body>
</html>