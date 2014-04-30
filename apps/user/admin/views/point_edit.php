<a href="<?php echo U('user','point');?>" >&lt;&lt;返回</a><br>
 
<div class="notice">
  注：调用方式<span class="c_y">app('user')->doPoints($uid,key)</span>
</div>
<form id="submit_form" class="ajaxform" method="post">
    <div class="text_label">
        积分Key：
    </div>
    <div class="text_ipt">
        <input type="text" name="pointkey" size="40" value="<?php echo $info['pointkey'];?>" />
    </div>
    <div class="text_label">
        积分说明：
    </div>
    <div class="text_ipt">
        <input type="text" name="name" size="40" value="<?php echo $info['name'];?>" />
    </div>
    <div class="text_label">
        积分数：
    </div>
    <div class="text_ipt">
        <input type="text" name="points" size="20" value="<?php echo $info['points'];?>" />
    </div>
    <div class="text_label">
        增加或减少：
    </div>
    <div class="text_ipt">
        <select name="ac">
          <option value="0" <?php if(!$info['ac']){ echo 'checked="checked"';} ?>>增加</option>
          <option value="1" <?php if($info['ac']){ echo 'checked="checked"';} ?>>减少</option>
        </select>
    </div>
    <div><br>
    <input type="submit" class="submit-btn" value="保存" />
    </div>
</form>