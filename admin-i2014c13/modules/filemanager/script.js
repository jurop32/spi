//document ready actions -------------------------------------------------------
$(document).ready(function(){
    openFilemanager();
});

//opens the file selector for food images
function openFilemanager(){
    var options = {
        defaultView: 'list',
        url: 'filemanager/elfinder/php/connector.php',
        lang: adminlangcode,
        resizable: false,
        requestType: 'post',
        height: 490
    }
    
    $('#filemanager>div').elfinder(options).elfinder('instance');
}