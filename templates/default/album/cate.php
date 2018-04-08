<?php $this->display('common/head.php'); ?>
<div class="container">
    <nav class="secondary-nav">
    <div class="caption-header">
        <h1 class="caption-name"><?php echo $cateinfo['name']; ?></h1>
        <span class="caption-posts"><?php echo $totalCount; ?>个相册</span>
    </div>
    <ul class="nav-list">
        <li <?php if(!getGet('t')): ?>class="active"<?php endif; ?> data-index="0">
            <a target="_self" href="<?=app('album')->makeCateLink($cateinfo)?>">新作</a>
        </li>
        <li <?php if(getGet('t')=='editor'): ?>class="active"<?php endif; ?> data-index="1">
            <a target="_self" href="<?=app('album')->makeCateLink($cateinfo,array('t'=>'editor'))?>">编辑推荐</a>
        </li>
    </ul>
    </nav>
    <div class="list-body">
        <div class="content-album">
            <div class="listCont">
                <?php $this->display('album/album_normal_list.php'); ?>
            </div>
            <div class="pageset" style="display:none"><?php echo $pagestr; ?></div> 
        </div>
    </div>
    <div class="loadingbar" style="display:none;"><label>努力加载中</label></div>
</div>

<script type="text/javascript" src="<?php echo S('album','js/loadmore.js'); ?>"></script>
<?php $this->display('my/foot.php'); ?>