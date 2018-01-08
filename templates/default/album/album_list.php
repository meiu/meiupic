<?php foreach ($rows as $key => $value): ?>
<div class="photo_thumbnail">
    <a href="<?php echo U('album','my','aid='.$value['id']);?>" class="album_link"></a>
    <div class="top" style="background-image:url('<?php echo thumb($value['cover_path'],600,600,2);?>');">
    </div>
    <div class="bottom">
        <a href="<?php echo U('album','my','aid='.$value['id']);?>" class="name"><?php echo $value['name'];?></a>
        <?php if($value['priv_type']==1):?><div class="gallery_private_icon"></div><?php endif; ?>
        <span class="desc"><?php echo $value['photos_num'];?>张照片</span>
    </div>
</div>
<?php endforeach ?>