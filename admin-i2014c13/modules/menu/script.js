//document ready actions -------------------------------------------------------
$(document).ready(function(){
    if(module_ready) showItems();
});

//cancels the action by removing the form
function cancelAction(){
    $('#editorholder').html('');
    $('#listcontent').css('display','block');
}

//displays the menu elements ---------------------------------------------------
function showItems(){
    sendRequest(
        'menu',
        {
            'action': 'getItems',
            'lang': $('#show_lang').val()
        }, function(data){
            $('#listholder').html(data);
            highlightElements($('#listholder table tr'));
        });
}

//Opens the new item form ------------------------------------------------------
function newItem(){
    sendRequest(
        'menu',
        {
            'action': 'getNewItemForm',
            'lang': $('#show_lang').val()
        }, function(data){
            $('#editorholder').html(data);
            $('#listcontent').css('display','none');
            controlCategoryLanguage();
        });
}

//adds new menuitem ------------------------------------------------------------
function addItem(id){
    var request_data = {
        'action': 'addItem',
        "parentid": $('#parentid').val(),
        "name": $('#name').val(),
        "description": $('#description').val(),
        "keywords": $('#keywords').val(),
        "categoryimage": $('#categoryimage').val(),
        "published": 0,
        "display_new_articles": 0,
        "show_in_menu": 0,
        "show_in_footer": 0,
        "layout": $('#layout').val(),
        "type": $('#type').val(),
        "lang": $('#lang').val(),
        "link": $('#link').val()
    }
    
    if($('#published').is(':checked')) request_data['published'] = 1;
    if($('#display_new_articles').is(':checked')) request_data['display_new_articles'] = 1;
    if($('#show_in_footer').is(':checked')) request_data['show_in_footer'] = 1;
    if($('#show_in_menu').is(':checked')) request_data['show_in_menu'] = 1;
    if($('#type').val() == 'p') request_data['link'] = $('#subpage').val();

    sendRequest(
        'menu',
        request_data,
        function(data){
            $('#editorholder').html(data);
            showItems();
            $('#listcontent').css('display','block');
        });
}

//deletes menuitem -------------------------------------------------------------
function deleteItem(id){
    var deletearticles = 0;
    if($('#deletearticles').is(':checked')) deletearticles = 1;

    sendRequest(
        'menu',
        {
            'action': 'deleteItem',
            'deletearticles': deletearticles,
            "id": id
        }, function(data){
            $('#editorholder').html(data);
            showItems();
        });
}

//Opens the menuitem form with menuitem data------------------------------------
function editItem(id){
    sendRequest(
        'menu',
        {
            'action': 'getEditItemForm',
            "id": id
        }, function(data){
            $('#editorholder').html(data);
            $('#listcontent').css('display','none');
            controlCategoryLanguage();
        });
}

//sets the new value for specified menuitem
function updateItem(id){
    
    var request_data = {
        'action': 'updateItem',
        "name": $('#name').val(),
        "description": $('#description').val(),
        "keywords": $('#keywords').val(),
        "categoryimage": $('#categoryimage').val(),
        "published": 0,
        "display_new_articles": 0,
        "show_in_menu": 0,
        "show_in_footer": 0,
        "layout": $('#layout').val(),
        "type": $('#type').val(),
        "lang": $('#lang').val(),
        "link": $('#link').val(),
        "id": id
    }
    
    if($('#published').is(':checked')) request_data['published'] = 1;
    if($('#display_new_articles').is(':checked')) request_data['display_new_articles'] = 1;
    if($('#show_in_footer').is(':checked')) request_data['show_in_footer'] = 1;
    if($('#show_in_menu').is(':checked')) request_data['show_in_menu'] = 1;
    if($('#type').val() == 'p') request_data['link'] = $('#subpage').val();

    sendRequest(
        'menu',
        request_data,
        function(data){
            $('#editorholder').html(data);
            showItems();
            $('#listcontent').css('display','block');
        });
}

//moves the menuelement to specified position ----------------------------------
function moveItem(id){
    sendRequest(
        'menu',
        {
            'action': 'getMoveItemForm',
            "id": id
        }, function(data){
            $('#editorholder').html(data);
            $('#listcontent').css('display','none');
        });
}

//moves the menuitem to specified position
function updateMoveItem(id){
    sendRequest(
        'menu',
        {
            'action': 'updateMoveItem',
            "id": id,
            'newcategory': $('#parentid').val(),
            'moveaction': $('input:radio[name=action]:checked').val()
        }, function(data){
            $('#editorholder').html(data);
            showItems();
            $('#listcontent').css('display','block');
        });
}

//controls the categorytype selector
function controlCategoryType(){
    var type = $('#type').val();
    
    //show hide linkname holder
    if(type == 's'){
        if($('#link').is(':visible')){
            $('#link').fadeOut();
        }
        if($('#subpage').is(':visible')){
            $('#subpage').fadeOut();
        }
    }else if(type == 'p'){
        if($('#link').is(':visible')){
            $('#link').fadeOut(function(){
                $('#subpage').fadeIn();
            });
        }else{
            $('#subpage').fadeIn();
        }
    }else{
        if($('#subpage').is(':visible')){
            $('#subpage').fadeOut(function(){
                $('#link').fadeIn();
            });
        }else{
            $('#link').fadeIn();
        }
    }
    
     //show hide layout combobox
    if(type == 's' || type == 'v'){
        $('#layoutbox,#imagebox').slideDown();
    }else{
        $('#layoutbox,#imagebox').slideUp();
    }
}

// enable/disable the languages of the parentid selector
function controlCategoryLanguage(change){
    var lang = $('#lang').val();
    $('#parentid optgroup').attr('disabled','disabled');
    $('#parentid optgroup.lang_'+lang).removeAttr('disabled');
    if(typeof change != 'undefined'){
       $('#parentid option:selected').each(function(){
           $(this).removeAttr('selected')}
       );
       $('#parentid option:[value="0"]').prop('selected', true);
    }
}

//opens the category image file selector
function selectCategoryFile(){
    openFilemanagerWindow("modules/menu/filemanager.php?field=categoryimage");
}