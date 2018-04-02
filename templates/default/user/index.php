<?php $this->display('common/head.php'); ?>
<div class="container">
    <nav class="secondary-nav">
    <ul class="nav-list">
        <li <?php if($_G['uri']['m']=='index' && !getGet('t')): ?>class="active"<?php endif; ?> data-index="0">
            <a target="_self" href="<?=U('user','index')?>">热门</a>
        </li>
        <li <?php if($_G['uri']['m']=='index' && getGet('t')=='new'): ?>class="active"<?php endif; ?> data-index="1">
            <a target="_self" href="<?=U('user','index','t=new')?>">新人</a>
        </li>
    </ul>
</nav>
    <div class="list-body">
        <div class="page-content" id="userlist">
            <div class="listCont">
                <?php $this->display('user/user_list.php'); ?>
            </div>
            <div class="pageset" style="display:none"><?php echo $pagestr; ?></div> 
        </div>
    </div>
    <div class="loadingbar" style="display:none;"><label>努力加载中</label></div>
</div>
<?php echo '<script type="text/javascript" src="'.S('comment','js/comment.js').'"></script>';?>
<script type="text/javascript" src="<?php echo ST('js/theater.js'); ?>"></script>
<script>
$('#userlist').delegate('.follow-button a','click',function(){
    var that = this;
    if(this.href=='javascript:void(0)'){
        return false;
    }
    var param = {};
    if($(this).hasClass('followed')){
        param.a = 'cancel';
    }
    return opt_one(this,false,param,function(){
        if($(that).hasClass('follow')){
            $(that).removeClass('follow').addClass('followed').html('取消关注');
        }else{
            $(that).removeClass('followed').addClass('follow').html('关注');
        }
    });
});
theater.init('div.container');
</script>
<script type="text/javascript" src="<?php echo S('album','js/loadmore.js'); ?>"></script>
<?php $this->display('my/foot.php'); ?>