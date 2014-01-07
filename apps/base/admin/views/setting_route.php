<div class="notice">
  注：路由规则从上往下匹配。如果需要优先匹配某个规则，可以将它的排序提前。
</div>
<div class="toolbar">
    <a href="<?php echo U('base','setting','a=routeadd');?>" class="i-add">新增路由</a>
</div>
<form id="submit_form" class="ajaxform" method="post">
<table cellspacing="0">
  <tbody>
  <tr class="th">
    <th>编号</th><th>路由</th><th>映射参数</th><th>排序</th><th>操作</th>
  </tr>
  <?php
  foreach ($rows as $key => $value): ?>
  <tr <?php if($key%2==0):?>class="odd"<?php endif;?>> 
    <td>
      <?php echo $value['id']; ?>
    </td>
    <td>
      <?php echo $value['route']; ?>
    </td>
    <td>
      <?php echo $value['params']; ?>
    </td>
    <td><input type="text" size="8" name="sort[<?php echo $value['id'];?>]" value="<?php echo $value['sort'];?>" /></td>
    <td> 
        <a href="<?php echo U('base','setting','a=routeedit&id='.$value['id']);?>" title="编辑"><img src="<?php echo S('base','admin/images/b-edit.png')?>" alt="编辑"></a>
        <a href="<?php echo U('base','setting','a=routedel&id='.$value['id']);?>" onclick="return del_one(this,'确定要删除该路由设置么？');" title="删除"><img src="<?php echo S('base','admin/images/b-del.png')?>" alt="删除"></a>
    </td>
  </tr>
  <?php endforeach ?>
</tbody>
</table>
<?php echo $pagestr; ?>
<div>
<input type="submit" class="submit-btn" value="保存排序" />
</div>
</form>