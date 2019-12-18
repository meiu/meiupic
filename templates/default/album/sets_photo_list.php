<?php foreach ($rows as $key => $value): ?>
<div class="photo_thumbnail">
    <a href="<?php echo U('album','sets_view','set_id='.$set_id.'&id='.$value['id']);?>" title="<?php echo $value['name']?>" class="photo_link">
        <img src="<?php echo thumb($value['path'],600,600,2);?>" alt="<?php echo $setsInfo['name'];?>" width="<?php echo $value['width']?>" height="<?php echo $value['height']?>" />
    </a>
    <?php if($value['uid']==$_G['user']['id']): ?>
    <div class="top-button">
        <span class="ani remove"><a href="<?php echo U('album','sets_removephoto','set_id='.$set_id.'&photo_id='.$value['id']) ?>" onclick="return opt_one(this,'确定从图集移除？');">从图集移除</a></span>
    </div>
    <?php endif; ?>
    <div class="info">
        <div class="ani credits">
            <a class="avatar" target="_blank" href="<?php echo U('space','index','id='.$value['uid']) ?>" style="background-image: url(<?php echo app('user')->getAvatar($value['user'],'small'); ?>);"></a>
            <div class="author-wrap">
                <a target="_blank" href="<?php echo U('space','index','id='.$value['uid']) ?>" class="author"><?php echo $value['user']['nickname']; ?></a>
            </div>
        </div>
    </div>
</div>
<?php endforeach ?>