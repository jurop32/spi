//document ready actions -------------------------------------------------------
$(document).ready(function(){
    if(module_ready) showItems();
});

//displays the eventscalendar elements ---------------------------------------------------
function showItems(){
    sendRequest(
        'stats',
        {
            'action': 'getItems'
        }, function(data){
            $('#stats').html(data);
        });
}