<!doctype html>
<html lang="en">
<head>
<title>上传</title>
<link rel="stylesheet" href="<?php echo S('base','admin/css/admin.css'); ?>" type="text/css" media="all" />
<script src="<?php echo S('base','js/jquery.min.js');?>"></script>
<script src="<?php echo S('base','dialog/jquery.artDialog.js?skin=blue');?>"></script>
<script src="<?php echo S('base','dialog/plugins/iframeTools.js');?>"></script>
<script src="<?php echo S('base','jquery-ui/jquery-ui.min.js');?>"></script>
<link rel="stylesheet" href="<?php echo S('base','jquery-ui/jquery-ui.min.css');?>" />
<link rel="stylesheet" href="<?php echo S('base','plupload/jquery.ui.plupload/css/jquery.ui.plupload.css'); ?>" type="text/css" />
<script type="text/javascript" src="<?php echo S('base','plupload/plupload.full.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo S('base','plupload/jquery.ui.plupload/jquery.ui.plupload.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo S('base','plupload/i18n/zh_CN.js'); ?>"></script>
<style>
	html{background: #fff}
	#filemanager{background: #fff;height: 390px;}
	#filemanager .tips_info p{
	    padding:2px 10px;
	    font-size:12px;
	    color:green;
	}
	.plupload_container{
		height: 345px;
	}
</style>
</head>

<body>
<script type="text/javascript">
function initPlupload(){
    $("#muilti_uploader").plupload({
        runtimes : 'html5,flash,silverlight,html4',
        url : "<?php echo U('base','upfile','a=uploadprocess');?>",
        chunk_size : '1mb',
        filters : {
            max_file_size : '<?php echo $filetype['max'];?>',
            mime_types: [
                {title : "<?php echo $filetype['title'];?>", extensions : "<?php echo $filetype['ext'];?>"}
            ]
        },
        unique_names:true,
        rename: true,
        sortable: true,
        dragdrop: true,
        views: {
            list: true,
            thumbs: true,
            active: 'thumbs'
        },
        flash_swf_url : '<?php echo S("base","plupload/Moxie.swf");?>',
        silverlight_xap_url : '<?php echo S("base","plupload/Moxie.xap");?>'<?php if(@$_G['settings']['album_resize_img'] && @$_G['settings']['album_pre_resize_img']):?>,
        resize : {width : <?php echo $_G['settings']['album_resize_img_w'];?>, height : <?php echo $_G['settings']['album_resize_img_h'];?>, quality : 90}
        <?php endif; ?>
    });
    
    $('#muilti_uploader').on('complete', function() {
        art.dialog.tips('图片处理中...',100,true);
        $('#uploadform').submit();
    });
}


$(function() {
    initPlupload();
});
</script>
    <div id="filemanager" class="box_container">
        <form id="uploadform" action="<?php echo U('album','upload','a=save&aid='.$aid);?>" method="post">
            <div class="tips_info">
                <p><?php echo $filetype['memo']?></p>
            </div>
            <div id="muilti_uploader"></div>
        </form>
    </div>
</body>
</html>