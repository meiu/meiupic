<!doctype html>
<html lang="en">
<head>
<title>上传</title>
<link rel="stylesheet" href="<?php echo S('base','admin/css/upload.css'); ?>" type="text/css" media="all" />
<script src="<?php echo S('base','js/jquery.min.js');?>"></script>
<script src="<?php echo S('base','datepicker/WdatePicker.js');?>"></script>
<script type="text/javascript">
$(document).ready(function(){
    set_status_empty();
}); 
function set_status_empty(){
    parent.window.$('#att-ids').html('');
    parent.window.$('#att-path').html('');
    parent.window.$('#att-name').html('');
}
function file_pick(obj){
    var src = $(obj).children("img").attr("path");
    var filename = $(obj).attr('title');
    var fileid = $(obj).children("img").attr('fileid');

    if($(obj).hasClass('on')){
        $(obj).removeClass("on");
        var imgstr = parent.window.$("#att-path").html();
        var length = $("a[class='on']").children("img").length;
        var strs = ids = filenames = '';
        for(var i=0;i<length;i++){
            ids += '|'+$("a[class='on']").children("img").eq(i).attr('fileid');
            strs += '|'+$("a[class='on']").children("img").eq(i).attr('path');
            filenames += '|'+$("a[class='on']").eq(i).attr('title');
        }
        parent.window.$('#att-ids').html(ids);
        parent.window.$('#att-path').html(strs);
        parent.window.$('#att-name').html(filenames);
    } else {
        var num = parent.window.$('#att-path').html().split('|').length;
        var file_upload_limit = '<?php echo $num;?>';
        if(num > file_upload_limit) {alert('不能选择超过'+file_upload_limit+'个附件'); return false;}
        $(obj).addClass("on");
        parent.window.$('#att-ids').append('|'+fileid);
        parent.window.$('#att-path').append('|'+src);
        parent.window.$('#att-name').append('|'+filename);
    }
}

</script>
</head>

<body style="margin:0;padding:0;">
<div class="filesearch">
    <form method="post">
    文件名： <input name="name" class="txt" value="<?php echo $name;?>" size="16" /> 日期： <input name="date" class="txt Wdate" value="<?php echo $date;?>" size="12" autocomplete="off" onClick="WdatePicker()" /> <input name="submit" class="submit" type="submit" value="搜索" />
    </form>
</div>
<div class="filelist">
    <ul>
        <?php foreach ($rows as $key => $value): ?>
        <li>
            <a href="javascript:void(0)" onclick="file_pick(this)" title="<?php echo $value['name'];?>">
            <div class="icon"></div>
            <img <?php if($value['filetype']=='image' || $value['filetype']=='watermark'): ?>
            src="<?php echo D($value['path']);?>" width="80"
            <?php else: ?>
            src="<?php echo S('base','images/mime/'.$value['ext'].'.png');?>" height="60"
            <?php endif; ?>
             fileid="<?php echo $value['id'];?>" path="<?php echo $value['path'];?>" />
            </a>
        </li>
        <?php endforeach ?>
    </ul>
    <?php echo $pagestr; ?>
</div>
</body>
</html>