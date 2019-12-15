<?php foreach ($rows as $key => $value): ?>
<div class="photo_thumbnail normal">
    <a href="<?php echo U('album','album_detail','id='.$value['id']);?>" class="photo_link">
        <img src="<?php echo thumb($value['cover_path'],600,600,2);?>" alt="<?php echo $value['name']?>" width="<?php echo $value['width']?>" height="<?php echo $value['height']?>" />
    </a>
    <div class="top-button">
        <?php if($value['photos_num']>1): ?>
            <span class="num ani works">（<?php echo $value['photos_num'];?>张）</span>
        <?php endif; ?>
        <span class="hits ani">浏览：<?php echo $value['hits'];?></span>
    </div>
    <div class="info">
        <span class="ani photo-title"><?php echo $value['name'];?></span>
        <a class="ani btns <?php if($value['liked']){echo "liked";}else{echo "like";} ?>" href="<?php if($value['uid']!=$_G['user']['id']): echo U('album','album_like','id='.$value['id']); else: echo 'javascript:void(0)'; endif; ?>"><?php echo $value['like_num']; ?></a>
    </div>
</div>
<?php endforeach ?>