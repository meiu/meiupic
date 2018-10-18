<?php $this->display('common/head.php'); ?>
<?php $this->display('space/inc.banner.php'); ?>
<div class="container">
    <?php $this->display('album/space_menu.php'); ?>
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
</script>
<script type="text/javascript" src="<?php echo S('album','js/loadmore.js'); ?>"></script>
<?php $this->display('common/foot.php'); ?>