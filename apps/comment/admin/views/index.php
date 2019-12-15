<script type="text/javascript">
function multi_del(){
    var seled = $(".id_sel:checked");

    if(seled.length == 0){
      art.dialog.alert('请先选择要删除的评论！');
      return;
    }

    art.dialog.confirm('您是要删除选定项么？', function () {
      var idarr = seled.serialize();
      $.post("<?php echo U('comment','index','a=del&isajax=1');?>",idarr,function(data){
        ajaxAlert(data,0.5,false);
      },'json');
    });
}

function multi_audit(status){
    var seled = $(".id_sel:checked");
    if(seled.length == 0){
      art.dialog.alert('请先选择要审核的评论！');
      return;
    }

    art.dialog.confirm('您是要审核选定项么？', function () {
      var idarr = seled.serialize();
      $.post("<?php echo U('comment','index','a=audit&isajax=1');?>&status="+status,idarr,function(data){
        ajaxAlert(data,0.5,false);
      },'json');
    });
}
</script>
<div class="main-head">
    <h3>评论列表</h3>
</div>
<div class="taba">
    <ul class="cl">
      <li <?php if(!$search['tab']): ?>class="active"<?php endif; ?>><a href="<?php echo U('comment','index');?>">全部</a></li>
      <li <?php if($search['tab']==1): ?>class="active"<?php endif; ?>><a href="<?php echo U('comment','index','tab=1');?>">待审核</a></li>
      <li <?php if($search['tab']==2): ?>class="active"<?php endif; ?>><a href="<?php echo U('comment','index','tab=2');?>">已通过</a></li>
      <li <?php if($search['tab']==3): ?>class="active"<?php endif; ?>><a href="<?php echo U('comment','index','tab=3');?>">未通过</a></li>
    </ul>
</div>
<div class="search"><!--搜索-->
    <form method="post">
      模块：<input type="text" size="12" name="mod" value="<?php echo $search['mod'];?>" />
      所属ID：<input type="text" size="12" name="rel_id" value="<?php echo $search['rel_id'];?>" />
      内容：<input type="text" name="keyword" value="<?php echo $search['keyword'];?>" />
      <input type="submit" class="submit-btn" value="搜索" />
    </form>
  </div>
<table cellspacing="0">
  <tbody>
  <tr class="th">
    <th width="20"><input type="checkbox" onclick="$('.id_sel').attr('checked',this.checked);" /></th>
    <th width="60">编号</th>
    <th width="80">发布人</th>
    <th width="140">时间</th>
    <th width="120">所属模块</th>
    <th width="80">所属ID</th>
    <th width="100">审核状态</th>
    <th>内容</th>
  </tr>
  <?php
  foreach ($rows as $key => $value): ?>
  <tr <?php if($key%2==0):?>class="odd"<?php endif;?>> 
    <td width="20">
      <input class="id_sel" name="ids[]" type="checkbox" value="<?php echo $value['id']; ?>" />
    </td> 
    <td>
      <?php echo $value['id']; ?>
    </td>
    <td>
      <?php echo $value['author']; ?>
    </td>
    <td>
      <?php echo date('Y-m-d H:i',$value['addtime']); ?>
    </td>
    <td>
      <?php echo $value['mod']; ?>
    </td>
    <td>
      <?php echo $value['rel_id']; ?>
    </td>
    <td>
      <?php echo echoType($value['status'],array('待审核','审核通过','审核不通过')); ?>
    </td>
    <td class="smallfont">
      <?php echo $value['content']; ?>
    </td>
  </tr>
  <?php endforeach ?>
</tbody>
</table>
<div class="bottom_bar">
  操作:选中项 <a href="javascript:void(0)" onclick="multi_del()">删除</a> , <a href="javascript:void(0)" onclick="multi_audit(1)">审核通过</a> , <a href="javascript:void(0)" onclick="multi_audit(2)">审核不通过</a> 
</div>
<?php echo $pagestr; ?>