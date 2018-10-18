<?php $this->display('common/head.php'); ?>
<?php $this->display('space/inc.banner.php'); ?>

<div class="container">
    <?php $this->display('album/space_menu.php'); ?>
    <div class="list-body">
        <div class="content-album">
            <div class="listCont">
                <?php $this->display('album/space_album_list.php'); ?>
            </div>
            <div class="pageset" style="display:none"><?php echo $pagestr; ?></div> 
        </div>
    </div>
    <div class="loadingbar" style="display:none;"><label>努力加载中</label></div>
</div>

<script type="text/javascript" src="<?php echo S('album','js/loadmore.js'); ?>"></script>
<?php $this->display('common/foot.php'); ?>