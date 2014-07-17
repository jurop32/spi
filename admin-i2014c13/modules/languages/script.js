//document ready actions -------------------------------------------------------
$(document).ready(function(){
    if(module_ready) showItems();
});

//cancels the action by removing the form
function cancelAction(){
    $('#editorholder').html('');
    $('#listcontent').css('display','block');
}

//displays the languages elements ---------------------------------------------------
function showItems(){
    sendRequest(
        'languages',
        {
            'action': 'getItems'
        }, function(data){
            $('#listholder').html(data);
            highlightElements($('#listholder table tr'));
        });
}

//Opens the new item form ------------------------------------------------------
function newItem(){
    sendRequest(
        'languages',
        {
            'action': 'getNewItemForm'
        }, function(data){
            $('#editorholder').html(data);
            $('#listcontent').css('display','none');
        });
}

//adds new languagesitem ------------------------------------------------------------
function addItem(id){
    var published = 0;
    if($('#published').is(':checked')) published = 1;
    
    var defaultlang = 0;
    if($('#defaultlang').is(':checked')) defaultlang = 1;
    sendRequest(
        'languages',
        {
            'action': 'addItem',
            "name": $('#name').val(),
            "shortcode": $('#shortcode').val(),
            "longcode": $('#longcode').val(),
            "defaultlang": defaultlang,
            "published": published
        }, function(data){
            $('#editorholder').html(data);
            showItems();
            $('#listcontent').css('display','block');
        });
}

//deletes languagesitem -------------------------------------------------------------
function deleteItem(id){
    sendRequest(
        'languages',
        {
            'action': 'deleteItem',
            "id": id
        }, function(data){
            $('#editorholder').html(data);
            showItems();
        });
}

//Opens the languagesitem form with languagesitem data------------------------------------
function editItem(id){
    sendRequest(
        'languages',
        {
            'action': 'getEditItemForm',
            "id": id
        }, function(data){
            $('#editorholder').html(data);
            $('#listcontent').css('display','none');
        });
}

//sets the new value for specified languagesitem
function updateItem(id){
    var published = 0;
    if($('#published').is(':checked')) published = 1;
    
    var defaultlang = 0;
    if($('#defaultlang').is(':checked')) defaultlang = 1;
    sendRequest(
        'languages',
        {
            'action': 'updateItem',
            "name": $('#name').val(),
            "shortcode": $('#shortcode').val(),
            "longcode": $('#longcode').val(),
            "defaultlang": defaultlang,
            "published": published,
            "id": id
        }, function(data){
            $('#editorholder').html(data);
            showItems();
            $('#listcontent').css('display','block');
        });
}