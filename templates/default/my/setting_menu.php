<nav class="secondary-nav">
    <ul class="nav-list">
        <li <?php if($_G['uri']['m']=='setting'): ?>class="active"<?php endif; ?> data-index="0">
            <a target="_self" href="<?=U('my','setting')?>">资料</a>
        </li>
        <li <?php if($_G['uri']['m']=='account'): ?>class="active"<?php endif; ?> data-index="1">
            <a target="_self" href="<?=U('my','account')?>">账号和密码</a>
        </li>
    </ul>
</nav>