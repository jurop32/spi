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
        'slideshow',
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
        'slideshow',
        {
            'action': 'getNewItemForm',
            'lang': $('#show_lang').val()
        },function(data) {
            $('#listcontent').css('display','none');
            $('#editorholder').html(data);
            initDatepicker();
        });
}

//opens the file uploader window -----------------------------------------------
function selectSlideFile(){
    openFilemanagerWindow("modules/slideshow/filemanager.php?field=file");
}

//saves the new item -----------------------------------------------------------
function addItem(){
    sendRequest(
        'slideshow',
        {
            'action': 'addItem',
            "heading": $('#heading').val(),
            "description": $('#description').val(),
            "link": $('#link').val(),
            "textposition": $('#textposition').val(),
            "publish_from": $('#publish_from').val(),
            "publish_to": $('#publish_to').val(),
            "lang": $('#lang').val(),
            "file": $('#file').val()
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
        'slideshow',
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
        'slideshow',
        {
            'action': 'getEditItemForm',
            "id": id
        },function(data) {
            $('#listcontent').css('display','none');
            $('#editorholder').html(data);
            initDatepicker();
        });
}

//sets the new values for specified Slide
function updateItem(id){
    sendRequest(
        'slideshow',
        {
            'action': 'updateItem',
            "heading": $('#heading').val(),
            "description": $('#description').val(),
            "link": $('#link').val(),
            "textposition": $('#textposition').val(),
            "publish_from": $('#publish_from').val(),
            "publish_to": $('#publish_to').val(),
            "lang": $('#lang').val(),
            "file": $('#file').val(),
            "id": id
        },function(data) {
            $('#listcontent').css('display','block');
            $('#editorholder').html(data);
            showItems();
        });
}

//exchanges the slides determined by id
var slideshow_sourceslide = null;
function exchangeItems(id){
    if(slideshow_sourceslide == null){
        slideshow_sourceslide = id;
        displayMessage('highlight',texts['slideshow_t27']);
    }else{
        if(slideshow_sourceslide == id){
            slideshow_sourceslide = null;
            displayMessage('highlight',texts['slideshow_t28']);
        }else{
            sendRequest(
                'slideshow',
                {
                    'action': 'exchangeItems',
                    "src_id": slideshow_sourceslide,
                    "dst_id": id
                },function(data) {
                    slideshow_sourceslide = null;
                    $('#editorholder').html(data);
                    showItems();
                });
        }
    }
}

//inits the datepicker
function initDatepicker(){
    $(document).ready(function(){
        $("#publish_from,#publish_to").datepicker(
            $.datepicker.regional[ adminlangcode ]
        );
    });    
}
