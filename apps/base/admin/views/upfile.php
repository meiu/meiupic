<!doctype html>
<html lang="en">
<head>
<title>上传</title>
<link rel="stylesheet" href="<?php echo S('base','admin/css/admin.css'); ?>" type="text/css" media="all" />
<link rel="stylesheet" href="<?php echo S('base','admin/css/upload.css'); ?>" type="text/css" media="all" />
<script src="<?php echo S('base','js/jquery.min.js');?>"></script>
<script type="text/javascript" src="<?php echo S('base','plupload/plupload.full.js'); ?>"></script>
<script type="text/javascript" src="<?php echo S('base','plupload/jquery.plupload.queue/jquery.plupload.queue.js'); ?>"></script>
<link rel="stylesheet" href="<?php echo S('base','plupload/jquery.plupload.queue/css/jquery.plupload.queue.css'); ?>" type="text/css" />
<script type="text/javascript">
function tab(h,con){
    var _tab=$(h);
    var _box=$(con);
    var _hover='active';
    var _index;
    _tab.click(function(){
        _index=_tab.index(this);
        $(this).addClass(_hover).siblings().removeClass(_hover);
        _box.eq(_index).show().siblings().hide();
    }).eq(0).click();
}
function setIframe(target,url){
    $(target).attr('src',url);
}

function file_pick(obj){
    var src = $(obj).children("img").attr("path");
    var filename = $(obj).attr('title');
    var fileid = $(obj).children("img").attr('fileid');

    if($(obj).hasClass('on')){
        $(obj).removeClass("on");
        var imgstr = $("#att-path").html();
        var length = $("a[class='on']").children("img").length;
        var strs = ids = filenames = '';
        for(var i=0;i<length;i++){
            ids += '|'+$("a[class='on']").children("img").eq(i).attr('fileid');
            strs += '|'+$("a[class='on']").children("img").eq(i).attr('path');
            filenames += '|'+$("a[class='on']").children("img").eq(i).attr('title');
        }
        $('#att-ids').html(ids);
        $('#att-path').html(strs);
        $('#att-name').html(filenames);
    } else {
        var num = $('#att-path').html().split('|').length;
        var file_upload_limit = '<?php echo $num;?>';
        if(num > file_upload_limit) {alert('不能选择超过'+file_upload_limit+'个附件'); return false;}
        $(obj).addClass("on");
        $('#att-ids').append('|'+fileid);
        $('#att-path').append('|'+src);
        $('#att-name').append('|'+filename);
    }
}

function editorSeleted(){
    var ids = $('#att-ids').html();
    var paths = $('#att-path').html();
    var names = $('#att-name').html();
    var idarr = ids.split('|');
    var patharr = paths.split('|');
    var namearr = names.split('|');
    var ret = new Array;
    for(var i in patharr){
        if(patharr[i]!='')
            ret.push({"id":idarr[i],"path":patharr[i],"name":namearr[i]});
    }
    if(patharr.length > 1){
        window.opener.CKEDITOR.tools.callFunction( '<?php echo $CKEditorFuncNum;?>', '<?php echo $upload_dir;?>'+ret[0].path );
        window.close();
    }else{
        alert('您还没有选择上传的文件！');
    }
    return true;
}
</script>
</head>

<body>
<script type="text/javascript">
var sel_files = new Array();
var sel_uploads = new Array();
var file_limit = <?php echo $num;?>;

function initPlupload(){
    $("#muilti_uploader").pluploadQueue({
        runtimes : 'html5,flash',
        url : "<?php echo U('base','upfile','a=uploadprocess');?>",
        max_file_size : "<?php echo $filetype['max'];?>",
        chunk_size : '1mb',
        unique_names : true,
        filters : [
            {title : "<?php echo $filetype['title'];?>", extensions : "<?php echo $filetype['ext'];?>"}
        ],
        <?php /*if($num): ?>
        init : {
              FilesAdded: function(up, files) {
                plupload.each(files, function(file) {
                  if (up.files.length > file_limit) {
                    up.removeFile(file);
                  }
                });
                if (up.files.length >= file_limit) {
                  $('#muilti_uploader_browse').hide();
                }
              },
              FilesRemoved: function(up, files) {
                if (up.files.length < file_limit) {
                  $('#muilti_uploader_browse').fadeIn('slow');
                }
              }
            },
            <?php if($num==1): ?>
            multi_selection: false,
            <?php endif; ?>
        <?php endif;*/ ?>
        flash_swf_url : '<?php echo S("base","plupload/plupload.flash.swf");?>'
    });
    var uploader = $('#muilti_uploader').pluploadQueue();
    var usubmited = 0;
    if(uploader){
        uploader.bind('StateChanged', function() {
            if (uploader.files.length === (uploader.total.uploaded + uploader.total.failed) && !usubmited) {
                usubmited = 1;
                $.post($('#uploadform').attr('action'),$('#uploadform').serializeArray(),function(data){
                    $('#muilti_uploader').html(data);
                });
            }
        });
    }
}


$(function() {
    plupload.addI18n({
        'Filename' : '文件名',
        'Status' : '状态',
        'Size' : '大小',
        'Add files' : '添加文件',
        'Stop current upload' : '停止上传',
        'Start uploading queue' : '开始上传',
        'Start upload' : '开始上传',
        'Uploaded %d/%d files':'已上传 %d/%d 文件',
        'Drag files here.' : '拖拽文件至此处.',
        'File extension error.': '文件类型错误.',
        'File size error.': '文件大小错误.',
        'Error: Invalid file extension: ':'错误的文件类型：',
        'Error: File too large: ':'文件太大：'
    });
    
    initPlupload();
});
</script>
<div id="att-ids" style="display:none;"></div>
<div id="att-path" style="display:none;"></div>
<div id="att-name" style="display:none;"></div>

    <div id="filemanager" class="box_container">
        <div class="tab">
            <ul>
                <li class="active"><span>上传文件</span></li>
                <li onclick="setIframe('#filelist','<?php echo U('base','upfile','a=filelist&type='.$type.'&num='.$num);?>');"><span>文件库</span></li>
                <?php if($hasnotused):?>
                <li onclick="setIframe('#notused','<?php echo U('base','upfile','a=notused&type='.$type.'&num='.$num);?>');"><span>未处理文件</span></li>
                <?php endif;?>
            </ul>
        </div>
        <div class="tab_con">
            <div class="tab_c">
                <form id="uploadform" action="<?php echo U('base','upfile','a=savefiles&type='.$type.'&num='.$num);?>" method="post">
                    <div class="tips_info">
                        <p><?=$filetype['memo']?></p>
                    </div>
                    <div id="muilti_uploader"></div>
                </form>
            </div>
            <div class="tab_c" style="display:none;">
                <iframe id="filelist" src="about:blank" border="0" frameborder="0" width="490" height="280"></iframe>
            </div>
        </div>
        <?php if($CKEditor): ?>
        <div class="editor_buttons">
            <input type="button" class="btn" value="确定" onclick="editorSeleted()" />
            <input type="button" class="btn" value="取消" onclick="window.close()" />
        </div>
        <?php endif; ?>
    </div>

<script type="text/javascript">
    tab('#filemanager .tab li','#filemanager .tab_c');
</script>
</body>
</html>