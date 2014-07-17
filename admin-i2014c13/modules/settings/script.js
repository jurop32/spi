//document ready actions -------------------------------------------------------
$(document).ready(function(){
    if(module_ready) showItems();
});

//cancels the action by removing the form
function cancelAction(){
    $('#listcontent').css('display','block');
    $('#editorholder').html('');
}

//displays the eventscalendar elements ---------------------------------------------------
function showItems(){
    sendRequest(
        'settings',
        {
            'action': 'getItems'
        }, function(data){
            $('#settings').html(data);
    });
}

//Opens the items form for editing
function editItems(){
    sendRequest(
        'settings',
        {
            'action': 'getEditItemsForm'
        }, function(data){
            $('#listcontent').css('display','none');
            $('#editorholder').html(data);
        });
}

//sets the new value for specified item
function updateItems(){
    
    var settings = $('#settingskeys').val().split(',');
    
    var userdata = {
        "action": 'updateItems'
    };
    
    var currentinput;
    for(var i in settings){
        currentinput = $('#'+settings[i]);
        if(currentinput.length == 0) currentinput = $('input[name='+settings[i]+']');
        if(currentinput.length > 1){
            userdata[settings[i]] = currentinput.filter(':checked').val();
        }else if(currentinput.length == 1){
            userdata[settings[i]] = currentinput.val();
        }
    }
    
    sendRequest(
        'settings',
        userdata,
        function(data){
            $('#listcontent').css('display','block');
            $('#editorholder').html(data);
            showItems();
        });
}