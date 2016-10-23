<div class="main-head">
    <h3>后台菜单列表</h3>
</div>
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
    $value['menu'] = $value['app'].'_'.$value['mod'].(isset($value['cid'])?'_'.$value['cid']:'');
  }
  ?>
  <tr <?php if($i%2==1):?>class="odd"<?php endif;?>>
    <td>
      <a href="<?php echo U($value['app'],isset($value['mod'])?$value['mod']:'index',isset($value['cid'])?'cid='.$value['cid']:'');?>"><?php echo $value['name']; ?></a>
    </td>
    <td>
      <?php echo U($value['app'],isset($value['mod'])?$value['mod']:'index',isset($value['cid'])?'cid='.$value['cid']:'');?>
    </td>
    <td>
      <input type="text" size="8" name="sort[<?php echo isset($value['menu'])?$value['menu']:$value['app']; ?>]" value="<?php echo $i; ?>" />
    </td>
    <td>
      <input type="checkbox" name="enable[<?php echo isset($value['menu'])?$value['menu']:$value['app']; ?>]" value="1" <?php if($value['enable']){echo 'checked="checked"';} ?> />
    </td>
    <td>
    <?php if(!$value['fixed']): ?>
      <a href="<?php echo U('base','setting','a=menudel&appid='.$value['app'].(isset($value['mod'])?'&modid='.$value['mod']:'').(isset($value['cid'])?'&cid='.$value['cid']:'')); ?>" onclick="return del_one(this,'确定要删除该项么？');" title="删除"><img src="<?php echo S('base','admin/images/b-del.png')?>" alt="删除"></a>
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