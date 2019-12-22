<?php $this->display('common/head.php'); ?>
<?php $this->display('space/inc.banner.php'); ?>

<div class="container">
    <?php $this->display('album/space_menu.php'); ?>
    <div class="list-body">
        <div class="content-gallery">
            <?php if($rows): ?>
            <div id="grid-gallery" class="listCont" style="opacity:0;">
                <?php $this->display('album/space_normal_list.php'); ?>
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

theater.init('div.container');
</script>
<script type="text/javascript" src="<?php echo S('album','js/loadmore.js'); ?>"></script>
<?php $this->display('common/foot.php'); ?>