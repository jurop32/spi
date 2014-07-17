//document ready actions -------------------------------------------------------
$(document).ready(function(){
    if(module_ready) showItems();
});

//cancels the action by removing the form
function cancelAction(){
    $('#listcontent').css('display','block');
    $('#editorholder').html('');
}

//displays the items --------------------------------------------------------
function showItems(){
    sendRequest(
        'users',
        {
            'action': 'getItems'
        }, function(data){
            $('#listholder').html(data);
            highlightElements($('#listholder table tr'));
        });
}

//Opens the new item form ---------------------------------------------------
function newItem(){
    sendRequest(
        'users',
        {
            'action': 'getNewItemForm',
            "id_menucategory": $('#kategoria').val()
        }, function(data){
            $('#listcontent').css('display','none');
            $('#editorholder').html(data);
        });
}

//adds new item -------------------------------------------------------------
function addItem(){
    var denylogin = 0;
    if($('#denylogin').is(':checked')) denylogin = 1;
    
    var newsletter = 0;
    if($('#newsletter').is(':checked')) newsletter = 1;
    
    var notify = 0;
    if($('#notify').is(':checked')) notify = 1;

    sendRequest(
        'users',
        {
            "action": 'addItem',
            "fronttitles": $('#fronttitles').val(),
            "firstname": $('#firstname').val(),
            "surename": $('#surename').val(),
            "endtitles": $('#endtitles').val(),
            "email": $('#email').val(),
            "phone": $('#phone').val(),
            "username": $('#username').val(),
            "password": $('#password').val(),
            "company": $('#company').val(),
            "company_id": $('#company_id').val(),
            "vat_id": $('#vat_id').val(),
            "tax_id": $('#tax_id').val(),
            "address": $('#address').val(),
            "country": $('#country').val(),
            "usergroup": $('#usergroup').val(),
            "denylogin": denylogin,
            "newsletter": newsletter,
            "notify": notify,
            "categoryaccess": $('#categoryaccess').val()
        }, function(data){
            $('#listcontent').css('display','block');
            $('#editorholder').html(data);
            showItems();
        });
}

//deletes item --------------------------------------------------------------
function deleteItem(id){
    var deletearticles = 0;
    if($('#deletearticles').is(':checked')) deletearticles = 1;
    sendRequest(
        'users',
        {
            'action': 'deleteItem',
            'deletearticles': deletearticles,
            'id': id
        }, function(data){
            $('#editorholder').html(data);
            showItems();
        });
}

//Opens the item form with menuitem data ------------------------------------
function editItem(id){
    sendRequest(
        'users',
        {
            'action': 'getEditItemForm',
            'id': id
        }, function(data){
            $('#listcontent').css('display','none');
            $('#editorholder').html(data);
        });
}

//sets the new value for specified item
function updateItem(id){
    var denylogin = 0;
    if($('#denylogin').is(':checked')) denylogin = 1;
    
    var newsletter = 0;
    if($('#newsletter').is(':checked')) newsletter = 1;
    
    sendRequest(
        'users',
        {
            "action": 'updateItem',
            "fronttitles": $('#fronttitles').val(),
            "firstname": $('#firstname').val(),
            "surename": $('#surename').val(),
            "endtitles": $('#endtitles').val(),
            "email": $('#email').val(),
            "phone": $('#phone').val(),
            "username": $('#username').val(),
            "password": $('#password').val(),
            "company": $('#company').val(),
            "company_id": $('#company_id').val(),
            "vat_id": $('#vat_id').val(),
            "tax_id": $('#tax_id').val(),
            "address": $('#address').val(),
            "country": $('#country').val(),
            "usergroup": $('#usergroup').val(),
            "denylogin": denylogin,
            "newsletter": newsletter,
            "id": id,
            "categoryaccess": $('#categoryaccess').val()
        }, function(data){
            $('#listcontent').css('display','block');
            $('#editorholder').html(data);
            showItems();
        });
}