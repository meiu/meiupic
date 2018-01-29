<?php if(!isAjax()): ?><?php $this->display('my/mini_head.php'); ?>
<style>
    html, body{height: 100%;}
</style>
<div class="viewphoto">
<?php endif; ?>
    <div class="photo-container">
        <a href="javascript:void(0)" class="fullscreen" title="全屏展示"></a>
        <a href="javascript:void(0)" class="close"></a>
        <a class="icon-like" title="喜欢" href="<?php echo $photoInfo['uid']!=$_G['user']['id']?U('album','photo_like','id='.$photoInfo['id']):'javascript:void(0)'; ?>"><?php echo $photoInfo['like_num']; ?></a>
        <div class="photo-view">
        <img class="photo" src="<?php echo thumb($photoInfo['path'],1200,1200,2);?>" alt="<?php echo $photoInfo['name']; ?>">
        </div>
        <a href="<?php echo $prevInfo?U('album','view','id='.$prevInfo['id']):'javascript:void(0)'; ?>" class="btn-prev"></a>
        <a href="<?php echo $nextInfo?U('album','view','id='.$nextInfo['id']):'javascript:void(0)'; ?>" class="btn-next"></a>
    </div>
    <div class="sidebar-region">
        <div class="sidebar-actions">
            <a class="icon-like" title="喜欢" href="<?php echo $photoInfo['uid']!=$_G['user']['id']?U('album','photo_like','id='.$photoInfo['id']):'javascript:void(0)'; ?>"><?php echo $photoInfo['like_num']; ?></a>
            <a class="icon-comment" title="评论"><?php echo $photoInfo['comments_num']; ?></a>
            <a class="icon-read" title="浏览数"><?php echo $photoInfo['hits']; ?></a>
        </div>
        <div class="sidebar-author">
            <a href="#" class="author-head">
                <img src="<?php echo app('user')->getAvatar($authorInfo,'small'); ?>">
            </a>
            <a href="#"><?php echo $authorInfo['nickname']; ?></a>
            <?php if($photoInfo['uid'] == $_G['user']['id'] || $is_followed): ?>
            <p><?php echo $authorInfo['followers']; ?>粉丝</p>
            <?php else: ?>
            <p><a href="<?php echo U('friend','follow','uid='.$photoInfo['uid']) ?>" onlick="return opt_one(this,'关注该用户？')">关注</a></p>
            <?php endif; ?>
        </div>
        <div class="sidebar-description">
            <h2><?php echo $photoInfo['name']; ?></h2>
            <p><?php echo nl2br($photoInfo['description']); ?></p>
            <p>
                <?php if ($photoInfo['tags']): 
                foreach (explode(',',$photoInfo['tags']) as $value): ?>
                <a href="<?php echo U('album','tags','tag='.$value) ?>">#<?php echo  $value; ?></a>
                <?php
                endforeach;
                endif ?>
            </p>
        </div>
        <?php if ($photoInfo['exif']): ?>
        <div class="sidebar-exif">
            <h2><?php echo $photoInfo['exif']['Model']; ?></h2>
            <div class="label small camera_settings">
                <div>
                    <div title="光圈" class="cicon apertureIcon"></div>
                    <div><?php echo $photoInfo['exif']['ApertureFNumber']; ?></div>
                </div>
                <div>
                    <div title="快门速度" class="cicon exposureTimeIcon"></div>
                    <div><?php echo $photoInfo['exif']['ExposureTime']; ?> s</div>
                </div>
                <div>
                    <div title="焦距" class="cicon focalLengthIcon"></div>
                    <div><?php echo $photoInfo['exif']['FocalLength']; ?></div>
                </div>
                <div>
                    <div title="ISO/胶片" class="cicon isoIcon"></div>
                    <div>ISO <?php echo $photoInfo['exif']['ISOSpeedRatings']; ?></div>
                </div>
            </div>
        </div>
        <?php endif ?>
        <div class="license_metadata section">
            <table class="license_metadata_table">
                <tbody>
                    <?php if($photoInfo['cate_id']): ?>
                    <tr><td class="license_metadata_key">分类</td><td class="license_metadata_value"><?php echo isset($cateIndex[$photoInfo['cate_id']])?$cateIndex[$photoInfo['cate_id']]['name']:'未知'; ?></td></tr>
                    <?php endif; ?>
                    <tr><td class="license_metadata_key">上传时间</td><td class="license_metadata_value"><?php echo date('Y-m-d H:i:s',$photoInfo['create_time']); ?></td></tr>
                    <?php if($photoInfo['taken_time']): ?>
                    <tr><td class="license_metadata_key">拍摄时间</td><td class="license_metadata_value"><?php echo date('Y-m-d H:i:s',$photoInfo['taken_time']); ?></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($photoInfo['uid'] == $_G['user']['id']): ?>
        <div class="sidebar-edit">
            <div class="owner_buttons">
                <a class="light_button edit-photo" href="javascript:void(0)" onclick="MuiShow('<?php echo U('album','photo_edit','id='.$photoInfo['id']); ?>','编辑图片',500,500)">编辑</a>
                <a class="light_button edit-photo" href="<?php echo U('album','photo_del','id='.$photoInfo['id']); ?>" onclick="return opt_one(this,'确定删除该图片？')">删除</a>
                <a class="light_button" target="_blank" href="<?php echo U('album','download','id='.$photoInfo['id']); ?>">下载原图</a>
            </div>
        </div>
        <?php endif; ?>
        <div class="sidebar-comment">
            <?php 
                echo x_comment_helper::comment('album_photo',$photoInfo['id'],!isAjax());
            ?>
        </div>
    </div>
<?php if(!isAjax()): ?>
</div>
<script>
$('div.photo-container a.close').click(function(){
    location.href = '<?php echo U('album','my','aid='.$photoInfo['album_id']); ?>';
});

photo_detail_click();

window.addEventListener("popstate", function() {
    var currentState = window.history.state;
    if(currentState){
        window.location.href = currentState.url;
    }
});
</script>
<?php $this->display('my/mini_foot.php'); ?>
<?php endif; ?>