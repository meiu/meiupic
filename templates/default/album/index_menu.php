<nav class="secondary-nav">
    <ul class="nav-list">
        <li <?php if($_G['uri']['m']=='index' && !getGet('t')): ?>class="active"<?php endif; ?> data-index="0">
            <a target="_self" href="<?php echo U('album','index')?>">热门</a>
        </li>
        <li <?php if($_G['uri']['m']=='index' && getGet('t')=='editor'): ?>class="active"<?php endif; ?> data-index="1">
            <a target="_self" href="<?php echo U('album','index','t=editor')?>">编辑推荐</a>
        </li>
        <li <?php if($_G['uri']['m']=='index' && getGet('t')=='fresh'): ?>class="active"<?php endif; ?> data-index="1">
            <a target="_self" href="<?php echo U('album','index','t=fresh')?>">新作</a>
        </li>
        <li <?php if($_G['uri']['m']=='sets'): ?>class="active"<?php endif; ?> data-index="1">
            <a target="_self" href="<?php echo U('album','sets')?>">图集</a>
        </li>
        <li <?php if($_G['uri']['m']=='tags'): ?>class="active"<?php endif; ?> data-index="1">
            <a target="_self" href="<?php echo U('album','tags')?>">热门标签</a>
        </li>
    </ul>
</nav>