<div class="main-head">
    <h3>分类列表</h3>
    <a href="<?php echo U('album','cate','a=add');?>" class="i-add">添加分类</a>
</div>
<form id="submit_form" class="ajaxform" method="post" >
<table cellspacing="0">
  <tbody>
  <tr class="th">
    <th width="50">编号</th><th>分类名</th><th>是否显示?</th><th>排序</th><th>操作</th><th>前台路径</th>
  </tr>
  <?php
  foreach ($catelist as $key => $value): ?>
  <tr <?php if($key%2==0):?>class="odd"<?php endif;?>> 
    <td><?php echo $value['id'];?></td>
    <td><?php 
    echo $value['spacer']?str_replace('&nbsp;', '&nbsp;&nbsp;&nbsp;', $value['spacer']).'─':'';
    ?> <a href="<?php echo U('album','cate','a=edit&id='.$value['id']);?>" title="编辑"><?php echo $value['name'];?></a></td>

    <td><?php echo echoType($value['status'],array('隐藏','显示'));?></td>
    <td><input type="text" size="8" name="sort[<?php echo $value['id'];?>]" value="<?php echo $value['sort'];?>" /></td>
    <td> 
        <a href="<?php echo U('album','cate','a=edit&id='.$value['id']);?>" title="编辑"><img src="<?php echo S('base','admin/images/b-edit.png')?>" alt="编辑"></a>
        <a href="<?php echo U('album','cate','a=del&id='.$value['id']);?>" onclick="return del_one(this,'确定要删除该分类么，删除后该分类的图片将无法显示？');" title="删除"><img src="<?php echo S('base','admin/images/b-del.png')?>" alt="删除"></a>
        <a href="<?php echo U('album','cate','a=add&pid='.$value['id']);?>" title="添加子分类"><img src="<?php echo S('base','admin/images/b-add.png')?>" alt="添加子分类"></a>
    </td> 
    <td><?php 
      $linkurl = app('album')->makeCateLink($value);
      echo $linkurl;
      echo ' [<a href="'.$linkurl.'" target="_blank">访问</a>]'
      ?></td>
  </tr>
  <?php endforeach ?>
</tbody>
</table>
<div>
<input type="submit" class="submit-btn" value="保存排序" />
</div>
</form>
<script>
  function set_menu(o){
    var url=$(o).attr('href');
    $.post(url,{_:Math.random(),'ajaxform':1},function(data){
        ajaxAlert(data,1,false);
    },'json');
    return false;
  }
</script>