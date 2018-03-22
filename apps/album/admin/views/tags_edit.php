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
    <div class="text_label">
        是否推荐：
    </div>
    <div class="text_ipt">
        <label><input type="radio" name="recommended" value="1" <?php if($item['recommended']){echo 'checked';} ?> /> 是</label>
        <label><input type="radio" name="recommended" value="0" <?php if(!$item['recommended']){echo 'checked';} ?> /> 否</label>
    </div>
    <div class="text_label">
        排序：
    </div>
    <div class="text_ipt">
        <input type="text" name="sort" size="10" value="<?php echo $item['sort'];?>" /> 数字越大越靠前
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