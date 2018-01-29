<div class="main-head">
    <h3>设置默认应用</h3>
</div>
<div class="notice">
  TIPS:您可以设置前台首页默认打开的app
</div>
<form class="ajaxform" method="post">
<table cellspacing="0">
  <tbody>
  <tr class="th">
    <th width="30"></th><th>APP</th>
  </tr>
  <?php foreach($applist as $value): ?>
  <tr>
    <td>
    <input type="radio" name="default_app" value="<?php echo $value['id'];?>" <?php if($default_app == $value['id']): ?>checked="checked"<?php endif; ?> />
    </td>
    <td>
      <?php echo $value['name'];?>
    </td>
  </tr>
  <?php endforeach; ?>
</tbody>
</table>

<div>
<input type="submit" class="submit-btn" value="保存修改" />
</div>
</form>