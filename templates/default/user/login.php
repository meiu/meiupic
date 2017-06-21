<?php $this->display('common/head.php'); ?>
<script type="text/javascript" src="<?php echo S('base','js/jquery.placeholder.js');?>"></script>
<div class="wrap">
    <div class="min-inner session-edge">
        <h2 class="big-title">登录</h2>
        <p class="title-description">欢迎登陆<?php echo getSetting('site_title');?></p>
        
        <div class="sfid-login">
            ( <a href="<?php echo U('user','register');?>">注册新账号</a> )
            <form method="post" action="<?php echo U('user','login','a=dologin');?>" onsubmit="return doLogin(this)">
                <p>
                    <label class="hid" for="username">用户名</label>
                    <input type="text" required="" value="" placeholder="用户名" name="username" class="text-14" />
                </p>
                <p>
                    <label class="hid" for="userpass">登录密码</label>
                    <input type="password" required="" placeholder="登录密码" name="userpass" class="text-14">
                </p>
                <?php 
                $user_setting = getSetting('user_setting',true);
                if($user_setting['enable_login_captcha']):
                ?>
                <p class="captcha">
                    <span class="captcha_show"><img orgisrc="<?php echo U('base','captcha');?>" src="<?php echo U('base','captcha');?>" alt="验证码" /><br /><a href="#">单击更换验证码</a></span>
                    <input type="text" required="" placeholder="验证码" name="captcha" class="text-14">
                </p>
                <?php endif; ?>
                <p class="loginbtn">
                    <input type="hidden" name="isajax" value="1" />
                    <input type="hidden" name="redirect" value="<?php echo $redirect;?>" />
                    <input type="submit" value="登录" class="btn-xl" />
                </p>
                <div class="remember"><input type="checkbox" checked="" value="1" name="remember" id="remember"> <label for="remember">下次自动登录</label></div>
            </form>
        </div>

    </div>
</div>
<script type="text/javascript">
function doLogin(f){
    $('.text-error').remove();
    $('.input-error').removeClass('input-error');
    $.post($(f).attr('action'),$(f).serializeArray(),function(data){
        if(data.ret){
            window.location.href=data.redirect;
        }else{
            if(data.field){
                $('input[name="'+data.field+'"]').addClass('input-error').after('<span class="text-error">'+data.msg+'</span>');
            }else{
                alert(data.msg);
            }
        }
    },'json');
    return false;
}

$(function(){
    $('input, textarea').placeholder();
    $('.sfid-login input[name=username]').focus();
    $('.sfid-login input[name=captcha]').focus(function(){
        $('.captcha_show').show();
    });
    $('.captcha_show a,.captcha_show img').click(function(){
        var osrc=$('.captcha_show img').attr('orgisrc');
        $('.captcha_show img').attr('src',osrc+(osrc.indexOf('?')>=0?'&':'?')+'t='+Math.random() );
        return false;
    });
});
</script>
<?php $this->display('common/foot.php'); ?>