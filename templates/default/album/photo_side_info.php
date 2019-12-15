<div class="sidebar-author">
    <?php if(!$is_followed): ?>
    <p class="follow"><a href="<?php echo U('friend','follow','id='.$photoInfo['uid']) ?>" onclick="return opt_one(this,'关注该用户？')">关注</a></p>
    <?php endif; ?>
    <a href="<?php echo U('space','index','id='.$authorInfo['id']) ?>" class="author-head">
        <img src="<?php echo app('user')->getAvatar($authorInfo,'small'); ?>">
    </a>
    <a href="<?php echo U('space','index','id='.$authorInfo['id']) ?>"><?php echo $authorInfo['nickname']; ?></a>
    <p class="followers"><span><?php echo $authorInfo['followers']; ?></span>粉丝</p>
</div>
<div class="sidebar-description">
    <h2><?php echo $albumInfo['name']; ?></h2>
</div>
<div class="sidebar-edit">
    <a href="<?php echo U('album','album_detail','id='.$albumInfo['id']);?>" class="big_button">查看完整作品</a>
</div>