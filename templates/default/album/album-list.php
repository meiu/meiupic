<?php foreach ($rows as $key => $value): ?>
<div class="photo_thumbnail">
    <a href="javascript:void(0)" class="album_link"></a>
    <div class="top" style="background-image:url('<?php echo thumb($value['cover_path'],600,600,2);?>');">
    </div>
    <div class="bottom">
        <a href="javascript:void(0)" class="name"><?php echo $value['name'];?></a>
        <span class="desc"><?php echo $value['photos_num'];?>张照片</span>
    </div>
</div>
<?php endforeach ?>