<form class="ajaxform" method="post">
<table cellspacing="0">
  <tbody>
  <tr class="th">
    <th>参数设置</th><th>说明</th>
  </tr>
  <tr>
    <td>发送接口：</td>
    <td><label><input type="radio" name="setting[api_type]" value="none" <?php if(!isset($setting['api_type'])||$setting['api_type']=='none'){echo 'checked="checked"';} ?> /> 未启用</label> <label><input type="radio" name="setting[api_type]" value="dingdong" <?php if(isset($setting['api_type'])&&$setting['api_type']=='dingdong'){echo 'checked="checked"';} ?> /> 叮咚云</label> <label><input type="radio" name="setting[api_type]" value="yunpian" <?php if(isset($setting['api_type'])&&$setting['api_type']=='yunpian'){echo 'checked="checked"';} ?> /> 云片</label></td>
  </tr>
  <tr>
    <td>
      发送短信KEY
    </td>
    <td>
        <input type="text" size="40" name="setting[api_key]" value="<?=isset($setting['api_key'])?$setting['api_key']:''?>" />
    </td>
  </tr>
  <tr>
    <td>
      短信签名
    </td>
    <td>
        <input type="text" size="40" name="setting[sign]" value="<?=isset($setting['sign'])?$setting['sign']:''?>" />
    </td>
  </tr>
  <tr>
    <td>
      验证码模版
    </td>
    <td>
        <textarea cols="50" rows="3" name="setting[yzmtpl]"><?=isset($setting['yzmtpl'])?$setting['yzmtpl']:''?></textarea>
    </td>
  </tr>
</tbody>
</table>

<div>
<input type="submit" class="submit-btn" value="保存修改" />
</div>
</form>