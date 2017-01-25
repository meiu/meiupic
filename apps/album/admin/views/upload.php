<!doctype html>
<html lang="en">
<head>
<title>上传</title>
<link rel="stylesheet" href="<?php echo S('base','admin/css/admin.css'); ?>" type="text/css" media="all" />
<script src="<?php echo S('base','js/jquery.min.js');?>"></script>
<script src="<?php echo S('base','dialog/jquery.artDialog.js?skin=blue');?>"></script>
<script src="<?php echo S('base','dialog/plugins/iframeTools.js');?>"></script>
<script type="text/javascript" src="<?php echo S('base','plupload/plupload.full.js'); ?>"></script>
<script type="text/javascript" src="<?php echo S('base','plupload/jquery.plupload.queue/jquery.plupload.queue.js'); ?>"></script>
<link rel="stylesheet" href="<?php echo S('base','plupload/jquery.plupload.queue/css/jquery.plupload.queue.css'); ?>" type="text/css" />
<style>
	html{background: #fff}
	#filemanager{background: #fff;height: 390px;}
	#filemanager .tips_info p{
	    padding:2px 10px;
	    font-size:12px;
	    color:green;
	}
	.plupload_scroll .plupload_filelist{
		height: 275px;
	}
</style>
</head>

<body>
<script type="text/javascript">
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
        flash_swf_url : '<?php echo S("base","plupload/plupload.flash.swf");?>'
    });
    var uploader = $('#muilti_uploader').pluploadQueue();
    var usubmited = 0;
    if(uploader){
        uploader.bind('StateChanged', function() {
            if (uploader.files.length === (uploader.total.uploaded + uploader.total.failed) && !usubmited) {
                usubmited = 1;
                art.dialog.tips('图片处理中...',100,true);
                $('#uploadform').submit();
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
    <div id="filemanager" class="box_container">
        <form id="uploadform" action="<?php echo U('album','upload','a=save&aid='.$aid);?>" method="post">
            <div class="tips_info">
                <p><?=$filetype['memo']?></p>
            </div>
            <div id="muilti_uploader"></div>
        </form>
    </div>
</body>
</html>