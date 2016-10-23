/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

/**
 * @fileOverview The "elementspath" plugin. It shows all elements in the DOM
 *		parent tree relative to the current selection in the editing area.
 */

( function() {
	CKEDITOR.plugins.add( 'kwords', {
		lang: 'en,zh-cn', 
		init: function( editor ) {
			editor._.kwords = {
				idBase: 'cke_kwords_' + CKEDITOR.tools.getNextNumber() + '_',
				filters: []
			};
			editor.on( 'uiSpace', function( event ) {
				if ( event.data.space == 'bottom' ){
					var spaceId = editor.ui.spaceId( 'kwords' );
					event.data.html += '<a id="' + spaceId + '" class="cke_cupload" role="group" aria-labelledby="' + spaceId + '_label">' + editor.lang.kwords.btnLabel + '</a>';

					editor.on( 'uiReady', function() {

						document.getElementById(spaceId).onclick = function(){
							var mySelection=editor.getSelection();
							var data;
							if (CKEDITOR.env.ie) {
					            mySelection.unlock(true);
					            data = mySelection.getNative().createRange().text;
					        } else {
					            data = mySelection.getNative();
					        }
					        if(data){
					        	editor.insertHtml('[kw]'+data+'[/kw]');
					        }
						};
					});
					
				}
			} );
		}
	} );
} )();