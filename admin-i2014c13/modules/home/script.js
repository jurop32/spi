//document ready actions -------------------------------------------------------
$(document).ready(function(){
    if(module_ready) showItems();
});

//displays the eventscalendar elements ---------------------------------------------------
function showItems(){
    sendRequest(
        'home',
        {
            'action': 'getItems'
        }, function(data){
            $('#homepage>div').prepend(data);
        });
}