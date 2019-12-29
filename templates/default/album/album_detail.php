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
                <a href="javascript:void(0)" class="close"></a>
                <a class="download" href="javascript:void(0)" data-url="<?php echo U('album','download'); ?>" title="下载原图"></a>
                <a href="javascript:void(0)" data-url="<?php echo U('album','sets_addphoto'); ?>" title="添加至图集" class="addto"></a>
            </div>
            
            <div class="caption">
                <span>0</span> / <?php echo count($photos); ?>
            </div>
        </div>
        

        <a <?php if($albumInfo['liked']): ?>class="icon-liked" title="取消喜欢"<?php else: ?>class="icon-like" title="喜欢"<?php endif; ?> href="<?php echo $albumInfo['uid']!=$_G['user']['id']?U('album','album_like','id='.$albumInfo['id']):'javascript:void(0)'; ?>"><?php echo $albumInfo['like_num']; ?></a>
        <div class="photo-view" data-imgid="<?php echo $photoInfo['id']; ?>">
            <img class="photo" src="<?php echo thumb($photoInfo['path'],1600,1600,2);?>" alt="<?php echo $photoInfo['name']; ?>">
        </div>
        <a href="javascript:void(0)" class="btn-prev"></a>
        <a href="javascript:void(0)" class="btn-next"></a>
    </div>
    <div class="photo-thumbs">
        <ul>
        <?php foreach ($photos as $key => $value):?>
            <li <?php if($value['id']==$photoInfo['id']): ?>class="current"<?php endif;?> data-imgid="<?php echo $value['id'];?>" data-bigsrc="<?php echo thumb($value['path'],1600,1600,2); ?>" data-exif="<?php echo $value['exif']?htmlentities(json_encode($value['exif'])):''; ?>">
                <img src="<?php echo thumb($value['path'],256,256,2); ?>" alt="<?php echo $value['name']; ?>">
            </li>
        <?php endforeach; ?>
        <?php if($nextInfo): ?>
            <li class="next" data-workurl="<?php echo U('album','album_detail','id='.$nextInfo['id']);?>">
                下一组
            </li>
        <?php endif; ?>
        </ul>
    </div>
    <div class="sidebar-region">
        <div class="sidebar-actions">
            <a <?php if($albumInfo['liked']): ?>class="icon-liked" title="取消喜欢"<?php else: ?>class="icon-like" title="喜欢"<?php endif; ?> href="<?php echo $albumInfo['uid']!=$_G['user']['id']?U('album','album_like','id='.$albumInfo['id']):'javascript:void(0)'; ?>"><?php echo $albumInfo['like_num']; ?></a>

            <a class="icon-comment" title="评论"><?php echo $albumInfo['comments_num']; ?></a>
            <a class="icon-read" title="浏览数"><?php echo $albumInfo['hits']; ?></a>
        </div>
        <div class="sidebar-author">
            <?php if(!$is_followed): ?>
            <p class="follow"><a href="<?php echo U('friend','follow','id='.$authorInfo['id']) ?>" onclick="return opt_one(this,'关注该用户？')">关注</a></p>
            <?php endif; ?>
            <a href="<?php echo U('space','index','id='.$authorInfo['id']) ?>" class="author-head">
                <img src="<?php echo app('user')->getAvatar($authorInfo,'small'); ?>">
            </a>
            <a href="<?php echo U('space','index','id='.$authorInfo['id']) ?>"><?php echo $authorInfo['nickname']; ?></a>
            <p class="followers"><span><?php echo $authorInfo['followers']; ?></span>粉丝</p>
        </div>
        <div class="sidebar-description">
            <h2><?php echo $albumInfo['name']; ?></h2>
            <p><?php echo nl2br($albumInfo['description']); ?></p>
            <p>
                <?php if ($albumInfo['tags']): 
                foreach (explode(',',$albumInfo['tags']) as $value): ?>
                <a href="<?php echo U('album','search','tag='.$value) ?>">#<?php echo  $value; ?></a>
                <?php
                endforeach;
                endif ?>
            </p>
        </div>
        <div class="sidebar-exif" style="display: none;">
        </div>
        <?php 
        //编辑操作选项
        if($albumInfo['uid'] == $_G['user']['id'] || $_G['user']['level']>=80):
        ?>
        <div class="sidebar-edit">
            <div class="owner_buttons">
                <?php if($albumInfo['uid'] == $_G['user']['id']): ?>
                    <a class="light_button edit-photo" href="<?php echo U('album','post','id='.$albumInfo['id']); ?>">编辑作品</a>
                    <a class="light_button edit-photo" href="<?php echo U('album','album_del','id='.$albumInfo['id']); ?>">删除作品</a>
                <?php endif; ?>
                <?php if($_G['user']['level']>=80): ?>
                <a class="light_button edit-photo" href="<?php echo U('album','album_recommend','id='.$albumInfo['id']); ?>" onclick="return opt_one(this,'确定推荐该作品？')">推荐</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
        <div class="sidebar-comment">
            <?php 
                echo x_comment_helper::comment('album',$albumInfo['id'],!isAjax());
            ?>
        </div>
    </div>
<?php if(!isAjax()): ?>
</div>
<script>
$('div.photo-container a.close').click(function(){
    location.href = '<?php
    if($_G['user']['id'] && $albumInfo['uid'] == $_G['user']['id']){ 
        echo U('album','space','id='.$albumInfo['uid']); 
    }else{
        echo U('base','index'); 
    }
    ?>';
});

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