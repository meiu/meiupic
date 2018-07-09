<?php $this->display('common/head.php'); ?>
<div class="container">
    <?php $this->display('album/index_menu.php'); ?>
    <div class="list-body" style="max-width: 1188px;">
         <div class="page-content">
            <ul class="listCont tag-list">
                <?php $this->display('album/tag_list.php'); ?>
            </ul>
            
            <div class="pageset" style="display:none"><?php echo $pagestr; ?></div> 
        </div>
    </div>
    <div class="loadingbar" style="display:none;"><label>努力加载中</label></div>
</div>
<script type="text/javascript" src="<?php echo S('album','js/loadmore.js'); ?>"></script>
<?php $this->display('common/foot.php'); ?>