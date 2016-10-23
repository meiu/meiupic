
<div class="main-head">
    <h3>扩展字段</h3>
</div>
<form class="ajaxform" method="post">
<table cellspacing="0">
  <tbody>
  <tr class="th">
    <th width="20"></th><th>扩展字段</th><th>别名</th><th>是否显示</th>
  </tr>
  <?php
  $i = 0;
  for($i=1;$i<=8;$i++): ?>
  <tr <?php if($i%2==1):?>class="odd"<?php endif;?>> 
    <td>
      
    </td>
    <td>
      extra<?php echo $i;?>
    </td>
    <td>
      <input type="text" size="30" name="cname[extra<?php echo $i;?>]" value="<?php echo empty($fields['extra'.$i])?'':$fields['extra'.$i]['cname'];?>" />
    </td>
    <td>
      <input type="checkbox" name="show[extra<?php echo $i;?>]" value="1" <?php if(isset($fields['extra'.$i]) && $fields['extra'.$i]['show']){ echo 'checked="checked"';} ?> />
    </td>
  </tr>
  <?php 
  endfor;
  ?>
</tbody>
</table>

<div>
<input type="submit" class="submit-btn" value="保存修改" />
</div>
</form>