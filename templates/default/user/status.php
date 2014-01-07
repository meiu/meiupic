<?php if($_G['user']): //已登录 ?>
您好，<?php echo $_G['user']['nickname'];?> <?php if($_G['user']['level'] == 99):?><a target="_blank" href="<?php echo U('base','index','','','admin');?>">进入后台</a><?php endif; ?> <a href="<?php echo U('user','login','a=logout');?>">退出</a>
<?php else: ?>
<a href="<?php echo U('user','login');?>">登录</a> <a href="<?php echo U('user','register');?>">注册</a>
<?php endif; ?>