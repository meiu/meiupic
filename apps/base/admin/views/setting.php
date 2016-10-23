<div class="main-head">
    <h3>基本设置</h3>
</div>
<form class="ajaxform" method="post">
<table cellspacing="0">
  <tbody>
  <tr class="th">
    <th>系统变量</th><th>说明</th><th>设置</th>
  </tr>
  
  <tr>
    <td>
    site_title
    </td>
    <td>
      网站名
    </td>
    <td>
      <input type="text" size="30" name="setting[site_title]" value="<?php echo arrval($settings_info,'site_title');?>" />
    </td>
  </tr>
  <tr>
    <td>
    site_sub_title
    </td>
    <td>
      网站小标题
    </td>
    <td>
      <input type="text" size="30" name="setting[site_sub_title]" value="<?php echo arrval($settings_info,'site_sub_title');?>" />
    </td>
  </tr>
  <tr>
    <td>
    site_seo_title
    </td>
    <td>
      网站SEO标题
    </td>
    <td>
      <input type="text" size="30" name="setting[site_seo_title]" value="<?php echo arrval($settings_info,'site_seo_title');?>" />
    </td>
  </tr>
  <tr>
    <td>
    site_seo_keywords
    </td>
    <td>
      网站SEO关键字
    </td>
    <td>
      <input type="text" size="30" name="setting[site_seo_keywords]" value="<?php echo arrval($settings_info,'site_seo_keywords');?>" />
    </td>
  </tr>
  <tr>
    <td>
    site_seo_description
    </td>
    <td>
      网站SEO描述
    </td>
    <td>
      <textarea cols="50" rows="3" name="setting[site_seo_description]"><?php echo arrval($settings_info,'site_seo_description');?></textarea>
    </td>
  </tr>
  <tr>
    <td>
    default_user_level
    </td>
    <td>
      默认用户注册级别
    </td>
    <td>
      <input type="text" size="30" name="setting[default_user_level]" value="<?php echo arrval($settings_info,'default_user_level');?>" />
    </td>
  </tr>
  <tr>
    <td>
    default_company_tpl
    </td>
    <td>
      默认企业模版的ID
    </td>
    <td>
      <input type="text" size="30" name="setting[default_company_tpl]" value="<?php echo arrval($settings_info,'default_company_tpl');?>" />
    </td>
  </tr>
  <tr>
    <td>
    icp
    </td>
    <td>
      icp备案
    </td>
    <td>
      <input type="text" size="30" name="setting[icp]" value="<?php echo arrval($settings_info,'icp');?>" />
    </td>
  </tr>
  <tr>
    <td>
    stats
    </td>
    <td>
      统计代码
    </td>
    <td>
      <textarea cols="50" rows="3" name="setting[stats]"><?php echo arrval($settings_info,'stats');?></textarea>
    </td>
  </tr>
  <tr>
    <td>
    html_cache_time
    </td>
    <td>
      页面缓存时间
    </td>
    <td>
      <input type="text" size="30" name="setting[html_cache_time]" value="<?php echo arrval($settings_info,'html_cache_time');?>" />
    </td>
  </tr>
  <tr>
    <td>
    enable_wap
    </td>
    <td>
      自动启用手机站
    </td>
    <td>
      <label><input type="radio" name="setting[enable_wap]" value="1" <?php if($settings_info['enable_wap']){ echo 'checked="checked"';} ?> /> 开启</label>
      <label><input type="radio" name="setting[enable_wap]" value="0" <?php if(!$settings_info['enable_wap']){ echo 'checked="checked"';} ?> /> 关闭</label>
    </td>
  </tr>
  <tr>
    <td>
    enable_rewrite
    </td>
    <td>
      启用地址重写
    </td>
    <td>
      <label><input type="radio" name="setting[enable_rewrite]" value="1" <?php if($settings_info['enable_rewrite']){ echo 'checked="checked"';} ?> /> 开启</label>
      <label><input type="radio" name="setting[enable_rewrite]" value="0" <?php if(!$settings_info['enable_rewrite']){ echo 'checked="checked"';} ?> /> 关闭</label>
    </td>
  </tr>
  <tr>
    <td>
    rewrite_style
    </td>
    <td>
      地址重写风格
    </td>
    <td>
      <select name="setting[rewrite_style]">
        <option value="1" <?php if($settings_info['rewrite_style']==1){ echo 'selected="selected"';} ?>>风格1（app/m/123/type/1）</option>
        <option value="2" <?php if($settings_info['rewrite_style']==2){ echo 'selected="selected"';} ?>>风格2（app/m/?id=123）</option>
        <option value="3" <?php if($settings_info['rewrite_style']==3){ echo 'selected="selected"';} ?>>风格3（app/m/123-type-1）</option>
      </select>
    </td>
  </tr>
  <tr>
    <td>
    enable_route
    </td>
    <td>
      启用自定义路由
    </td>
    <td>
      <label><input type="radio" name="setting[enable_route]" value="1" <?php if($settings_info['enable_route']){ echo 'checked="checked"';} ?> /> 开启</label>
        <label><input type="radio" name="setting[enable_route]" value="0" <?php if(!$settings_info['enable_route']){ echo 'checked="checked"';} ?> /> 关闭</label>
    </td>
  </tr>
</tbody>
</table>

<div>
<input type="submit" class="submit-btn" value="保存修改" />
</div>
</form>