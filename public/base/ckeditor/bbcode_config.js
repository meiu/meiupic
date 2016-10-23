CKEDITOR.editorConfig = function( config ) {
    config.height = 220;
    config.toolbar = [
        [ 'Find', 'Replace', '-', 'SelectAll', 'RemoveFormat', '-', 'Undo', 'Redo' ],
        [ 'Link', 'Unlink', 'Image' ],
        [ 'FontSize', 'Bold', 'Italic', 'Underline' ,'-','NumberedList', 'BulletedList', '-', 'Blockquote' ],
        [ 'TextColor', '-', 'Smiley', 'SpecialChar',  ],
        [ 'Source', '-', 'Save', 'NewPage','-', 'Maximize' ]
    ];

    config.removeButtons = 'Underline,Subscript,Superscript';
    config.removePlugins = 'elementspath';
    config.extraPlugins  = 'bbcode';

    config.removeDialogTabs = 'image:advanced;link:advanced';
    config.image_previewText = '您正在使用MeiuPic,MeiuPic是一款智能的PHP系统,包含相册管理、评论、用户系统等功能。这里是图片预览区域！';
    config.filebrowserWindowHeight = '470';
    config.filebrowserWindowWidth = '520';
    config.filebrowserBrowseUrl = '';
    config.filebrowserFlashBrowseUrl = '';
    config.filebrowserImageBrowseUrl = '';
};
