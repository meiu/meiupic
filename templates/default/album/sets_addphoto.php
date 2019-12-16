<?php $this->display('space/mini_head.php'); ?>
<form id="submit_form" action="" class="ajaxform" method="post">
    <div style="padding:10px 0;">
        <a class="normal-btn" target="_blank" href="<?php echo U('album','space_sets','id='.$_G['user']['id'].'&add=1')?>">新增图集</a>
    </div>
    <div class="innercol grid">
        <div class="gallary_wrap">
        <?php foreach ($rows as $key => $value): ?>
            <div class="gallary_item album mini" style="background-image: url('<?php if($value['cover_path']){echo thumb($value['cover_path'],180,180,2);}else{echo S('base','images/nophoto.gif');}?>')">
                <em><?php echo $value['photos_num'];?> 张</em>
                <div class="title">
                    <?php echo $value['name'];?>
                </div>
                <div class="check"></div>
                <input class="id_sel selitem" name="set_id" type="radio" value="<?php echo $value['id']; ?>" />
            </div>
            <?php endforeach ?>
            <div class="clear"></div>
        </div>
    </div>
    <?php echo $pagestr; ?>
    <input type="hidden" name="photo_id" value="<?php echo $search['photo_id'];?>" />
    <input type="submit" id="dosubmit" style="display:none;" value="保存修改" />
</form>
<script>
    $('.gallary_item').click(function(){
        $(this).addClass('checked').siblings().removeClass('checked');
        $(this).find('input[type="radio"]').prop('checked',true);
    });
</script>
<?php $this->display('space/mini_foot.php'); ?>