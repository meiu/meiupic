<div class="main-head">
    <h3>添加/编辑积分设置</h3>
    <a href="<?php echo U('user','point');?>" >&lt;&lt;返回</a><br>
</div>
 
<div class="notice">
  注：调用方式<span class="c_y">app('user')->doPoints($uid,key)</span>
</div>
<form id="submit_form" class="ajaxform" method="post">
    <table class="edit_table">
        <tr>
            <th>积分Key：</th>
            <td>
                <input type="text" name="pointkey" size="40" value="<?php echo $info['pointkey'];?>" />
            </td>
        </tr>
        <tr>
            <th>积分说明：</th>
            <td>
                <input type="text" name="name" size="40" value="<?php echo $info['name'];?>" />
            </td>
        </tr>
        <tr>
            <th>积分数：</th>
            <td>
                <input type="text" name="points" size="20" value="<?php echo $info['points'];?>" />
            </td>
        </tr>
        <tr>
            <th>增加或减少：</th>
            <td>
                 <select name="ac">
                  <option value="0" <?php if(!$info['ac']){ echo 'checked="checked"';} ?>>增加</option>
                  <option value="1" <?php if($info['ac']){ echo 'checked="checked"';} ?>>减少</option>
                </select>
            </td>
        </tr>
        <tr>
            <th></th>
            <td class="submitline">
                <input type="submit" class="submit-btn" value="保存" />
            </td>
        </tr>
    </table>
</form>