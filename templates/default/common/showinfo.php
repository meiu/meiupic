<?php $this->display('common/head.php'); ?>

<div class="wrap">
    <div class="min-inner session-edge">
        <h2 class="medium-title"><?php echo $message; ?></h2>
        <p class="title-description"><?php if($autoredirect): ?><span id="jump_sec"></span>秒后跳转！<?php endif; ?> <a href="<?php echo $link; ?>">立刻返回</a></p>
    </div>
</div>
<?php if($autoredirect): ?>
<script language="javascript">  
var i=<?php echo $autoredirect; ?>;  
<!--  
function clock(){
    document.getElementById("jump_sec").innerHTML = i;  
    if(i>0)  
        setTimeout("clock();",1000);  
    else
        window.location.href = '<?php echo $link; ?>';
    i--;
}
window.onload = clock;
//-->
</script>
<?php endif; ?>
<?php $this->display('common/foot.php'); ?>