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
    sendRequest(
        'banners',
        {
            'action': 'getItems'
        },function(data) {
            $('#listholder').html(data);
            highlightElements($('#listholder table tr'));
        });
}

//Opens the new article form ---------------------------------------------------
function newItem(){
    sendRequest(
        'banners',
        {
            'action': 'getNewItemForm'
        },function(data) {
            $('#listcontent').css('display','none');
            $('#editorholder').html(data);
            controlCategoryLanguage();
        });
}

//adds new article -------------------------------------------------------------
function addItem(id){
    var active = 0;
    if($('#active').is(':checked')) active = 1;
    
    var openin = 0;
    if($('#openin').is(':checked')) openin = 1;

    var position = $('#position').val();
    if(position == 'pos') position = $('#position_number').val();
    
    sendRequest(
        'banners',
        {
            'action': 'addItem',
            "type": $('#type').val(),
            "link": $('#link').val(),
            "description": $('#description').val(),
            "lang": $('#lang').val(),
            "location": $('#location').val(),
            "position": position,
            "filename": $('#filename').val(),
            "maxviewcount": $('#maxviewcount').val(),
            "active": active,
            "openin": openin
        },function(data) {
            $('#listcontent').css('display','block');
            $('#editorholder').html(data);
            showItems();
        });
}

//deletes article --------------------------------------------------------------
function deleteItem(id){
    var deletefiles = 0;
    if($('#deletefiles').is(':checked')) deletefiles = 1;
    
    sendRequest(
        'banners',
        {
            'action': 'deleteItem',
            "id": id,
            "deletefiles": deletefiles
        },function(data) {
            $('#editorholder').html(data);
            showItems();
        });
}

//Opens the article form with menuitem data ------------------------------------
function editItem(id){
    sendRequest(
        'banners',
        {
            'action': 'getEditItemForm',
            'id': id
        },function(data) {
            $('#listcontent').css('display','none');
            $('#editorholder').html(data);
            showFilenameInput();
            controlCategoryLanguage();
        });
}

//sets the new value for specified article
function updateItem(id){
    var active = 0;
    if($('#active').is(':checked')) active = 1;
    
    var openin = 0;
    if($('#openin').is(':checked')) openin = 1;

    var position = $('#position').val();
    if(position == 'pos') position = $('#position_number').val();

    sendRequest(
        'banners',
        {
            'action': 'updateItem',
            "type": $('#type').val(),
            "link": $('#link').val(),
            "description": $('#description').val(),
            "lang": $('#lang').val(),
            "location": $('#location').val(),
            "position": position,
            "filename": $('#filename').val(),
            "maxviewcount": $('#maxviewcount').val(),
            "active": active,
            "openin": openin,
            "id": id
        },function(data) {
            $('#listcontent').css('display','block');
            $('#editorholder').html(data);
            showItems();
        });
}

function selectBannerFile(){
    openFilemanagerWindow("modules/banners/filemanager.php?field=filename");
}

function showPositionInput(){
    if($('#position').val() == 'pos'){
        $('#position_number').show();
    }else{
        $('#position_number').hide();
    }
}

function showFilenameInput(){
    var type = $('#type').val();
    if(type == 'e' || type == 'f'){
        $('#bannerfile').slideUp();
    }else{
        $('#bannerfile').slideDown();
    }
    if(type == 'c' || type == 'd'){
        $('#bannerfile>button').fadeOut();
    }else{
        $('#bannerfile>button').fadeIn();
    }
    if(type == 'a' || type == 'c'){
        $('#openinholder').slideDown();
    }else{
        $('#openinholder').slideUp();
    }
}

// enable/disable the languages of the category selector
function controlCategoryLanguage(event){
    if(typeof event != 'undefined'){
        var lang = $('#lang').val();
        $('#location optgroup').attr('disabled','disabled');
        $('#location optgroup.lang_'+lang).removeAttr('disabled');

        if(event.type == 'change'){
           $('#location option:selected').each(function(){
               $(this).removeAttr('selected')}
           );
           $('#location option:[value="0"]').prop('selected', true);
        }
    }
}

//number input
function validateTextinput(input){
    var input_object = $(input);
    var new_value = input_object.val();
    if(!(new_value.match(/^\d+$/)) || new_value<1){
        input_object.val(1);
    }
}