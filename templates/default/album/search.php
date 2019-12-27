<?php $this->display('common/head.php'); ?>
<div class="container">
    <nav class="secondary-nav">
    <?php if(!empty($urlparam['tag'])): ?>
    <div class="caption-header">
        <h1 class="caption-name"><?php echo $urlparam['tag']; ?></h1>
        <span class="caption-posts"><?php echo $totalCount; ?>个作品</span>
    </div>
    <?php endif; ?>
    <ul class="nav-list">
        <li <?php if(!getGet('t')): ?>class="active"<?php endif; ?> data-index="0">
            <a target="_self" href="<?php echo U('album','search',$urlparam)?>">热门</a>
        </li>
        <li <?php if(getGet('t')=='new'): ?>class="active"<?php endif; ?> data-index="1">
            <a target="_self" href="<?php echo U('album','search',array_merge($urlparam,array('t'=>'new')))?>">新作</a>
        </li>
    </ul>
    </nav>
    <div class="list-body">
        <div class="content-gallery">
            <?php if($rows):?>
            <div id="grid-gallery" class="listCont" style="opacity:0;">
                <?php $this->display('album/normal_list.php'); ?>
            </div>
            <?php else: ?>
            <div class="no-data">暂无内容</div>
            <?php endif; ?>
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
theater.init('div.container');
</script>
<script type="text/javascript" src="<?php echo S('album','js/loadmore.js'); ?>"></script>
<?php $this->display('common/foot.php'); ?>