//document ready actions -------------------------------------------------------
$(document).ready(function(){
    if(module_ready) showItems();
});

//displays the eventscalendar elements ---------------------------------------------------
function showItems(){
    sendRequest(
        'docs',
        {
            'action': 'getItems'
        }, function(data){
            $('#docspage').append(data);
        });
}