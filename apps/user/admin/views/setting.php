<div class="main-head">
    <h3>用户设置</h3>
</div>
<form class="ajaxform" method="post">
<table cellspacing="0">
  <tbody>
  <tr class="th">
    <th>参数设置</th><th>说明</th>
  </tr>
  <tr>
    <td>
      允许用户注册
    </td>
    <td>
        <label><input type="radio" name="setting[enable_register]" value="1" <?php if($setting['enable_register']){ echo 'checked="checked"';} ?> /> 开启</label>
        <label><input type="radio" name="setting[enable_register]" value="0" <?php if(!$setting['enable_register']){ echo 'checked="checked"';} ?> /> 关闭</label>
    </td>
  </tr>
  <tr>
    <td>
      登录启用验证码
    </td>
    <td>
        <label><input type="radio" name="setting[enable_login_captcha]" value="1" <?php if($setting['enable_login_captcha']){ echo 'checked="checked"';} ?> /> 开启</label>
        <label><input type="radio" name="setting[enable_login_captcha]" value="0" <?php if(!$setting['enable_login_captcha']){ echo 'checked="checked"';} ?> /> 关闭</label>
    </td>
  </tr>
  <tr>
    <td>
      注册启用验证码
    </td>
    <td>
        <label><input type="radio" name="setting[enable_reg_captcha]" value="1" <?php if($setting['enable_reg_captcha']){ echo 'checked="checked"';} ?> /> 开启</label>
        <label><input type="radio" name="setting[enable_reg_captcha]" value="0" <?php if(!$setting['enable_reg_captcha']){ echo 'checked="checked"';} ?> /> 关闭</label>
    </td>
  </tr>
  <tr>
    <td>
      注册启用手机验证码
    </td>
    <td>
        <label><input type="radio" name="setting[enable_mobile_captcha]" value="1" <?php if($setting['enable_mobile_captcha']){ echo 'checked="checked"';} ?> /> 开启</label>
        <label><input type="radio" name="setting[enable_mobile_captcha]" value="0" <?php if(!$setting['enable_mobile_captcha']){ echo 'checked="checked"';} ?> /> 关闭</label>
    </td>
  </tr>
  <tr>
    <td>
      后台启用验证码
    </td>
    <td>
        <label><input type="radio" name="setting[enable_admin_captcha]" value="1" <?php if($setting['enable_admin_captcha']){ echo 'checked="checked"';} ?> /> 开启</label>
        <label><input type="radio" name="setting[enable_admin_captcha]" value="0" <?php if(!$setting['enable_admin_captcha']){ echo 'checked="checked"';} ?> /> 关闭</label>
    </td>
  </tr>
</tbody>
</table>

<div>
<input type="submit" class="submit-btn" value="保存修改" />
</div>
</form>