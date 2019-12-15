<?php foreach ($rows as $key => $value): ?>
<div class="photo_thumbnail">
    <a href="<?php echo U('album','sets_photos','id='.$value['id']);?>" class="album_link"></a>
    <div class="top" style="<?php if($value['cover_path']):?>background-image:url('<?php echo thumb($value['cover_path'],600,600,2);?>');<?php else:?>background:#eee;<?php endif; ?>">
    </div>

    <div class="bottom">
        <a href="<?php echo U('album','sets_photos','id='.$value['id']);?>" class="name"><?php echo $value['name'];?></a>
        <?php if($value['priv_type']==1):?><div class="gallery_private_icon"></div><?php endif; ?>
        <span class="desc"><?php echo $value['photos_num'];?>张照片</span>

        <div class="credits">
            <a class="avatar" target="_blank" href="<?php echo U('space','index','id='.$value['uid']) ?>" style="background-image: url(<?php echo app('user')->getAvatar($value['user'],'small'); ?>);"></a>
            <div class="author-wrap">
                <a target="_blank" href="<?php echo U('space','index','id='.$value['uid']) ?>" class="author"><?php echo $value['user']['nickname']; ?></a>
            </div>
        </div>
    </div>
</div>
<?php endforeach ?>