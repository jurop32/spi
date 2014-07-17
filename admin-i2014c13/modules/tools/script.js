//document ready
$(document).ready(function(){
    if(module_ready) showItems();
});

//displays the items --------------------------------------------------------
function showItems(category,nazov){
    sendRequest(
        'tools',
        {
            'action': 'getItems'
        }, function(data){
            $('#listcontent').html(data);
        });
}

//cancels the action by removing the form
function cancelAction(){
    $('#listcontent').css('display','block');
    $('#editorholder').html('');
}

//clearCache of the page
function clearPageCache(){
    sendRequest(
        'tools',
        {
            'action': 'clearPageCache'
        }, function(data){
            $('#cachedpagecount').html('0');
        });
}

//clears the error log file
function clearErrorLog(){
    sendRequest(
        'tools',
        {
            'action': 'clearErrorLog'
        }, function(data){
            $('#logerrorcount').html('0');
        });
}

//displays error log file contents
function showErrorLog(){
    sendRequest(
        'tools',
        {
            'action': 'showErrorLog'
        }, function(data){
            $('#listcontent').css('display','none');
            $('#editorholder').html(data);
        });
}

//displays changelog log file contents
function showChangeLog(){
    sendRequest(
        'tools',
        {
            'action': 'showChangeLog'
        }, function(data){
            $('#listcontent').css('display','none');
            $('#editorholder').html(data);
        });
}