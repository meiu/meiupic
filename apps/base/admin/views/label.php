<div class="main-head">
    <h3>风格列表</h3>
    <a href="<?php echo U('base','template','a=labeladd');?>" class="i-add">新增标签</a>
</div>
<table cellspacing="0">
  <tbody>
  <tr class="th">
    <th>编号</th><th>名称</th><th>操作</th>
  </tr>
  <?php
  foreach ($rows as $key => $value): ?>
  <tr <?php if($key%2==0):?>class="odd"<?php endif;?>> 
    <td>
      <?php echo $value['id']; ?>
    </td>
    <td>
      <a href="<?php echo U('base','template','a=labeledit&id='.$value['id']);?>" title="编辑"><?php echo $value['name']; ?></a>
    </td>
    <td> 
        <a href="<?php echo U('base','template','a=labeledit&id='.$value['id']);?>" title="编辑"><img src="<?php echo S('base','admin/images/b-edit.png')?>" alt="编辑"></a>
        <a href="<?php echo U('base','template','a=labeldel&id='.$value['id']);?>" onclick="return del_one(this,'确定要删除该标签么？');" title="删除"><img src="<?php echo S('base','admin/images/b-del.png')?>" alt="删除"></a>
    </td> 
  </tr>
  <?php endforeach ?>
</tbody>
</table>

<?php echo $pagestr; ?>