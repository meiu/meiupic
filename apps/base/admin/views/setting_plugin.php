<form id="submit_form" class="ajaxform" method="post" action="">

<div class="taba">
    <ul class="cl">
      <li <?php if(!$pluginapp): ?>class="active"<?php endif; ?>><a href="<?php echo U('base','setting','a=plugin');?>">所有</a></li>
      <?php foreach ($appList as $value): ?>
        <li <?php if($pluginapp == $value): ?>class="active"<?php endif; ?>><a href="<?php echo U('base','setting','a=plugin&pluginapp='.$value);?>"><?php echo $value.($value=='pub'?'(公共)':'');?></a></li>
      <?php endforeach ?>
    </ul>
</div>
<table cellspacing="0">
  <tbody>
  <tr class="th">
    <th>插件名称</th><th>版本</th><th>作者/介绍</th><th>状态</th><th>操作</th>
  </tr>
  <?php
  $i = 0;
  if(!empty($pluginList)):
  foreach ($pluginList as $value): 
  ?>
  <tr <?php if($i%2==1):?>class="odd"<?php endif;?>>
    <td>
      <?php echo $value['Name']; ?>
    </td>
    <td>
      <?php echo $value['Version']; ?>
    </td>
    <td>
      <?php echo $value['Author'].'/'.$value['Description']; ?>
    </td>
    <td>
      <?php if(isset($activedPlugins[$value['App']]) && in_array($value['Plugin'], $activedPlugins[$value['App']])): ?>
        启用 (<a href="javascript:void(0)" onclick="switchActive('<?php echo U('base','setting','a=pluginactive&status=inactive&pluginapp='.$value['App'].'&plugin='.$value['Plugin']);?>',0)">停用</a>)
      <?php else: ?>
        停用 (<a href="javascript:void(0)" onclick="switchActive('<?php echo U('base','setting','a=pluginactive&status=active&pluginapp='.$value['App'].'&plugin='.$value['Plugin']);?>',1)">启用</a>)
      <?php endif; ?>
    </td>
    <td>
    <?php if($value['Setting']):?>
    <a href="<?php echo U('base','setting','a=pluginset&pluginapp='.$value['App'].'&plugin='.$value['Plugin']); ?>">设置</a>
    <?php endif;?>
    </td>
  </tr>
  <?php 
  $i++;
  endforeach;
  endif; ?>
</tbody>
</table>
</form>

<script type="text/javascript">
function switchActive(url,s){
    art.dialog.confirm( (s==1?'启用该插件？':'确定要停用该插件？'), function () {
        $.post(url,{_:Math.random(),'ajaxform':1},function(data){
            ajaxAlert(data,1,false);
        },'json');
    }, function () {
    });
    return false;
}
</script>