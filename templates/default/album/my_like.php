<?php $this->display('my/head.php'); ?>
<div class="container">
    <?php $this->display('album/my_menu.php'); ?>
    <?php if(isset($albumInfo) && $albumInfo): ?>
    <div class="head-title">
        <a class="delete-album right" href="<?php echo U('album','album_del','id='.$albumInfo['id']); ?>" onclick="return opt_one(this,'确定删除该相册？')">删除相册</a>
        <a href="javascript:void(0)" onclick="MuiShow('<?php echo U('album','album_edit','id='.$albumInfo['id']); ?>','编辑相册',500,500)" class="normal-btn right">编辑相册</a>
        <label id="album-title"><?php echo $albumInfo['name'];?></label> <span class="album-account "><?php echo $albumInfo['photos_num'];?>张</span>  
    </div>
    <?php endif ?>
    <div class="list-body">
        <div class="content-gallery">
            <div id="grid-gallery" class="listCont" style="opacity:0;">
                <?php $this->display('album/normal_list.php'); ?>
            </div>
            <div class="pageset" style="display:none"><?php echo $pagestr; ?></div> 
        </div>
    </div>
    <div class="loadingbar" style="display:none;"><label>努力加载中</label></div>
</div>
<script type="text/javascript" src="<?php echo ST('js/theater.js'); ?>"></script>
<script>
$("#grid-gallery").justifiedGallery({'rowHeight':300,'margins':10}).on('jg.complete', function (e) {
    $('#grid-gallery').css('opacity',1);
});
$('#grid-gallery').delegate('a.liked,a.like','click',function(){
    var that = this;
    if(this.href=='javascript:void(0)'){
        return false;
    }
    var param = {};
    if($(this).hasClass('liked')){
        param.a = 'cancel';
    }
    return opt_one(this,false,param,function(){
        if($(that).hasClass('like')){
            $(that).text(parseInt($(that).text())+1);
            $(that).removeClass('like').addClass('liked');
        }else{
            $(that).text(parseInt($(that).text())-1);
            $(that).removeClass('liked').addClass('like');
        }
    });
});
theater.init('div.container');
</script>
<script type="text/javascript" src="<?php echo S('album','js/loadmore.js'); ?>"></script>
<?php $this->display('my/foot.php'); ?>