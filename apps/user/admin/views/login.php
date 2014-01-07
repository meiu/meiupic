<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>后台登录</title>
    <link rel="stylesheet" href="<?php echo S('user','admin/css/login.css');?>" />
</head>
<body>

<div class="loginArea">
<h1>登录后台</h1>
<div class="notice">
    <?php if($flag == 3): ?>
    <div class="error">
    用户名不能为空！
    </div>
    <?php elseif($flag == 4): ?>
    <div class="error">
    密码不能为空！
    </div>
    <?php elseif($flag == 5): ?>
    <div class="error">
    用户名或密码输入错误！
    </div>
    <?php elseif($flag == 6): ?>
    <div class="error">
    请输入验证码！
    </div>
    <?php elseif($flag == 7): ?>
    <div class="error">
    验证码输入错误！
    </div>
    <?php elseif($flag == 1): ?>
    <div class="success">
    您已成功退出登录！
    </div>
    <?php endif; ?> 
</div>
<form name="login_soft" method="post" action="<?php echo U('user','login','a=dologin');?>">
    <div class="bbg1">
        <div class="bbg2">
            <div class="fields">
                <label>用户名</label>
                <input type="text" name="username" value="<?php if($_G['user']){ echo $_G['user']['username'];} ?>" />
            </div>
            <div class="fields sec">
                <label>密码</label>
                <input type="password" name="userpass" />
            </div>
            <?php 
            $user_setting = getSetting('user_setting',true);
            if($user_setting['enable_admin_captcha']):
            ?>
            <div class="fields captcha">
                <img src="<?php echo U('base','captcha');?>" onclick="this.src='<?php echo U('base','captcha');?>&t='+Math.random();" alt="验证码" />
                <label>验证码</label>
                <input type="text" name="captcha" />
            </div>
        <?php endif; ?>
        </div>
    </div>
    
    <input class="loginbtn" type="submit" value="登 录" />
</form>
</div>
<script type="text/javascript">
<?php if($_G['user']): ?>
    window.document.login_soft.userpass.focus();
<?php else: ?>
    window.document.login_soft.username.focus();
<?php endif; ?>
</script>
</body>
</html>