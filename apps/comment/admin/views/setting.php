<form class="ajaxform" method="post">
<table cellspacing="0">
  <tbody>
  <tr class="th">
    <th>参数设置</th><th>说明</th>
  </tr>
  <tr>
    <td>
      开启评论
    </td>
    <td>
        <label><input type="radio" name="setting[open_comment]" value="1" <?php if($setting['open_comment']){ echo 'checked="checked"';} ?> /> 开启</label>
        <label><input type="radio" name="setting[open_comment]" value="0" <?php if(!$setting['open_comment']){ echo 'checked="checked"';} ?> /> 关闭</label>
    </td>
  </tr>
  <tr>
    <td>
      允许游客评论
    </td>
    <td>
        <label><input type="radio" name="setting[allow_guest]" value="1" <?php if($setting['allow_guest']){ echo 'checked="checked"';} ?> /> 开启</label>
        <label><input type="radio" name="setting[allow_guest]" value="0" <?php if(!$setting['allow_guest']){ echo 'checked="checked"';} ?> /> 关闭</label>
    </td>
  </tr>
  <tr>
    <td>
      允许回复评论
    </td>
    <td>
        <label><input type="radio" name="setting[allow_reply]" value="1" <?php if($setting['allow_reply']){ echo 'checked="checked"';} ?> /> 开启</label>
        <label><input type="radio" name="setting[allow_reply]" value="0" <?php if(!$setting['allow_reply']){ echo 'checked="checked"';} ?> /> 关闭</label>
    </td>
  </tr>
  <tr>
    <td>
      开启赞的按钮
    </td>
    <td>
        <label><input type="radio" name="setting[allow_support]" value="1" <?php if($setting['allow_support']){ echo 'checked="checked"';} ?> /> 开启</label>
        <label><input type="radio" name="setting[allow_support]" value="0" <?php if(!$setting['allow_support']){ echo 'checked="checked"';} ?> /> 关闭</label>
    </td>
  </tr>
  <tr>
    <td>
      开启反对按钮
    </td>
    <td>
        <label><input type="radio" name="setting[allow_object]" value="1" <?php if($setting['allow_object']){ echo 'checked="checked"';} ?> /> 开启</label>
        <label><input type="radio" name="setting[allow_object]" value="0" <?php if(!$setting['allow_object']){ echo 'checked="checked"';} ?> /> 关闭</label>
    </td>
  </tr>
  <tr>
    <td>
      是否需要审核
    </td>
    <td>
        <label><input type="radio" name="setting[need_audit]" value="1" <?php if($setting['need_audit']){ echo 'checked="checked"';} ?> /> 需要</label>
        <label><input type="radio" name="setting[need_audit]" value="0" <?php if(!$setting['need_audit']){ echo 'checked="checked"';} ?> /> 不需要</label>
    </td>
  </tr>
  <tr>
    <td>
      每页显示几条
    </td>
    <td>
      <input type="text" name="setting[comments_perpage]" value="<?php echo $setting['comments_perpage'];?>" />
    </td>
  </tr>
  <tr>
    <td>
      是否启用验证码
    </td>
    <td>
      <label><input type="radio" name="setting[enable_captcha]" value="1" <?php if($setting['enable_captcha']){ echo 'checked="checked"';} ?> /> 开启</label>
      <label><input type="radio" name="setting[enable_captcha]" value="0" <?php if(!$setting['enable_captcha']){ echo 'checked="checked"';} ?> /> 关闭</label>
    </td>
  </tr>
  
</tbody>
</table>

<div>
<input type="submit" class="submit-btn" value="保存修改" />
</div>
</form>