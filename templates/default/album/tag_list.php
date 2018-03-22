<?php foreach ($rows as $key => $value): ?>
<li class="tag-square-base">
    <a href="<?php echo U('album','search','tag='.urlencode($value['name'])); ?>" title="<?php echo $value['name']; ?>" target="_blank">
        <img alt="<?php echo $value['name']; ?>" width="173" src="<?php echo thumb($value['image'],180,180);?>">
        <span class="tag-title"><?php echo $value['name']; ?></span>
    </a>
</li>
<?php endforeach ?>