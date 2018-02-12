<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php if(isset($site_title)): echo $site_title; else: ?><?php echo getSetting('site_title');?> - <?php echo getSetting('site_sub_title');?><?php endif; ?></title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
    <meta name="keywords" content="<?php if(isset($site_keywords)){ echo $site_keywords; } ?>" />
    <meta name="description" content="<?php if(isset($site_description)){ echo $site_description; } ?>" />
    <script type="text/javascript" src="<?=S('base','js/jquery.min.js');?>"></script>
    <script type="text/javascript" src="<?php echo S('base','js/jquery.placeholder.js');?>"></script>
    <link rel="stylesheet" href="<?=ST('css/login.css')?>" type="text/css" />
</head>
<body>
<div class="register-box">
    <div class="mod-head">
        <a href="/"><img src="<?php echo ST('images/login_logo.png');?>" alt="<?php echo getSetting('site_title');?>"></a>
        <h1>找回密码</h1>
    </div>
    <div class="mod-body">
            <form class="session-form" action="<?php echo U('user','findpass');?>" method="post" onsubmit="return postFindForm(this);">
                <ul>
                <li class="alert alert-danger error_message" style="display: none;">
                    
                </li>
                <li>
                    <input type="text" required="" value="" placeholder="请输入手机号码或Email" name="username" class="form-control" />
                </li>
                <li class="captcha clearfix">
                    <input type="text" required="" placeholder="验证码" name="captcha" class="form-control" />
                    <i class="captcha_show">
                        <img orgisrc="<?php echo U('base','captcha');?>" src="<?php echo U('base','captcha');?>" alt="验证码" />
                    </i>
                </li>
                <li class="form-action">
                    <input type="hidden" name="isajax" value="1" />
                    <input type="submit" class="btn btn-xl" value="下一步" />
                </li>
                </ul>
            </form>
    </div>
</div>
<script type="text/javascript" src="<?php echo S('sms','js/sms.js') ?>"></script>
<script type="text/javascript">
function postFindForm(f){
    $('.error_message').hide();
    $.post($(f).attr('action'),$(f).serializeArray(),function(data){
        if(data.ret){
            window.location.href=data.redirect;
        }else{
            $('.error_message').html(data.msg).show();
        }
    },'json');
    return false;
}
$(function(){
    $('input, textarea').placeholder();
    $('input[name=username]').focus();
    $('.captcha_show img').click(function(){
        var osrc=$('.captcha_show img').attr('orgisrc');
        $('.captcha_show img').attr('src',osrc+(osrc.indexOf('?')>=0?'&':'?')+'t='+Math.random() );
        return false;
    });
});
</script>
<?php $this->display('user/login_foot.php'); ?>