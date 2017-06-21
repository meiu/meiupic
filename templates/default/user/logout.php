<?php $this->display('common/head.php'); ?>

<div class="wrap">
    <div class="min-inner session-edge">
        <h2 class="medium-title">您已成功退出登录！</h2>
        <p class="title-description">正在跳转 <a href="<?php echo $redirect?$redirect:U('base','index');?>">立刻返回</a> <?php echo $msg;?></p>
    </div>
</div>
<script type="text/javascript">
setTimeout(function(){
    window.location.href="<?php echo $redirect?$redirect:U('base','index');?>";
},1000);
</script>
<?php $this->display('common/foot.php'); ?>