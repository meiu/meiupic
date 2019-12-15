<?php foreach ($rows as $key => $value): ?>
<div class="photo_thumbnail">
    <?php if($value['uid']==$_G['user']['id']): ?>
    <div class="options">
        <a href="javascript:void(0)" onclick="MuiShow('<?php echo U('album','sets_edit','id='.$value['id']);?>','修改图集',500,350);">修改</a>
        <a href="javascript:void(0)" onclick="return opt_one(this,'是否删除此图集？');" data-url="<?php echo U('album','sets_del','id='.$value['id']);?>">删除</a>
    </div>
    <?php endif; ?>

    <a href="<?php echo U('album','sets_photos','id='.$value['id']);?>" class="album_link"></a>
    <div class="top" style="<?php if($value['cover_path']):?>background-image:url('<?php echo thumb($value['cover_path'],600,600,2);?>');<?php else:?>background:#eee;<?php endif; ?>">
    </div>
    <div class="bottom">
        <a href="<?php echo U('album','sets_photos','id='.$value['id']);?>" class="name"><?php echo $value['name'];?></a>
        <?php if($value['priv_type']==1):?><div class="gallery_private_icon"></div><?php endif; ?>
        <span class="desc"><?php echo $value['photos_num'];?>张照片</span>
    </div>
</div>
<?php endforeach ?>