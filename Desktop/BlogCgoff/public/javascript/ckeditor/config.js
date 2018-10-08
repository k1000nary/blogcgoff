/**
 * @license Copyright (c) 2003-2018, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	
	// %REMOVE_START%
	// The configuration options below are needed when running CKEditor from source files.
	config.plugins = 'youtubebootstrap,dialogui,dialog,about,a11yhelp,dialogadvtab,basicstyles,bidi,blockquote,notification,button,toolbar,clipboard,panelbutton,panel,floatpanel,colorbutton,colordialog,templates,menu,contextmenu,copyformatting,div,resize,elementspath,enterkey,entities,popup,filetools,filebrowser,find,fakeobjects,flash,floatingspace,listblock,richcombo,font,forms,format,horizontalrule,htmlwriter,iframe,wysiwygarea,indent,indentblock,indentlist,smiley,justify,menubutton,language,link,list,liststyle,magicline,maximize,newpage,pagebreak,pastetext,pastefromword,preview,print,removeformat,save,selectall,showblocks,showborders,sourcearea,specialchar,scayt,stylescombo,tab,table,tabletools,tableselection,undo,wsc,lineutils,widgetselection,widget,imagebase,balloonpanel,balloontoolbar,xml,ajax,cloudservices,easyimage';
	config.skin = 'office2013';
    config.extraPlugins = 'docprops,pbckcode';
    /*config.toolbarGroups = [
        {name: 'pbckcode'},
        // your other buttons here
        // get information about available buttons here: bhttp://docs.ckeditor.com/?mobile=/guide/dev_toolbar
    ];

    // CKEDITOR PLUGINS LOADING
    config.extraPlugins = 'pbckcode'; // add other plugins here (comma separated)

    // ADVANCED CONTENT FILTER (ACF)
    // ACF protects your CKEditor instance of adding unofficial tags
    // however it strips out the pre tag of pbckcode plugin
    // add this rule to enable it, useful when you want to re edit a post
    // only needed on v1.1.x
    config.allowedContent = 'pre[*]{*}(*)'; // add other rules here

    // PBCKCODE CUSTOMIZATION
    config.pbckcode = {
        // An optional class to your pre tag.
        cls: '',

        // The syntax highlighter you will use in the output view
        highlighter: 'PRETTIFY',

        // An array of the available modes for you plugin.
        // The key corresponds to the string shown in the select tag.
        // The value correspond to the loaded file for ACE Editor.
        modes: [['HTML', 'html'], ['CSS', 'css'], ['PHP', 'php'], ['JS', 'javascript']],

        // The theme of the ACE Editor of the plugin.
        theme: 'textmate',

        // Tab indentation (in spaces)
        tab_size: '4'
    };*/
	// %REMOVE_END%

	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
};
