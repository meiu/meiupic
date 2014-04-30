<link rel="stylesheet" href="<?php echo S('base','admin/codemirror/lib/codemirror.css');?>">
<script src="<?php echo S('base','admin/codemirror/core.js');?>"></script>
<a href="<?php echo U('base','template','a=filelist&theme='.$file_info['theme'].'&dir='.dirname($file_info['file'])) ?>" >&lt;&lt;返回</a><br>
<form id="submit_form" class="ajaxform" method="post">
    <input type="hidden" name="file" value="<?php echo $file_info['file'];?>" />
    <div class="text_label">
        文件内容：
    </div>
    <div class="text_ipt">
    <textarea class="code" id="code" name="code"><?php echo $code;?></textarea>
    </div>
    <div><br>
    <input type="submit" class="submit-btn" value="保存修改" />
    </div>
</form>
<script type="text/javascript">
var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
    lineNumbers: true,
    matchBrackets: true,
    mode: "<?php
     if($fileext == 'js'){
        echo 'text/javascript';
     }elseif($fileext == 'css'){
        echo 'text/css';
     }elseif($fileext == 'html'){
        echo 'text/html';
     }else{
        echo 'application/x-httpd-php';
     }
    ?>",
    indentUnit: 4,
    indentWithTabs: true,
    styleActiveLine: true,
    enterMode: "keep",
    tabMode: "shift"
});
</script>