//document ready actions -------------------------------------------------------
$(document).ready(function(){
    if(module_ready) showItems();
});

//cancels the action by removing the form
function cancelAction(){
    $('#listcontent').css('display','block');
    $('#editorholder').html('');
}

//displays the articles --------------------------------------------------------
function showItems(){
    var onlynotpublished = 0;
    if($('#onlynotpublished').is(':checked')) onlynotpublished = 1;

    sendRequest(
        'articles',
        {
            'action': 'getItems',
            'category': $('#kategoria').val(),
            'nazov': $('#nazov').val(),
            'order': $('#order').val(),
            'resultcount': $('#resultcount').val(),
            'resultpage': $('#resultpage').val(),
            'onlynotpublished': onlynotpublished
        }, function(data){
            if(typeof data == 'object'){
                $('#listholder').html(data.html);
                var resultpageholder = $('#resultpage');
                var activeresultpage = resultpageholder.val();
                resultpageholder.empty();
                
                //adding pages to select
                for(var i=0; i< data.resultpagescount; i++){
                    resultpageholder.append('<option class="resultpage_'+i+'" value="'+i+'">'+(i+1)+'</option>');
                }
                
                //selecting the active result page
                if(activeresultpage > (data.resultpagescount-1)){
                    resultpageholder.children('.resultpage_'+(data.resultpagescount-1)).attr('selected','selected');
                }else{
                    resultpageholder.children('.resultpage_'+activeresultpage).attr('selected','selected');
                }
            }else{
                $('#listholder').html(data);
            }
            highlightElements($('#listholder table tr'));
        });
}

//Opens the new article form ---------------------------------------------------
function newItem(){
    sendRequest(
        'articles',
        {
            'action': 'getNewItemForm',
            "id_menucategory": $('#kategoria').val(),
            'lang': $('#kategoria').find("option:selected").parent().attr('title')
        }, function(data){
            $('#listcontent').css('display','none');
            $('#editorholder').html(data);
            initTinyMce();
            initDatepickers();
        });
}

//adds new article -------------------------------------------------------------
function addItem(dirname){
    //tinyMCE.triggerSave();
    
    var published = 0;
    if($('#published').is(':checked')) published = 1;
    
    var topped = 0;
    if($('#topped').is(':checked')) topped = 1;
    
    var homepage = 0;
    if($('#homepage').is(':checked')) homepage = 1;
    
    var showsocials = 0;
    if($('#showsocials').is(':checked')) showsocials = 1;
    
    sendRequest(
        'articles',
        {
            "action": 'addItem',
            "article_title": $('#title').val(),
            "article_prologue": $('#prologue').val(),
            "keywords": $('#keywords').val(),
            "article_content": tinyMCE.activeEditor.getContent(),
            "id_menucategory": $('#id_menucategory').val(),
            "layout": $('#layout').val(),
            "author": $('#author').val(),
            "dirname": dirname,
            "published": published,
            "publish_date": $('#publish_date').val(),
            "topped": topped,
            "homepage": homepage,
            "added_by": $('#added_by').val(),
            "lang": $('#lang').val(),
            "showsocials": showsocials
        }, function(data){
            $('#listcontent').css('display','block');
            $('#editorholder').html(data);
            showItems();
        });
}

//deletes article --------------------------------------------------------------
function deleteItem(id){
    sendRequest(
        'articles',
        {
            'action': 'deleteItem',
            'id': id
        }, function(data){
            $('#editorholder').html(data);
            showItems();
        });
}

//Opens the article form with menuitem data ------------------------------------
function editItem(id){
    sendRequest(
        'articles',
        {
            'action': 'getEditItemForm',
            'id': id
        }, function(data){
            $('#listcontent').css('display','none');
            $('#editorholder').html(data);
            initTinyMce();
            initDatepickers();
        });
}

//sets the new value for specified article
function updateItem(id){
    //tinyMCE.triggerSave();

    var published = 0;
    if($('#published').is(':checked')) published = 1;
    
    var topped = 0;
    if($('#topped').is(':checked')) topped = 1;
    
    var homepage = 0;
    if($('#homepage').is(':checked')) homepage = 1;
    
    var showsocials = 0;
    if($('#showsocials').is(':checked')) showsocials = 1;
    
    sendRequest(
        'articles',
        {
            "action": 'updateItem',
            "article_title": $('#title').val(),
            "article_prologue": $('#prologue').val(),
            "keywords": $('#keywords').val(),
            "article_content": tinyMCE.activeEditor.getContent(),
            "id_menucategory": $('#id_menucategory').children(':selected').val(),
            "layout": $('#layout').val(),
            "author": $('#author').val(),
            "id": id,
            "published": published,
            "publish_date": $('#publish_date').val(),
            "topped": topped,
            "homepage": homepage,
            "added_by": $('#added_by').val(),
            "lang": $('#lang').val(),
            "showsocials": showsocials
        }, function(data){
            $('#listcontent').css('display','block');
            $('#editorholder').html(data);
            showItems();
        });
}

//opens the image uploader window -----------------------------------------------
function uploadImageFiles(){
    openFilemanagerWindow("modules/articles/filemanager.php?field=image");
}

//initializes the datepicker
function initDatepickers(){
    $(document).ready(function(){
        $('.date').datepicker(
            $.datepicker.regional[ "sk" ]
        );
    });
}

//TinyMce editor init
function initTinyMce(){
    $(function() {
        $('textarea.tinymce').tinymce({
            // Location of TinyMCE script
            script_url : 'scripts/libs/tiny_mce/tiny_mce.js',

            // General options
            mode : "textareas",
            theme : "advanced",
            skin : "default",
            skin_variant : "silver",
            language: adminlangcode,
            plugins : "autolink,lists,pagebreak,style,layer,table,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,contextmenu,paste,directionality,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist",

            // Theme options
            theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
            theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,|,undo,redo,|,link,unlink,anchor,cleanup,code,styleprops,|,forecolor,backcolor",
            theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,iespell,media,image,advhr",
            theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,blockquote,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,restoredraft,|,insertdate,inserttime",
            theme_advanced_toolbar_location : "top",
            theme_advanced_toolbar_align : "left",
            theme_advanced_statusbar_location : "bottom",
            theme_advanced_resizing : true,
            theme_advanced_resizing_max_width: 880,
            theme_advanced_resizing_max_height: 600,
		
            document_base_url : mainpageurl,
            
            entity_encoding : "raw",
            
            //custom font sizes
            theme_advanced_font_sizes: "10px,12px,13px,14px,16px,18px,20px",
            
            //editor styles from page styles
            content_css : mainpageurl+"/styles/editor.css",

            //Mad File Manager

            //relative_urls : false,
            //remove_script_host : false,
            file_browser_callback : MadFileBrowser                
        });
        
    });
}

function MadFileBrowser(field_name, url, type, win) {
    var mediatype='null';
    if(win.document.getElementById("media_type") != null){
        mediatype= win.document.getElementById("media_type").value;
    }
    tinyMCE.activeEditor.windowManager.open({
        file : "modules/articles/filechooser.php?field=" + field_name + "&url=" + url + "&mediatype="+mediatype,
        title : 'File Manager',
        width : 620,
        height : 435,
        resizable : "no",
        inline : "yes",
        close_previous : "no"
    }, {
        window : win,
        input : field_name,
        type: type
    });
    return false;
}