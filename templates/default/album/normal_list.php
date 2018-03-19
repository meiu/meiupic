<?php foreach ($rows as $key => $value): ?>
<div class="photo_thumbnail normal">
    <a href="<?php echo U('album','view','id='.$value['id']);?>" title="<?=$value['name']?>" class="photo_link">
        <img src="<?php echo thumb($value['path'],600,600,2);?>" width="<?=$value['width']?>" height="<?=$value['height']?>" />
    </a>
    <div class="top-button">
        <span class="hits">浏览：<?php echo $value['hits'];?></span>
    </div>
    <div class="info">
        <a class="btns <?php if($value['liked']){echo "liked";}else{echo "like";} ?>" href="<?php echo U('album','photo_like','id='.$value['id']); ?>"><?php echo $value['like_num']; ?></a>
        <div class="credits">
            <a class="avatar" target="_blank" href="<?php echo U('user','space','id='.$value['uid']) ?>" style="background-image: url(<?php echo app('user')->getAvatar($value['user'],'small'); ?>);"></a>
            <div class="author-wrap">
                <a target="_blank" href="<?php echo U('user','space','id='.$value['uid']) ?>" class="author"><?php echo $value['user']['nickname']; ?></a>
            </div>
        </div>
    </div>
</div>
<?php endforeach ?>