<?php $this->display('common/head.php'); ?>
<div class="container">
    <div class="detail-header">
        <div class="detail-header-img">
            <div class="detail-header-imgbg" style="<?php if($setsInfo['cover_path']):?>background-image: url(<?php echo thumb($setsInfo['cover_path'],1600,1600,2);?>)<?php else:?>background:#eee;<?php endif;?>"></div>
            <span class="detail-header-imgpop"></span>
        </div>
        <dl class="text-item">
            <dt><span class="title"><?php echo $setsInfo['name'];?></span></dt>
            <dd><a href="<?php echo U('space','index','id='.$authorInfo['id']) ?>"><img src="<?php echo app('user')->getAvatar($authorInfo,'small'); ?>"> <span><?php echo $authorInfo['nickname']; ?></span></a></dd>
            <dt class="detail-description">
            <?php echo $setsInfo['description'];?>
            </dt>
        </dl>
        
        <div class="total-info">
            <div class="total-num">共 <span class="num"><?php echo $totalCount;?></span> 张图片</div>
        </div>
        <div class="back-link">
        </div>
    </div>
    <div class="list-body">
        <div class="content-gallery sets-gallery">
            <div id="grid-gallery" class="listCont" style="opacity:0;">
                <?php $this->display('album/sets_photo_list.php'); ?>
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
$("#grid-gallery").justifiedGallery({'rowHeight':260,'margins':10,'captions':false}).on('jg.complete', function (e) {
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
theater.init('div.container','photo');
</script>
<script type="text/javascript" src="<?php echo S('album','js/loadmore.js'); ?>"></script>
<?php $this->display('common/foot.php'); ?>