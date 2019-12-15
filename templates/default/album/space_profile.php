<?php $this->display('common/head.php'); ?>
<?php $this->display('space/inc.banner.php'); ?>
<div class="container">
    <?php $this->display('album/space_menu.php'); ?>
    <div class="content">
            <div class="line-wrap"> 
                <?php if($uinfo['id']==$_G['user']['id']): ?>
                <a class="normal-btn right" href="<?php echo U('space','setting'); ?>">修改资料</a>
                <?php endif; ?>
                <h1 class="title">基本信息</h1>
            </div> 

            <div class="line-wrap">             
                <label class="label">昵称</label>
                <div class="input-wrap"><?php echo $uinfo['nickname']?>
                </div>
            </div>

            <div class="line-wrap">
                <label class="label">性别</label>
                <div class="input-wrap">
                        <?php if($uinfo['gender']=='n'){echo '不详';} ?>
                        <?php if($uinfo['gender']=='f'){echo '女';} ?>
                        <?php if($uinfo['gender']=='m'){echo '男';} ?>
                </div>
            </div>

            <div class="line-wrap">
                <label class="label">简介</label>
                <div class="input-wrap"><?php echo $uinfo['description']?></div>
            </div>
            
            <?php foreach ($fields as $key => $value): 
            ?>
            <div class="line-wrap">
                <label class="label"><?php echo $value['cname'];?></label>
                <div class="input-wrap">
                    <?php echo $iinfo[$key];?>
                </div>
            </div>
            <?php endforeach ?>
    </div>
</div>
<?php $this->display('common/foot.php'); ?>