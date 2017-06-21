<?php $this->display('my/head.php'); ?>
<div class="container">
    <?php $this->display('my/setting_menu.php'); ?>

    <div class="content">
        <div id="avatar" class="avatar-wrap">
            <div class="avatar" id="avatarPreview" style="background-image:url('<?=app('user')->getAvatar($_G['user'],'large')?>')"></div>
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
                    <input type="text" class="nomal-input" name="nickname" value="<?=$_G['user']['nickname']?>" autocomplete="off" data-target="form-basic">
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
                    <input type="text" class="nomal-input" name="description" value="<?=$_G['user']['description']?>" autocomplete="off" data-target="form-basic">
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
var uploader = WebUploader.create({
    auto: true,
    swf:"<?=S('base','webuploader/Uploader.swf')?>",
    server: "<?=U('my','saveavatar')?>",
    pick: '#avatarPicker',
    accept: {
        title: 'Images',
        extensions: 'gif,jpg,jpeg,png',
        mimeTypes: 'image/*'
    }
});
uploader.on( 'uploadProgress', function( file, percentage ) {
    $('#avatarPicker label').html('上传：'+percentage+'%');
});
uploader.on( 'uploadSuccess', function( file ) {
    $('#avatarPicker label').html('更换头像');
    art.dialog.tips('上传成功！',1,true);
    setTimeout(function(){
        window.location.reload();
    },1000);
});
uploader.on( 'uploadAccept', function( file, response ) {
    if ( response.error ) {
        console.log(response.error);
        return false;
    }else{
        return true;
    }
});
uploader.on( 'uploadError', function( file ) {
   $('#avatarPicker label').html('更换头像');
    art.dialog.tips('上传失败！',1,true);
});
</script>
<?php $this->display('my/foot.php'); ?>