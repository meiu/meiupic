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
<div id="wrapper">
    <div class="login-box">
        <div class="mod-body clearfix">
            <div class="content pull-left">
                <h1 class="logo"><a href="/"><img src="<?php echo ST('images/login_logo.png');?>" alt="<?php echo getSetting('site_title');?>"></a></h1>
                <h2>登录<?php echo getSetting('site_title');?></h2>
                <form id="login_form" method="post" onsubmit="return doLogin(this)" action="<?php echo U('user','login','a=dologin');?>">
                    <ul>
                        <li>
                            <input type="text" class="form-control" placeholder="用户名/邮箱/手机号码" name="username" />
                        </li>
                        <li>
                            <input type="password" class="form-control" placeholder="密码" name="userpass" />
                        </li>
                        <?php 
                        $user_setting = getSetting('user_setting',true);
                        if($user_setting['enable_login_captcha']):
                        ?>
                        <li class="captcha clearfix">
                            <input type="text" required="" placeholder="验证码" name="captcha" class="form-control">
                            <span class="captcha_show"><img orgisrc="<?php echo U('base','captcha');?>" src="<?php echo U('base','captcha');?>" alt="验证码" /></span>
                        </li>
                        <?php endif; ?>

                        <li class="alert alert-danger error_message" style="display: none;">
                        </li>
                        <li class="last">
                            <input type="hidden" name="isajax" value="1" />
                            <input type="hidden" name="redirect" value="<?php echo $redirect;?>" />
                            <input type="submit" value="登录" class="btn-xl" />
                            <label>
                                <input type="checkbox" checked="" value="1" name="remember" id="remember">
                                记住我
                            </label>
                            <a href="<?php echo U('user','findpass');?>">&nbsp;&nbsp;忘记密码</a>
                        </li>
                    </ul>
                </form>
            </div>
            <div class="side-bar pull-left">
            </div>
        </div>
        <div class="mod-footer">
            <span>还没有账号?</span>&nbsp;&nbsp;
            <a href="<?php echo U('user','register');?>">立即注册</a>&nbsp;&nbsp;•&nbsp;&nbsp;
        </div>
    </div>
</div>
<script type="text/javascript">
function doLogin(f){
    $('.error_message').hide();
    $.post($(f).attr('action'),$(f).serializeArray(),function(data){
        if(data.ret){
            window.location.href=data.redirect;
        }else{
            if(data.field){
                $('.error_message').html(data.msg).show();
            }else{
                alert(data.msg);
            }
        }
    },'json');
    return false;
}

$(function(){
    $('input, textarea').placeholder();
    $('input[name=username]').focus();
    $('.captcha_show a,.captcha_show img').click(function(){
        var osrc=$('.captcha_show img').attr('orgisrc');
        $('.captcha_show img').attr('src',osrc+(osrc.indexOf('?')>=0?'&':'?')+'t='+Math.random() );
        return false;
    });
});
</script>
<?php $this->display('user/login_foot.php'); ?>