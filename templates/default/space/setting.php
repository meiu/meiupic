<?php $this->display('common/head.php'); ?>
<?php $this->display('space/inc.banner.php'); ?>
<div class="container">
    <?php $this->display('space/setting_menu.php'); ?>

    <div class="content">
        <div id="avatar" class="avatar-wrap">
            <div class="avatar" id="avatarPreview" style="background-image:url('<?php echo app('user')->getAvatar($_G['user'],'large')?>')"></div>
            <label class="change-btn" id="avatarPicker">
                更换头像
            </label>
        </div>

        <form class="form-basic ajaxform" method="post">
            <div class="line-wrap"> 
                <h1 class="title">基本信息</h1>
            </div> 

            <div class="line-wrap">             
                <label class="label">昵称</label>
                <div class="input-wrap">
                    <input type="text" class="nomal-input" name="nickname" value="<?php echo $_G['user']['nickname']?>" autocomplete="off" data-target="form-basic">
                    <i></i>
                </div>
            </div>

            <div class="line-wrap">
                <label class="label">性别</label>
                <div class="input-wrap">
                    <select name="gender">
                        <option value="n" <?php if($_G['user']['gender']=='n'){echo 'selected="selected"';} ?>>不详</option>
                        <option value="f" <?php if($_G['user']['gender']=='f'){echo 'selected="selected"';} ?>>女</option>
                        <option value="m" <?php if($_G['user']['gender']=='m'){echo 'selected="selected"';} ?>>男</option>
                    </select>
                    <i></i>
                </div>
            </div>

            <div class="line-wrap">
                <label class="label">简介</label>
                <div class="input-wrap">
                    <input type="text" class="nomal-input" name="description" value="<?php echo $_G['user']['description']?>" autocomplete="off" data-target="form-basic">
                    <i></i>
                </div>
            </div>
            
            <?php foreach ($fields as $key => $value): 
            ?>
            <div class="line-wrap">
                <label class="label"><?php echo $value['cname'];?></label>
                <div class="input-wrap">
                    <input type="text" class="nomal-input" name="<?php echo $key;?>" value="<?php echo $iinfo[$key];?>" autocomplete="off" data-target="form-basic">
                    <i></i>
                </div>
            </div>
            <?php endforeach ?>

            <span id="form-basic" class="alert-text"></span>
            <div class="line-wrap">
                <div class="input-wrap">
                    <input type="submit" class="submit-btn" value="保存">
                </div>
            </div>
        </form>
    </div>
</div>
<script>
uploadAvatar('<?php echo U("space","saveavatar")?>');
</script>
<?php $this->display('common/foot.php'); ?>