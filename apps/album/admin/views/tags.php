<div class="main-head">
    <h3>标签列表</h3>
    <a href="<?php echo U('album','tags','a=add');?>" class="i-add">新增标签</a>
</div>
<table cellspacing="0">
  <tbody>
  <tr class="th">
    <th>编号</th><th>图片</th><th>名称</th><th>相册数</th><th>图片数</th><th>是否推荐</th><th>排序</th><th>操作</th>
  </tr>
  <?php
  foreach ($rows as $key => $value): ?>
  <tr <?php if($key%2==0):?>class="odd"<?php endif;?>> 
    <td>
      <?php echo $value['id']; ?>
    </td>
    <td>
         <img alt="<?php echo $value['name'];?>" width="80" src="<?php echo thumb($value['image'],180,180,2);?>"></a>
    </td>
    <td>
      <a href="<?php echo U('album','tags','a=edit&id='.$value['id']);?>" title="编辑"><?php echo $value['name']; ?></a>
    </td>
    <td><?php echo $value['album_num']; ?></td>
    <td><?php echo $value['photo_num']; ?></td>
    <td><?php echo $value['recommended']?'是':'否'; ?></td>
    <td><?php echo $value['sort']; ?></td>
    <td> 
        <a href="<?php echo U('album','tags','a=edit&id='.$value['id']);?>" title="编辑"><img src="<?php echo S('base','admin/images/b-edit.png')?>" alt="编辑"></a>
    </td> 
  </tr>
  <?php endforeach ?>
</tbody>
</table>

<?php echo $pagestr; ?>