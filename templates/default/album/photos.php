<?php $this->display('common/head.php'); ?>
<div class="container">
    <div class="detail-header">
        <div class="detail-header-img">
            <div class="detail-header-imgbg" style="background-image: url(<?php echo thumb($albumInfo['cover_path'],1600,1600,2);?>)"></div>
            <span class="detail-header-imgpop"></span>
        </div>
        <dl class="text-item">
            <dt><span class="title"><?php echo $albumInfo['name'];?></span></dt>
            <dd><a href="<?php echo U('user','space','id='.$authorInfo['id']) ?>"><img src="<?php echo app('user')->getAvatar($authorInfo,'small'); ?>"> <span><?php echo $authorInfo['nickname']; ?></span></a></dd>
        </dl>
        <div class="total-info">
            <div class="total-num">共 <span class="num"><?php echo $totalCount;?></span> 张图片</div>
        </div>
    </div>
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
<?php echo '<script type="text/javascript" src="'.S('comment','js/comment.js').'"></script>';?>
<script type="text/javascript" src="<?php echo ST('js/theater.js'); ?>"></script>
<script>
$('.list-body').css('max-width',$(window).width()-120);
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