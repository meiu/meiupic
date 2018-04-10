<?php $this->display('my/head.php'); ?>
<div class="container">
    <?php $this->display('my/setting_menu.php'); ?>

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
var uploader = new plupload.Uploader({
    runtimes : 'html5,flash,silverlight,html4',
    browse_button : 'avatarPicker',
    url : "<?php echo U('my','saveavatar')?>",
    flash_swf_url : '<?php echo S("base","plupload/Moxie.swf");?>',
    silverlight_xap_url : '<?php echo S("base","plupload/Moxie.xap");?>',
    resize : { width : 400, height : 400, quality : 90 },
    filters : {
        max_file_size : '2mb',
        mime_types: [
            {title : "Image files", extensions : "jpg,gif,png"}
        ]
    },

    init: {
        FilesAdded: function(up, files) {
            uploader.start();
        },
        UploadProgress: function(up, file) {
            $('#avatarPicker label').html('上传：'+file.percent +'%');
        },
        Error: function(up, err) {
            $('#avatarPicker label').html('更换头像');
            art.dialog.tips('上传失败！',1,true);
        },
        UploadComplete: function(up, files) {
            $('#avatarPicker label').html('更换头像');
            art.dialog.tips('上传成功！',1,true);
            setTimeout(function(){
                window.location.reload();
            },1000);
        }
    }
});

uploader.init();
</script>
<?php $this->display('my/foot.php'); ?>