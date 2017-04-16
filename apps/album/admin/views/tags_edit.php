<div class="main-head"><h3>添加/编辑标签</h3><a href="<?php echo U('album','tags');?>" >&lt;&lt;返回</a></div>
 
<form id="submit_form" class="ajaxform" method="post">
    <div class="text_label">
        名称：
    </div>
    <div class="text_ipt">
        <input type="text" name="name" size="30" value="<?php echo $item['name'];?>" <?php if($item['name']){echo 'readonly="readonly"';} ?> />
    </div>
    <div class="text_label">
        图片：
    </div>
    <div class="text_ipt">
        <input type="text" size="50" class="picarea" id="image" name="image" value="<?php echo $item['image'];?>" />  <input type="button" class="btn" value="浏览" onclick="viewfile('image','image')" />
    </div>
    <div class="text_label">
        描述：
    </div>
    <div class="text_ipt">
    <textarea name="description" cols="50" rows="3"><?php echo $item['description'];?></textarea>
    </div>
    <div><br>
    <input type="submit" class="submit-btn" value="保存" />
    </div>
</form>
<script>
function viewfile(iptid,type){
  uploadWin.open(type,1,function(vReturnValue){
      $('#'+iptid).val(vReturnValue.path);
  });
}

$(function(){
  picArea();
});
</script>