<div class="main-head">
    <h3>邮件配置</h3>
</div>
<form class="ajaxform" method="post">
<table cellspacing="0">
  <tbody>
  <tr class="th">
    <th>参数设置</th><th>说明</th>
  </tr>
  <tr>
    <td>发送方式：</td>
    <td><label><input type="radio" name="setting[protocol]" value="none" <?php if(!isset($setting['protocol'])||$setting['protocol']=='none'){echo 'checked="checked"';} ?> /> 未启用</label> <label><input type="radio" name="setting[protocol]" value="mail" <?php if(isset($setting['protocol'])&&$setting['protocol']=='mail'){echo 'checked="checked"';} ?> /> Mail</label> <label><input type="radio" name="setting[protocol]" value="sendmail" <?php if(isset($setting['protocol'])&&$setting['protocol']=='sendmail'){echo 'checked="checked"';} ?> /> Sendmail</label> <label><input type="radio" name="setting[protocol]" value="smtp" <?php if(isset($setting['protocol'])&&$setting['protocol']=='smtp'){echo 'checked="checked"';} ?> /> Smtp</label></td>
  </tr>
  
  <tr>
    <td>
      发件姓名：
    </td>
    <td>
        <input type="text" size="40" name="setting[send_name]" value="<?php echo isset($setting['send_name'])?$setting['send_name']:''?>" />
    </td>
  </tr>
  <tr>
    <td>
      发件邮箱：
    </td>
    <td>
        <input type="text" size="40" name="setting[send_email]" value="<?php echo isset($setting['send_email'])?$setting['send_email']:''?>" />
    </td>
  </tr>
  <tr class="th">
      <th colspan="2">
  以下内容配置为smtp时必填。
      </th>
  </tr>
  <tr>
    <td>
      发送服务器：
    </td>
    <td>
        <input type="text" size="40" name="setting[smtp_host]" value="<?php echo isset($setting['smtp_host'])?$setting['smtp_host']:''?>" />
    </td>
  </tr>
  <tr>
    <td>
      加密协议：
    </td>
    <td>
      <label><input type="radio" name="setting[smtp_crypto]" value="none" <?php if(!isset($setting['smtp_crypto'])||$setting['smtp_crypto']==''){echo 'checked="checked"';} ?> /> 无</label> <label><input type="radio" name="setting[smtp_crypto]" value="ssl" <?php if(isset($setting['smtp_crypto'])&&$setting['smtp_crypto']=='ssl'){echo 'checked="checked"';} ?> /> SSL</label> <label><input type="radio" name="setting[smtp_crypto]" value="tls" <?php if(isset($setting['smtp_crypto'])&&$setting['smtp_crypto']=='tls'){echo 'checked="checked"';} ?> /> TLS</label>
    </td>
  </tr>
  <tr>
    <td>
      服务器端口：
    </td>
    <td>
        <input type="text" size="40" name="setting[smtp_port]" value="<?php echo isset($setting['smtp_port'])?$setting['smtp_port']:''?>" />
    </td>
  </tr>
  <tr>
    <td>
      发件账号：
    </td>
    <td>
        <input type="text" size="40" name="setting[smtp_username]" value="<?php echo isset($setting['smtp_username'])?$setting['smtp_username']:''?>" />
    </td>
  </tr>
  <tr>
      <td>
      发件账号密码：
    </td>
    <td>
        <input type="text" size="40" name="setting[smtp_password]" value="<?php echo isset($setting['smtp_password'])?$setting['smtp_password']:''?>" />
    </td>
  </tr>
  
</tbody>
</table>

<div>
<input type="submit" class="submit-btn" value="保存修改" />
</div>
</form>