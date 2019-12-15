<?php if(!isAjax()): ?><?php $this->display('space/mini_head.php'); ?>
<style>
    html, body{height: 100%;}
</style>
<div class="viewphoto">
<?php endif; ?>
    <div class="photo-container">
        <div class="toolbar">
            <a href="javascript:void(0)" class="fullscreen" title="全屏展示"></a>
            <div class="right">
                <a href="<?php echo U('album','sets_photos','id='.$set_id); ?>" class="close"></a>
                <a class="download" href="javascript:void(0)" data-url="<?php echo U('album','download'); ?>" title="下载原图"></a>
                <a href="javascript:void(0)" data-url="<?php echo U('album','sets_addphoto'); ?>" title="添加至图集" class="addto"></a>
            </div>
            <div class="caption">
                <span>0</span> / <?php echo count($photos); ?>
            </div>
        </div>

        <div class="photo-view">
        <img class="photo" src="<?php echo thumb($photoInfo['path'],1600,1600,2);?>" alt="<?php echo $photoInfo['name']; ?>">
        </div>
        <a href="<?php echo $prevInfo?U('album','view','id='.$prevInfo['id']):'javascript:void(0)'; ?>" class="btn-prev"></a>
        <a href="<?php echo $nextInfo?U('album','view','id='.$nextInfo['id']):'javascript:void(0)'; ?>" class="btn-next"></a>
    </div>
    <div class="photo-thumbs">
        <ul>
        <?php foreach ($photos as $key => $value):?>
            <li <?php if($value['id']==$photoInfo['id']): ?>class="current"<?php endif;?> data-imgid="<?php echo $value['id'];?>" data-bigsrc="<?php echo thumb($value['path'],1600,1600,2); ?>" data-info-url="<?php echo U('album','photo_side_info','id='.$value['id']);?>">
                <img src="<?php echo thumb($value['path'],180,180,2); ?>" alt="<?php echo $value['name']; ?>">
            </li>
        <?php endforeach; ?>
        </ul>
    </div>
    <div class="sidebar-region" id="set-side-info">
    </div>
<?php if(!isAjax()): ?>
</div>
<script>

bind_keys();
photo_detail_click();

window.addEventListener("popstate", function() {
    var currentState = window.history.state;
    if(currentState){
        window.location.href = currentState.url;
    }
});
</script>
<?php $this->display('space/mini_foot.php'); ?>
<?php endif; ?>