<nav class="secondary-nav">
    <ul class="nav-list">
        <li <?php if($_G['uri']['m']=='space' && empty($albumInfo)): ?>class="active"<?php endif; ?> data-index="0">
            <a target="_self" href="<?php echo U('album','space','id='.$uid)?>">全部图片</a>
        </li>
        <li <?php if($_G['uri']['m']=='album' || ($_G['uri']['m']=='my' && $albumInfo)): ?>class="active"<?php endif; ?> data-index="1">
            <a target="_self" href="<?php echo U('album','album','id='.$uid)?>">相册</a>
        </li>
        <li <?php if($_G['uri']['m']=='like'): ?>class="active"<?php endif; ?> data-index="1">
            <a target="_self" href="<?php echo U('album','like','id='.$uid)?>">喜欢</a>
        </li>
    </ul>
</nav>