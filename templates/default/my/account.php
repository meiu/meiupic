<?php $this->display('my/head.php'); ?>
<div class="container">
    <?php $this->display('my/setting_menu.php'); ?>
    <div class="content">
        <form class="form-basic ajaxform" method="post" action="<?php echo U('my','account','a=savebind')?>">
            <div class="line-wrap"> 
                <h1 class="title">绑定激活</h1>
            </div> 

            <div class="line-wrap">             
                <label class="label">Email</label>
                <div class="input-wrap">
                    <input type="text" class="nomal-input <?php if($_G['user']['email_actived']){echo 'readonly';} ?>" name="email" value="<?php echo $_G['user']['email']?>" autocomplete="off" data-target="form-basic" <?php if($_G['user']['email_actived']){echo 'readonly="readonly"';} ?>>
                    <i></i><?php if(!$_G['user']['email_actived']):?><input id="send_active_code" type="button" class="smallbtn" value="发送激活邮件" /><?php else: ?><span class="f-green">已激活</span><?php endif; ?>
                </div>
            </div>
            <div class="line-wrap">             
                <label class="label">手机号</label>
                <div class="input-wrap">
                    <input type="text" class="nomal-input <?php if($_G['user']['mobile_actived']){echo 'readonly';} ?>" placeholder="输入手机号" id="mobile" name="mobile" value="<?php echo $_G['user']['mobile']?>" autocomplete="off" data-target="form-basic" <?php if($_G['user']['mobile_actived']){echo 'readonly="readonly"';} ?>>
                    <i></i><?php if(!$_G['user']['mobile_actived']):?><input type="button" class="smallbtn mobile_captcha" orgi="<?php echo U('base','captcha','type=sms');?>" smsurl="<?php echo U('sms','send_code');?>" value="获取手机验证码"><?php else: ?><span class="f-green">已绑定</span><?php endif; ?>
                </div>
                <?php if(!$_G['user']['mobile_actived']): ?>
                <div class="input-wrap">
                    <input type="text" class="nomal-input" name="mobile_code" value="" placeholder="输入验证码" autocomplete="off" data-target="form-basic">
                </div>
                <?php endif; ?>
            </div>
            <span id="form-basic" class="alert-text"></span>
            <div class="line-wrap">
                <div class="input-wrap">
                    <input type="submit" class="submit-btn" value="保存">
                </div>
            </div>
        </form>
    </div>
    <div class="content">
        <form class="form-basic ajaxform" method="post">
            <div class="line-wrap"> 
                <h1 class="title">修改密码</h1>
            </div> 

            <div class="line-wrap">             
                <label class="label">原密码</label>
                <div class="input-wrap">
                    <input type="password" class="nomal-input" name="oldpass" value="" autocomplete="off" data-target="form-basic">
                    <i></i>
                </div>
            </div>
            <div class="line-wrap">
                <label class="label">新密码</label>
                <div class="input-wrap">
                    <input type="password" class="nomal-input" name="newpass" value="" autocomplete="off" data-target="form-basic">
                    <i></i>
                </div>
            </div>
            <div class="line-wrap">
                <label class="label">再次输入新密码</label>
                <div class="input-wrap">
                    <input type="password" class="nomal-input" name="passagain" value="" autocomplete="off" data-target="form-basic">
                    <i></i>
                </div>
            </div>

            <span id="form-basic" class="alert-text"></span>
            <div class="line-wrap">
                <div class="input-wrap">
                    <input type="submit" class="submit-btn" value="保存">
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript" src="<?php echo S('sms','js/sms.js') ?>"></script>
<script>
    $(function(){
        $('#send_active_code').click(function(){
            $.post('<?php echo U('my','account','a=send_activecode')?>',{isajax:1},function(data){
                ajaxAlert(data,1,true);
            },'json');
        });
        showMobileCaptcha('.mobile_captcha','#mobile');
    })
</script>
<?php $this->display('my/foot.php'); ?>