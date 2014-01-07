<div class="notice">
  TIPS:您可以点击“+”号将应用设置为菜单项。
</div>
<div class="applist cl">
    <?php foreach ($applist as $key => $value): ?>
    <div class="item">
        <a class="addtomenu" title="添加到菜单" href="javascript:void(0)" onclick="addToMenu('<?php echo $value['id']; ?>','<?php echo $value['name'];?>')">+</a>
        <div class="thumb">
          <a href="<?php echo U($value['id'],'index');?>">
            <img src="<?php echo U('base','apps','a=icon&id='.$value['id']);?>" align="absmiddle" />
          </a>
        </div>
        <div class="title"><?php echo $value['name'];?></div>
    </div>
    <?php endforeach ?>
</div>
<script>
function addToMenu(appid,name){
  $.post('<?php echo U('base','apps','a=addmenu');?>',{appid:appid,name:name},function(data){
    art.dialog.tips('添加成功！',0.5,false);
  });
}
</script>