<?php $this->display('common/head.php'); ?>
<script type="text/javascript" src="<?php echo S('base','js/jquery.placeholder.js');?>"></script>

<div class="wrap">
    <div class="min-inner session-edge">
        <h2 class="big-title">注册</h2>
        <p class="title-description">您正在注册<?php echo getSetting('site_title');?>独立帐号</p>
        
        <div class="sfid-register">
            <form class="session-form" action="<?php echo U('user','register');?>" method="post" onsubmit="return doRegister(this);">
                <div class="session-aside">
                    
                </div>
                <p>
                    <label class="hid" for="username">用户名</label>
                    <input type="text" required="" value="" placeholder="用户名" name="username" class="text-14" />
                </p>
                <p>
                    <label class="hid" for="userpass">登录密码</label>
                    <input type="password" required="" placeholder="登录密码" name="userpass" class="text-14" />
                </p>
                <p>
                    <label class="hid" for="email">Email</label>
                    <input type="email" required="" value="" placeholder="Email" name="email" class="text-14" />
                </p>
                <p>
                    <label class="hid" for="nickname">常用昵称或真名</label>
                    <input type="text" required="" placeholder="常用昵称或真名" name="nickname" class="text-14" />
                </p>
                <?php foreach ($fields as $key => $value): 
                if($value['show'] != 1) continue;
                ?>
                <p>
                    <label class="hid" for="<?php echo $key;?>"><?php echo $value['cname'];?></label>
                    <input type="text" placeholder="<?php echo $value['cname'];?>" name="<?php echo $key;?>" class="text-14" />
                </p>
                <?php endforeach ?>
                <?php 
                $user_setting = getSetting('user_setting',true);
                if($user_setting['enable_reg_captcha']):
                ?>
                <p class="captcha">
                    <label class="hid" for="captcha">验证码</label>
                    <input type="text" required="" placeholder="验证码" name="captcha" class="text-14" />
                    <span class="captcha_show">
                        <img orgisrc="<?php echo U('base','captcha');?>" src="<?php echo U('base','captcha');?>" alt="验证码" /> <a href="#">更换验证码</a>
                    </span>
                </p>
                <?php endif; ?>
                <div class="form-action">
                    <!--<span class="left">您将同意并接受<a href="#" target="_blank">《服务条款》</a></span>-->
                    <input type="hidden" name="isajax" value="1" />
                    <input type="submit" class="btn btn-xl" value="注册" />
                </div>
            </form>
        </div>

    </div>
</div>
<script type="text/javascript">
function doRegister(f){
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

    $('.captcha_show a,.captcha_show img').click(function(){
        var osrc=$('.captcha_show img').attr('orgisrc');
        $('.captcha_show img').attr('src',osrc+(osrc.indexOf('?')>=0?'&':'?')+'t='+Math.random() );
        return false;
    });
});
</script>
<?php $this->display('common/foot.php'); ?>