<div class="main-head">
    <h3>相册设置</h3>
</div>
<form class="ajaxform" method="post">
<table cellspacing="0">
  <tbody>
  <tr class="th">
    <th width="200">系统变量</th><th>设置</th>
  </tr>
  <tr>
    <td>
    Email未激活不允许上传
    </td>
    <td>
      <label><input type="radio" name="setting[album_email_notactive_cannotpost]" value="1" <?php if(@$settings_info['album_email_notactive_cannotpost']){ echo 'checked="checked"';} ?>  /> 开启</label>
      <label><input type="radio" name="setting[album_email_notactive_cannotpost]" value="0" <?php if(!@$settings_info['album_email_notactive_cannotpost']){ echo 'checked="checked"';} ?>  /> 关闭</label>
    </td>
  </tr>
  <tr>
    <td>
    手机号码未绑定不允许上传
    </td>
    <td>
      <label><input type="radio" name="setting[album_mobile_notactive_cannotpost]" value="1" <?php if(@$settings_info['album_mobile_notactive_cannotpost']){ echo 'checked="checked"';} ?>  /> 开启</label>
      <label><input type="radio" name="setting[album_mobile_notactive_cannotpost]" value="0" <?php if(!@$settings_info['album_mobile_notactive_cannotpost']){ echo 'checked="checked"';} ?>  /> 关闭</label>
    </td>
  </tr>
  <tr>
    <td>
    上传时自动缩放大图片
    </td>
    <td>
      <label><input type="radio" name="setting[album_resize_img]" value="1" <?php if(@$settings_info['album_resize_img']){ echo 'checked="checked"';} ?>  onclick="$('#img_resize').show();" /> 开启</label>
      <label><input type="radio" name="setting[album_resize_img]" value="0" <?php if(!@$settings_info['album_resize_img']){ echo 'checked="checked"';} ?>  onclick="$('#img_resize').hide();" /> 关闭</label>
    </td>
  </tr>
  </tbody>
  <tbody id="img_resize" <?php if(!@$settings_info['album_resize_img']){ echo 'style="display:none;"';} ?>>
  <tr>
    <td>
    开启浏览器预处理
    </td>
    <td>
      <label><input type="radio" name="setting[album_pre_resize_img]" value="1" <?php if(@$settings_info['album_pre_resize_img']){ echo 'checked="checked"';} ?> /> 开启</label>
      <label><input type="radio" name="setting[album_pre_resize_img]" value="0" <?php if(!@$settings_info['album_pre_resize_img']){ echo 'checked="checked"';} ?> /> 关闭</label>
    </td>
    </tr>
  <tr>
  	<td>
    自动缩放尺寸（宽度/高度）
    </td>
    <td>
      <input type="text" size="10" name="setting[album_resize_img_w]" value="<?php echo arrval($settings_info,'album_resize_img_w');?>" /> x
      <input type="text" size="10" name="setting[album_resize_img_h]" value="<?php echo arrval($settings_info,'album_resize_img_h');?>" />
    </td>
   </tr>
   
  </tbody>
  <tbody>
  <tr>
    <td>
    是否启用水印
    </td>
    <td>
      <label><input type="radio" name="setting[album_enable_watermark]" value="1" <?php if(@$settings_info['album_enable_watermark']){ echo 'checked="checked"';} ?>  onclick="$('#img_wm').show();" /> 开启</label>
      <label><input type="radio" name="setting[album_enable_watermark]" value="0" <?php if(!@$settings_info['album_enable_watermark']){ echo 'checked="checked"';} ?>  onclick="$('#img_wm').hide();" /> 关闭</label>
    </td>
  </tr>

  </tbody>
  <tbody id="img_wm" <?php if(!@$settings_info['album_enable_watermark']){ echo 'style="display:none;"';} ?>>
  <tr>
    <td>
      图片水印地址
    </td>
    <td>
      <input type="text" size="50" class="picarea" id="watermark_path" name="setting[album_watermark_path]" value="<?php echo arrval($settings_info,'album_watermark_path');?>" />  <input type="button" class="btn" value="浏览" onclick="viewfile('watermark_path','watermark')" />
    </td>
  </tr>
  <tr>
    <td>
      水印位置
    </td>
    <td>
      <table width="400">
            <tbody><tr>
                <td height="25"><label><input name="setting[album_water_mark_pos]" value="1" type="radio" <?php if(@$settings_info['album_water_mark_pos']==1):?>checked="checked"<?php endif;?>> 顶部居左</label></td>
                <td><label><input name="setting[album_water_mark_pos]" value="2" type="radio" <?php if(@$settings_info['album_water_mark_pos']==2):?>checked="checked"<?php endif;?>> 顶部居中</label></td>
                <td><label><input name="setting[album_water_mark_pos]" value="3" type="radio" <?php if(@$settings_info['album_water_mark_pos']==3):?>checked="checked"<?php endif;?>> 顶部居右</label></td>

            </tr>
            <tr>
                <td height="25"><label><input name="setting[album_water_mark_pos]" value="4" type="radio" <?php if(@$settings_info['album_water_mark_pos']==4):?>checked="checked"<?php endif;?>> 左部居中</label></td>
                <td><label><input name="setting[album_water_mark_pos]" value="5" type="radio" <?php if(@$settings_info['album_water_mark_pos']==5):?>checked="checked"<?php endif;?>> 图片中心</label></td>
                <td><label><input name="setting[album_water_mark_pos]" value="6" type="radio" <?php if(@$settings_info['album_water_mark_pos']==6):?>checked="checked"<?php endif;?>> 右部居中</label></td>
            </tr>
            <tr>
                <td height="25"><label><input name="setting[album_water_mark_pos]" value="7" type="radio" <?php if(@$settings_info['album_water_mark_pos']==7):?>checked="checked"<?php endif;?>> 底部居左</label></td>
                <td><label><input name="setting[album_water_mark_pos]" value="8" type="radio" <?php if(@$settings_info['album_water_mark_pos']==8):?>checked="checked"<?php endif;?>> 底部居中</label></td>
                <td><label><input name="setting[album_water_mark_pos]" value="9" type="radio" <?php if(@$settings_info['album_water_mark_pos']==9):?>checked="checked"<?php endif;?>> 底部居右</label></td>
            </tr>
            <tr>
                <td colspan="3" height="25"><label><input name="setting[album_water_mark_pos]" value="0" type="radio" <?php if(@$settings_info['album_water_mark_pos']==0):?>checked="checked"<?php endif;?>> 随机</label></td>
            </tr>
        </tbody></table>
    </td>
  </tr>
</tbody>
</table>

<div>
<input type="submit" class="submit-btn" value="保存修改" />
</div>
</form>
<script>
function viewfile(iptid,type){
  uploadWin.open(type,1,function(vReturnValue){
      $('#'+iptid).val(vReturnValue.path);
  });
}

$(function(){
  picArea();
});
</script>