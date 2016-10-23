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
                    <th>内容： <span class="red">*</span></th>
                    <td><textarea id="data" name="data" class="ckeditor" rows="10" cols="90"><?php  echo $label_info['data'];?></textarea></td>
                </tr>
                <tr>
                    <th></th>
                    <td class="submitline"><input type="submit" value="保存修改" class="submit-btn"></td>
                </tr>
            </tbody>
    </table>
    
</form>