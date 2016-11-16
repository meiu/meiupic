<script src="<?php echo S('base','ckeditor/ckeditor.js');?>"></script>
<div class="main-head">
    <h3>添加/修改全局标签</h3>
    <a href="<?php echo U('base','template','a=label');?>" >&lt;&lt;返回</a>
</div>
<form id="submit_form" class="ajaxform" method="post">
    <table cellspacing="0" cellpadding="0" border="0" class="edit_table">
                <tbody><tr>
                    <th>标签名：<span class="red">*</span></th>
                    <td><input type="text" name="name" size="20" value="<?php echo $label_info['name'];?>" /></td>
                </tr>
                <tr>
                    <th>使用纯文本：</th>
                    <td><input type="checkbox" name="pure_txt" value="1" <?php if($label_info['pure_txt']){ echo 'checked="checked"';}?>></td>
                </tr>
                <tr id="mtxtarea">
                    <th>内容： <span class="red">*</span></th>
                    <td><textarea id="data" name="data" class="ckeditor" rows="10" cols="90"><?php  echo $label_info['data'];?></textarea></td>
                </tr>
                <tr id="ptxtarea">
                    <th>内容： <span class="red">*</span></th>
                    <td><textarea name="data_pure" rows="10" cols="90"><?php  echo $label_info['data'];?></textarea></td>
                </tr>
                <tr>
                    <th></th>
                    <td class="submitline"><input type="submit" value="保存修改" class="submit-btn"></td>
                </tr>
            </tbody>
    </table>
</form>
<script>
function showTxtarea(){
    var pure=$('input[name="pure_txt"]:checked').val();
    if(pure){
        $('#mtxtarea').hide();
        $('#ptxtarea').show();
    }else{
        $('#mtxtarea').show();
        $('#ptxtarea').hide();
    }
}
$('input[name="pure_txt"]').click(function(){
    showTxtarea();
});
showTxtarea();
</script>