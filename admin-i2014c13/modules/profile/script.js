//document ready actions -------------------------------------------------------
$(document).ready(function(){
    if(module_ready) showProfile();
});

//cancels the action by removing the form
function cancelAction(){
    $('#listcontent').css('display','block');
    $('#editorholder').html('');
}

//displays the items --------------------------------------------------------
function showProfile(){
    sendRequest(
        'profile',
        {
            'action': 'getProfile'
        }, function(data){
            $('#vcardholder').html(data);
        });
}

//Opens the item form with menuitem data ------------------------------------
function editItem(id){
    sendRequest(
        'profile',
        {
            'action': 'getEditItemForm',
            'id': id
        }, function(data){
            $('#listcontent').css('display','none');
            $('#editorholder').html(data);
        });
}

//sets the new value for specified item
function updateItem(){
    
    var newsletter = 0;
    if($('#newsletter').is(':checked')) newsletter = 1;
    
    sendRequest(
        'profile',
        {
            "action": 'updateItem',
            "firstname": $('#firstname').val(),
            "surename": $('#surename').val(),
            "fronttitles": $('#fronttitles').val(),
            "endtitles": $('#endtitles').val(),
            "username": $('#username').val(),
            "password": $('#password').val(),
            "email": $('#email').val(),
            "newsletter": newsletter,
            "phone": $('#phone').val(),
            "company": $('#company').val(),
            "company_id": $('#company_id').val(),
            "vat_id": $('#vat_id').val(),
            "tax_id": $('#tax_id').val(),
            "address": $('#address').val(),
            "country": $('#country').val()
        }, function(data){
            $('#listcontent').css('display','block');
            $('#editorholder').html(data);
            showProfile();
        });
}