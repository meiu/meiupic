<div class="main-head">
    <h3>新增/编辑用户</h3>
    <a href="<?php echo U('user','index');?>" >&lt;&lt;返回</a>
</div>
<form id="submit_form" class="ajaxform" method="post">
    <table class="edit_table">
        <tr>
        <th>用户名：</th>
        <td><input type="text" name="username" size="20" value="<?php echo $info['username'];?>" /></td>
        </tr>

        <tr>
        <th>昵称：</th>
        <td><input type="text" name="nickname" size="20" value="<?php echo $info['nickname'];?>" /></td>
        </tr>

        <tr>
        <th>Email：</th>
        <td><input type="text" name="email" size="20" value="<?php echo $info['email'];?>" /></td>
        </tr>

        <tr>
        <th>登录密码：</th>
        <td><input type="password" name="userpass" size="20" value="" /> (不修改可留空)</td>
        </tr>
        <tr>
        <th>确认密码：</th>
        <td><input type="password" name="userpass2" size="20" value="" /></td>
        </tr>
        <tr>
        <th>会员级别：</th>
        <td><input type="text" name="level" size="20" value="<?php echo $info['level'];?>" /> (99为超级管理员)</td>
        </tr>
        <!--额外字段开始-->
        <?php for($i=1;$i<=8;$i++):
        if(!isset($fields['extra'.$i])) continue;
        ?>
        <tr>
        <th><?php echo $fields['extra'.$i]['cname'];?>：</th>
        <td><input type="text" name="<?php echo 'extra'.$i;?>" size="20" value="<?php echo $iinfo['extra'.$i];?>" /></td>
        </tr>
        <?php 
        endfor; ?>

        <tr>
        <th></th>
        <td class="submitline"><input type="submit" class="submit-btn" value="保存修改" /></td>
        </tr>
    </table>
</form>