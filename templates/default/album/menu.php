<nav class="secondary-nav">
    <ul class="nav-list">
        <li <?php if($_G['uri']['m']=='my'): ?>class="active"<?php endif; ?> data-index="0">
            <a target="_self" href="<?=U('album','my')?>">全部图片</a>
        </li>
        <li <?php if($_G['uri']['m']=='album'): ?>class="active"<?php endif; ?> data-index="1">
            <a target="_self" href="<?=U('album','album')?>">相册</a>
        </li>
    </ul>
</nav>