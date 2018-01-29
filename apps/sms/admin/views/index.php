<div class="main-head">
    <h3>短信发送日志</h3>
</div>
<table cellspacing="0">
  <tbody>
  <tr class="th">
    <th>编号</th>
    <th>手机号</th>
    <th>短信内容</th>
    <th>发送状态</th>
    <th>接口返回</th>
    <th>发送时间</th>
  </tr>
  <?php
  foreach ($rows as $key => $value): ?>
  <tr <?php if($key%2==0):?>class="odd"<?php endif;?>> 
    <td>
      <?php echo $value['id']; ?>
    </td>
    <td>
      <?php echo $value['mobile']; ?>
    </td>
    <td>
      <?php echo $value['content'];?>
    </td>
    <td>
      <?php if($value['result']=='success'){echo '成功';}else{ echo '失败';}?>
    </td>
    <td>
      <?php echo $value['result_content']; ?>
    </td>
    <td>
      <?php echo date('Y-m-d H:i',$value['sendtime']); ?>
    </td>
  </tr>
  <?php endforeach ?>
</tbody>
</table>
<?php echo $pagestr; ?>