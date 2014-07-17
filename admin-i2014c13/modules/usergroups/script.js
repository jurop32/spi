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
function showItems(category,nazov){
    sendRequest(
        'usergroups',
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
        'usergroups',
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
    
    sendRequest(
        'usergroups',
        {
            "action": 'addItem',
            "name": $('#name').val(),
            "privileges": parsePrivileges(),
            "denylogin": denylogin
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
        'usergroups',
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
        'usergroups',
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
    
    sendRequest(
        'usergroups',
        {
            "action": 'updateItem',
            "name": $('#name').val(),
            "privileges": parsePrivileges(),
            "denylogin": denylogin,
            "id": id
        }, function(data){
            $('#listcontent').css('display','block');
            $('#editorholder').html(data);
            showItems();
        });
}

//function returns the privileges string
function parsePrivileges(){
    var privileges = '';
    var moduleprivilegecount;

    var availablemodules = $('#availablemodules').val().split(',');
    for(var i in availablemodules){
        privileges += availablemodules[i]+':'
        moduleprivilegecount = $('.'+availablemodules[i]).length;
        for(var j=0;j<moduleprivilegecount;j++){
            if($('#'+availablemodules[i]+j).is(':checked')){
                privileges += '1';
            }else{
                privileges += '0';
            }
        }
        privileges += '|';
    }
    return privileges.substring(0, privileges.length-1);
}

//highlights the selectable privileges
function controlPrivilegeHighlighting(element){
    var activegroup = $(element).attr('class');
    var groupelementcount = $('.'+activegroup).length;
    var i=1;
    if($(element).is(':checked')){
        for(i;i<groupelementcount;i++){
            $('#'+activegroup+i).removeAttr('disabled');
        }
    }else{
        for(i;i<groupelementcount;i++){
            $('#'+activegroup+i).attr('disabled','disabled');
        }
    }
}