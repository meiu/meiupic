<?php foreach ($rows as $key => $value): ?>
<li class="user-row">
    <a class="link_wrap" href="<?php echo U('user','space','id='.urlencode($value['id'])); ?>" title="<?php echo $value['nickname']; ?>" target="_blank">
    </a>
    <div class="top" style="background-image: url(<?php echo app('user')->getBannerBg($value,'small');?>)">
        <img class="avatar" alt="<?php echo $value['nickname']; ?>" src="<?php echo app('user')->getAvatar($value,'small');?>">
    </div>
    <div class="bottom">
        <span class="name"><?php echo $value['nickname']; ?></span>
        <span class="followers">粉丝&nbsp;<?php echo $value['followers'];?></span>
        <div class="follow-button">
            <a class="button  <?php if($value['followed']){echo "followed";}else{echo "follow";} ?>" href="<?php if($_G['user'] && $_G['user']['id']==$value['id']){ echo 'javascript:void(0)';}else{echo U('friend','follow','id='.$value['id']);} ?>">
            <?php if($value['followed']){echo "取消关注";}else{echo "关注";} ?>
            </a>
        </div>
    </div>
</li>
<?php endforeach ?>