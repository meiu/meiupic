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
        <a class="ani btns <?php if($value['liked']){echo "liked";}else{echo "like";} ?>" href="<?php if($value['uid']!=$_G['user']['id']): echo U('album','album_like','id='.$value['id']); else: echo 'javascript:void(0)'; endif; ?>"><?php echo $value['like_num']; ?></a>
        <div class="ani credits">
            <a class="avatar" target="_blank" href="<?php echo U('space','index','id='.$value['uid']) ?>" style="background-image: url(<?php echo app('user')->getAvatar($value['user'],'small'); ?>);"></a>
            <div class="author-wrap">
                <a target="_blank" href="<?php echo U('space','index','id='.$value['uid']) ?>" class="author"><?php echo $value['user']['nickname']; ?></a>
            </div>
        </div>
    </div>
</div>
<?php endforeach ?>