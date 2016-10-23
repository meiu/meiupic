<div class="main-head">
    <h3>积分设置列表</h3>
    <a href="<?php echo U('user','point','a=add');?>" class="i-add">新增设置</a>
</div>
<table cellspacing="0">
  <tbody>
  <tr class="th">
    <th>编号</th><th>积分说明</th><th>key</th><th>积分数</th><th>增加/减少</th><th>操作</th>
  </tr>
  <?php
  foreach ($rows as $key => $value): ?>
  <tr <?php if($key%2==0):?>class="odd"<?php endif;?>> 
    <td>
      <?php echo $value['id']; ?>
    </td>
    <td>
      <?php echo $value['name']; ?>
    </td>
    <td>
      <?php echo $value['pointkey']; ?>
    </td>
    <td>
      <?php echo $value['points']; ?>
    </td>
    <td>
      <?php echo $value['ac']==0?"增加":"减少"; ?>
    </td>
    <td> 
        <a href="<?php echo U('user','point','a=edit&id='.$value['id']);?>" title="编辑"><img src="<?php echo S('base','admin/images/b-edit.png')?>" alt="编辑"></a>
        <a href="<?php echo U('user','point','a=del&id='.$value['id']);?>" onclick="return del_one(this,'确定要删除该项么？');" title="删除"><img src="<?php echo S('base','admin/images/b-del.png')?>" alt="删除"></a>
    </td> 
  </tr>
  <?php endforeach ?>
</tbody>
</table>
<?php echo $pagestr; ?>