<div class="filelist">
    <ul>
        <?php foreach ($uploaded_files as $key => $value): ?>
         <li><a href="javascript:void(0)" onclick="file_pick(this)" title="<?php echo $value['name'];?>" id="fno_<?php echo $value['id'];?>">
            <div class="icon"></div><img <?php if($value['filetype']=='image' || $value['filetype']=='watermark'): ?>
            src="<?php echo D($value['path']);?>" width="80"
            <?php else: ?>
            src="<?php echo S('base','images/mime/'.$value['ext'].'.png');?>" height="60"
            <?php endif; ?>
             fileid="<?php echo $value['id'];?>" path="<?php echo $value['path'];?>" /></a></li>
            <?php if($key<$num): ?>
            <script type="text/javascript"> file_pick('#fno_<?php echo $value['id'];?>'); </script>
            <?php endif; ?>
        <?php endforeach ?>
    </ul>
</div>