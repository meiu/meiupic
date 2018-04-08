/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
    // Define changes to default configuration here.
    // For the complete reference:
    // http://docs.ckeditor.com/#!/api/CKEDITOR.config
    config.height = 260;

    // The toolbar groups arrangement, optimized for two toolbar rows.
    config.toolbarGroups = [
    	{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
        { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
        { name: 'editing',     groups: [ 'find', 'selection', 'spellchecker','pagebreak' ] },
        { name: 'links' },
        { name: 'insert' },
        { name: 'forms' },
        { name: 'tools' },
        { name: 'others' },
        { name: 'document',    groups: [ 'mode', 'document', 'doctools' ] },
        '/',
        { name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
        { name: 'styles' },
        { name: 'colors' },
        { name: 'about' }
    ];

    // Remove some buttons, provided by the standard plugins, which we don't
    // need to have in the Standard(s) toolbar.
    config.removeButtons = 'Underline,Subscript,Superscript,elementspath';
    config.removePlugins = 'elementspath';
    config.extraPlugins  = 'cupload,kwords,pagebreak,syntaxhighlight';
    // Se the most common block elements.
    config.format_tags = 'p;h1;h2;h3;pre';
    
    CKEDITOR.config.font_names = '宋体/"宋体", simson;' +
    '黑体/"黑体", simhei;' +
    '仿宋/"仿宋";' +
    '微软雅黑/"微软雅黑", Microsoft Yahei;' +
    'Arial/Arial, Helvetica, sans-serif;' +
    'Comic Sans MS/Comic Sans MS, cursive;' +
    'Courier New/Courier New, Courier, monospace;' +
    'Georgia/Georgia, serif;' +
    'Lucida Sans Unicode/Lucida Sans Unicode, Lucida Grande, sans-serif;' +
    'Tahoma/Tahoma, Geneva, sans-serif;' +
    'Times New Roman/Times New Roman, Times, serif;' +
    'Trebuchet MS/Trebuchet MS, Helvetica, sans-serif;' +
    'Verdana/Verdana, Geneva, sans-serif';

    // Make dialogs simpler.
    config.removeDialogTabs = 'image:advanced;link:advanced';
    config.image_previewText = '您正在使用MeiuPic,MeiuPic是一款智能的PHP系统,包含相册管理、评论、用户系统等功能。这里是图片预览区域！';
    config.filebrowserWindowHeight = '470';
    config.filebrowserWindowWidth = '520';
    config.filebrowserBrowseUrl = ADMIN_BASE_URL +'?app=base&m=upfile&type=attach&num=1';
    config.filebrowserFlashBrowseUrl = ADMIN_BASE_URL + '?app=base&m=upfile&type=flash&num=1';
    config.filebrowserImageBrowseUrl = ADMIN_BASE_URL + '?app=base&m=upfile&type=image&num=10';
};
