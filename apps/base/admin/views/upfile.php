<!doctype html>
<html lang="en">
<head>
<title>上传</title>
<link rel="stylesheet" href="<?php echo S('base','admin/css/admin.css'); ?>" type="text/css" media="all" />
<link rel="stylesheet" href="<?php echo S('base','admin/css/upload.css'); ?>" type="text/css" media="all" />
<script src="<?php echo S('base','js/jquery.min.js');?>"></script>
<script src="<?php echo S('base','jquery-ui/jquery-ui.min.js');?>"></script>
<link rel="stylesheet" href="<?php echo S('base','jquery-ui/jquery-ui.min.css');?>" />
<link rel="stylesheet" href="<?php echo S('base','plupload/jquery.ui.plupload/css/jquery.ui.plupload.css'); ?>" type="text/css" />
<script type="text/javascript" src="<?php echo S('base','plupload/plupload.full.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo S('base','plupload/jquery.ui.plupload/jquery.ui.plupload.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo S('base','plupload/i18n/zh_CN.js'); ?>"></script>
<script>
var UPFILE_PRE = "<?php echo C('storage.setting.url_pre'); ?>";

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
        window.opener.CKEDITOR.tools.callFunction( '<?php echo $CKEditorFuncNum;?>', UPFILE_PRE+ret[0].path );
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
        <?php if(stripos($filetype['ext'], 'png')!==false || stripos($filetype['ext'], 'jpg')!==false): ?>
        views: {
            list: true,
            thumbs: true,
            active: 'thumbs'
        },
        <?php else: ?>
        views: {
            list: true,
            thumbs: false,
            active: 'list'
        },
        <?php endif; ?>
        flash_swf_url : '<?php echo S("base","plupload/Moxie.swf");?>',
        silverlight_xap_url : '<?php echo S("base","plupload/Moxie.xap");?>'
    });
    
    $('#muilti_uploader').on('complete', function() {
        $.post($('#uploadform').attr('action'),$('#uploadform').serializeArray(),function(data){
            $('#muilti_uploader').html(data);
        });
    });
}


$(function() {
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
                <?php if($attach):?>
                <li onclick="setIframe('#filelist','<?php echo U('base','upfile','a=filelist&type='.$type.'&num='.$num);?>');"><span>文件库</span></li>
                <?php endif;?>
            </ul>
        </div>
        <div class="tab_con">
            <div class="tab_c">
                <form id="uploadform" action="<?php echo U('base','upfile','a=savefiles&type='.$type.'&num='.$num.'&attach='.$attach);?>" method="post">
                    <div class="tips_info">
                        <p><?=$filetype['memo']?></p>
                    </div>
                    <div id="muilti_uploader"></div>
                </form>
            </div>
            <?php if($attach):?>
            <div class="tab_c" style="display:none;">
                <iframe id="filelist" src="about:blank" border="0" frameborder="0" width="490" height="280"></iframe>
            </div>
            <?php endif;?>
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