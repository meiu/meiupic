<?php foreach ($rows as $key => $value): ?>
<div class="photo_thumbnail">
    <a href="<?php echo U('album','view','id='.$value['id']);?>" title="<?php echo $value['name']?>" class="photo_link">
        <img src="<?php echo thumb($value['path'],600,600,2);?>" width="<?php echo $value['width']?>" height="<?php echo $value['height']?>" />
    </a>
    <div class="top-button">
        <a href="javascript:void(0)" class="btns privtype <?php if($value['priv_type']==1){echo 'lock';}else{echo 'open';} ?>"></a>
        <?php if(!empty($albumInfo) && $_G['user']['id']==$uid): ?>
        <a href="<?php echo U('album','set_cover','id='.$value['id']); ?>" class="btns setcover" title="设为封面" onclick="return opt_one(this,'确定将此图片设置为相册封面？')"></a>
        <?php endif; ?>
    </div>
    <div class="info">
        <?php if($_G['user']['id']==$uid): ?>
        <a href="javascript:void(0)" class="btns addto" onclick="MuiShow('<?php echo U('album','photo_move','id='.$value['id']); ?>','移动图片至相册',500,500)"></a>
        <?php endif; ?>
        <span class="photo-title"><?php echo $value['name'];?></span>
    </div>
</div>
<?php endforeach ?>