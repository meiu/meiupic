<form id="submit_form" class="ajaxform" method="post" action="">
<table cellspacing="0">
  <tbody>
  <tr class="th">
    <th>链接名称</th><th>url地址</th><th>排序</th><th>启用</th><th></th>
  </tr>
  <?php
  $i = 0;
  if(!empty($menulist)):
  foreach ($menulist as $value): 
  if(isset($value['mod'])){
    $value['menu'] = $value['app'].'_'.$value['mod'];
  }
  ?>
  <tr <?php if($i%2==1):?>class="odd"<?php endif;?>>
    <td>
      <?php echo $value['name']; ?>
    </td>
    <td>
      <?php echo U($value['app'],isset($value['mod'])?$value['mod']:'index');?>
    </td>
    <td>
      <input type="text" size="8" name="sort[<?php echo isset($value['menu'])?$value['menu']:$value['app']; ?>]" value="<?php echo $i; ?>" />
    </td>
    <td>
      <input type="checkbox" name="enable[<?php echo isset($value['menu'])?$value['menu']:$value['app']; ?>]" value="1" <?php if($value['enable']){echo 'checked="checked"';} ?> />
    </td>
    <td>
    <?php if(!$value['fixed']): ?>
      <a href="<?php echo U('base','setting','a=menudel&appid='.$value['app']); ?>" onclick="return del_one(this,'确定要删除该项么？');" title="删除"><img src="<?php echo S('base','admin/images/b-del.png')?>" alt="删除"></a>
    <?php endif; ?>
    </td>
  </tr>
  <?php 
  $i++;
  endforeach;
  endif; ?>
</tbody>
</table>
<div><br>
<input type="submit" class="submit-btn" value="保存排序" />
</div>
</form>