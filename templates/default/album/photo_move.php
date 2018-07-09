<?php $this->display('space/mini_head.php'); ?>
<form class="mini-search" action="" method="post">
    <div class="line-wrap">
        相册名：
        <input type="text"  class="nomal-input" name="name" value="<?php echo $search['name'];?>" />
        <input type="submit" value="搜索" class="normal-btn" />
    </div>
</form>
<form id="submit_form" action="<?php echo U('album','do_photo_move');?>" class="ajaxform" method="post">
    <div class="innercol grid">
        <div class="gallary_wrap">
        <?php foreach ($rows as $key => $value): ?>
            <div class="gallary_item album mini" style="background-image: url('<?php if($value['cover_path']){echo thumb($value['cover_path'],180,180,2);}else{echo S('base','images/nophoto.gif');}?>')">
                <em><?php echo $value['photos_num'];?> 张</em>
                <div class="title">
                    <?php echo $value['name'];?>
                </div>
                <div class="check"></div>
                <input class="id_sel selitem" name="album_id" type="radio" value="<?php echo $value['id']; ?>" />
            </div>
            <?php endforeach ?>
            <div class="clear"></div>
        </div>
    </div>
    <?php echo $pagestr; ?>
    <input type="hidden" name="id" value="<?php echo $search['id'];?>" />
    <input type="submit" id="dosubmit" style="display:none;" value="保存修改" />
</form>
<script>
    $('.gallary_item').click(function(){
        $(this).addClass('checked').siblings().removeClass('checked');
        $(this).find('input[type="radio"]').prop('checked',true);
    });
</script>
<?php $this->display('space/mini_foot.php'); ?>