<nav class="secondary-nav">
    <?php if($uid == $_G['user']['id'] && $_G['uri']['m']=='space_sets'): ?>
    <a class="nav-add" href="javascript:void(0)" onclick="MuiShow('<?php echo U('album','sets_create');?>','创建图集',500,350);">
        创建图集
    </a>
    <?php endif; ?>
    <ul class="nav-list">
        <li <?php if($_G['uri']['m']=='space'): ?>class="active"<?php endif; ?> data-index="0">
            <a target="_self" href="<?php echo U('album','space','id='.$uid)?>">全部作品</a>
        </li>
        <li <?php if($_G['uri']['m']=='space_sets'): ?>class="active"<?php endif; ?> data-index="1">
            <a target="_self" href="<?php echo U('album','space_sets','id='.$uid)?>">图集</a>
        </li>
        <li <?php if($_G['uri']['m']=='space_like'): ?>class="active"<?php endif; ?>>
            <a target="_self" href="<?php echo U('album','space_like','id='.$uid)?>">喜欢</a>
        </li>
        <li <?php if($_G['uri']['m']=='space_profile'): ?>class="active"<?php endif; ?>>
            <a href="<?php echo U('album','space_profile','id='.$uid)?>" target="_self">资料</a>
        </li>
    </ul>
</nav>