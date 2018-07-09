<div class="user-banner" style="background-image: url(<?php echo app('user')->getBannerBg($uinfo);?>)">
    <div class="banner-info">
        <a href="javascript:void(0);" class="banner-user-avatar">
            <img src="<?php echo app('user')->getAvatar($_G['user'],'large');?>">
        </a>
        <div class="banner-user-info">
            <div>
                <span class="info-name"> <?php echo $uinfo['nickname']; ?> </span>
            </div>
            <div class="desc">
                <?php echo $_G['user']['description']; ?>
            </div>
            <ul>
                <li> 
                    <a href="<?php echo U('friend','friends','id='.$uinfo['id']); ?>"> 关注 <?php echo $uinfo['friends']; ?> </a> 
                </li>
                <li> 
                    <a href="<?php echo U('friend','followers','id='.$uinfo['id']); ?>"> 粉丝 <?php echo $uinfo['followers']; ?> </a> 
                </li>
            </ul>
        </div>
        <?php if($uinfo['id'] == $_G['user']['id']): ?>
        <div class="banner-act">
            <label id="change-banner">
                编辑封面 &gt; 
            </label>
        </div>
        <?php endif; ?>
    </div>
</div>
<script>
    uploadBanner('<?php echo U("space","savebg")?>');
</script>