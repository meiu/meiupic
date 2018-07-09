<div class="user-banner" style="background-image: url(<?php echo app('user')->getBannerBg($_G['user']);?>)">
    <div class="banner-info">
        <a href="javascript:void(0);" class="banner-user-avatar">
            <img src="<?php echo app('user')->getAvatar($_G['user'],'large');?>">
        </a>
        <div class="banner-user-info">
            <div>
                <span class="info-name"> <?php echo $_G['user']['nickname']; ?> </span>
            </div>
            <div class="desc">
                <?php echo $_G['user']['description']; ?>
            </div>
            <ul>
                <li> 
                    <a href="<?php echo U('friend','my_friends'); ?>"> 关注 <?php echo $_G['user']['friends']; ?> </a> 
                </li>
                <li> 
                    <a href="<?php echo U('friend','my_followers'); ?>"> 粉丝 <?php echo $_G['user']['followers']; ?> </a> 
                </li>
            </ul>
        </div>
        <div class="banner-act">
            <label id="change-banner">
                编辑封面 &gt; 
            </label>
        </div>
    </div>
</div>
<script>
    uploadBanner('<?php echo U("my","savebg")?>');
</script>