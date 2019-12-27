<?php $this->display('common/head.php'); ?>
<div class="container">
    <?php $this->display('album/index_menu.php'); ?>
    <div class="list-body">
        <div class="content-album">
            <?php if($rows):?>
            <div class="listCont">
                <?php $this->display('album/sets_list.php'); ?>
            </div>
            <?php else: ?>
            <div class="no-data">暂无内容</div>
            <?php endif; ?>
            <div class="pageset" style="display:none"><?php echo $pagestr; ?></div> 
        </div>
    </div>
    <div class="loadingbar" style="display:none;"><label>努力加载中</label></div>
</div>

<script type="text/javascript" src="<?php echo S('album','js/loadmore.js'); ?>"></script>
<?php $this->display('common/foot.php'); ?>