//document ready actions -------------------------------------------------------
$(document).ready(function(){
    if(module_ready) showItems();
});

//cancels the action by removing the form
function cancelAction(){
    $('#listcontent').css('display','block');
    $('#editorholder').html('');
}

//displays the menu elements ---------------------------------------------------
function showItems(){
    sendRequest(
        'minislider',
        {
            'action': 'getItems',
            'lang': $('#show_lang').val()
        },function(data) {
            $('#listholder').html(data);
            highlightElements($('#listholder table tr'));
        });
}

//Opens the new item form ------------------------------------------------------
function newItem(id){
    sendRequest(
        'minislider',
        {
            'action': 'getNewItemForm',
            'lang': $('#show_lang').val()
        },function(data) {
            $('#listcontent').css('display','none');
            $('#editorholder').html(data);
        });
}

//opens the file uploader window -----------------------------------------------
function selectSlideFile(){
    openFilemanagerWindow("modules/minislider/filemanager.php?field=filename");
}

//saves the new item -----------------------------------------------------------
function addItem(){
    var active = 0;
    if($('#active').is(':checked')) active = 1;
    
    sendRequest(
        'minislider',
        {
            'action': 'addItem',
            "text": $('#text').val(),
            "name": $('#name').val(),
            "url": $('#url').val(),
            "lang": $('#lang').val(),
            "filename": $('#filename').val(),
            "x": $('#x').val(),
            "y": $('#y').val(),
            "active": active
        },function(data) {
            $('#editorholder').html(data);
            showItems();
            $('#listcontent').css('display','block');
        });
}

//deletes menuitem -------------------------------------------------------------
function deleteItem(id){
    var deletefiles = 0;
    if($('#deletefiles').is(':checked')) deletefiles = 1;
    
    sendRequest(
        'minislider',
        {
            'action': 'deleteItem',
            'deletefiles': deletefiles,
            "id": id
        },function(data) {
            $('#editorholder').html(data);
            showItems();
        });
}

//Opens the Slide editor form ------------------------------------
function editItem(id){
    sendRequest(
        'minislider',
        {
            'action': 'getEditItemForm',
            "id": id
        },function(data) {
            $('#listcontent').css('display','none');
            $('#editorholder').html(data);
        });
}

//sets the new values for specified Slide
function updateItem(id){
    var active = 0;
    if($('#active').is(':checked')) active = 1;
    
    sendRequest(
        'minislider',
        {
            'action': 'updateItem',
            "text": $('#text').val(),
            "name": $('#name').val(),
            "url": $('#url').val(),
            "lang": $('#lang').val(),
            "filename": $('#filename').val(),
            "x": $('#x').val(),
            "y": $('#y').val(),
            "active": active,
            "id": id
        },function(data) {
            $('#listcontent').css('display','block');
            $('#editorholder').html(data);
            showItems();
        });
}

//exchanges the slides determined by id
var minislider_sourceslide = null;
function exchangeItems(id){
    if(minislider_sourceslide == null){
        minislider_sourceslide = id;
        displayMessage('highlight',texts['minislider_t17']);
    }else{
        if(minislider_sourceslide == id){
            minislider_sourceslide = null;
            displayMessage('highlight',texts['minislider_t18']);
        }else{
            sendRequest(
                'minislider',
                {
                    'action': 'exchangeItems',
                    "src_id": minislider_sourceslide,
                    "dst_id": id
                },function(data) {
                    minislider_sourceslide = null;
                    $('#editorholder').html(data);
                    showItems();
                });
        }
    }
}