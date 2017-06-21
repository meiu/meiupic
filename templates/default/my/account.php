<?php $this->display('my/head.php'); ?>
<div class="container">
    <?php $this->display('my/setting_menu.php'); ?>

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
<?php $this->display('my/foot.php'); ?>