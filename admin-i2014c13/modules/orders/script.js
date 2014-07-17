//document ready actions -------------------------------------------------------
$(document).ready(function(){
    if(module_ready) showItems();
});

//cancels the action by removing the form
function cancelAction(){
    $('#listcontent').css('display','block');
    $('#editorholder').html('');
}

//displays the orders --------------------------------------------------------
function showItems(){
    sendRequest(
        'orders',
        {
            'action': 'getItems',
            'owner': $('#owner').val(),
            'state': $('#state').val(),
            'order': $('#order').val(),
            'resultcount': $('#resultcount').val(),
            'resultpage': $('#resultpage').val()
        }, function(data){
            if(typeof data == 'object'){
                $('#listholder').html(data.html);
                var resultpageholder = $('#resultpage');
                var activeresultpage = resultpageholder.val();
                resultpageholder.empty();

                //adding pages to select
                for(var i=0; i< data.resultpagescount; i++){
                    resultpageholder.append('<option class="resultpage_'+i+'" value="'+i+'">'+(i+1)+'</option>');
                }

                //selecting the active result page
                if(activeresultpage > (data.resultpagescount-1)){
                    resultpageholder.children('.resultpage_'+(data.resultpagescount-1)).attr('selected','selected');
                }else{
                    resultpageholder.children('.resultpage_'+activeresultpage).attr('selected','selected');
                }
            }else{
                $('#listholder').html(data);
            }
            highlightElements($('#listholder table tr'));
        });
}

//Opens the order for preview ------------------------------------
function showItem(id){
    sendRequest(
        'orders',
        {
            'action': 'getItem',
            'id': id
        }, function(data){
            $('#listcontent').css('display','none');
            $('#editorholder').html(data);
        });
}

function checkAddOrder(){
    sendRequest(
        'orders',
        {
            'action': 'checkAddOrder'
        }, function(data){
            $('#listcontent').css('display','none');
            $('#editorholder').html(data);
        });
}

//Opens the new article form ---------------------------------------------------
function newItem(){
    sendRequest(
        'orders',
        {
            'action': 'getNewItemForm'
        }, function(data){
            $('#listcontent').css('display','none');
            $('#editorholder').html(data);
            initDatepickers();
        });
}

//adds new article -------------------------------------------------------------
function addItem(){
    sendRequest(
        'orders',
        {
            "action": 'addItem',
            "meno": $('#meno').val(),
            "priezvisko": $('#priezvisko').val(),
            "firma": $('#firma').val(),
            "telefon": $('#telefon').val(),
            "email": $('#email').val(),
            "miesto": $('#miesto').val(),
            "typ_zateplenia": $('#typ_zateplenia').val(),
            "plocha": $('#plocha').val(),
            "hrubka": $('#hrubka').val(),
            "datum_platnosti": $('#datum_platnosti').val(),
            "vlastnik": $('#vlastnik').val()
        }, function(data){
            $('#listcontent').css('display','block');
            $('#editorholder').html(data);
            showItems();
        });
}

//deletes article --------------------------------------------------------------
function deleteItem(id){
    sendRequest(
        'orders',
        {
            'action': 'deleteItem',
            'id': id
        }, function(data){
            $('#editorholder').html(data);
            showItems();
        });
}

//Opens the article form with menuitem data ------------------------------------
function editItem(id){
    sendRequest(
        'orders',
        {
            'action': 'getEditItemForm',
            'id': id
        }, function(data){
            $('#listcontent').css('display','none');
            $('#editorholder').html(data);
            initDatepickers();
        });
}

//sets the new value for specified article
function updateItem(id){
    sendRequest(
        'orders',
        {
            "action": 'updateItem',
            "meno": $('#meno').val(),
            "priezvisko": $('#priezvisko').val(),
            "firma": $('#firma').val(),
            "telefon": $('#telefon').val(),
            "email": $('#email').val(),
            "miesto": $('#miesto').val(),
            "typ_zateplenia": $('#typ_zateplenia').val(),
            "plocha": $('#plocha').val(),
            "hrubka": $('#hrubka').val(),
            "datum_platnosti": $('#datum_platnosti').val(),
            "sarza_setov": $('#sarza_setov').val(),
            "mnozstvo_mat": $('#mnozstvo_mat').val(),
            "id": id,
            "vlastnik": $('#vlastnik').val()
        }, function(data){
            $('#listcontent').css('display','block');
            $('#editorholder').html(data);
            showItems();
        });
}

//Assigns the order to user ------------------------------------
function acquireItem(id){
    sendRequest(
        'orders',
        {
            'action': 'acquireItem',
            'id': id
        }, function(data){
            showItems();
        });
}

//marks item as finished
function finishItem(id){
    sendRequest(
        'orders',
        {
            "action": 'finishItem',
            "sarza_setov": $('#sarza_setov').val(),
            "mnozstvo_mat": $('#mnozstvo_mat').val(),
            "id": id
        }, function(data){
            $('#listcontent').css('display','block');
            $('#editorholder').html(data);
            showItems();
        });
}

//opens the image uploader window -----------------------------------------------
function uploadImageFiles(){
    openFilemanagerWindow("modules/orders/filemanager.php");
}

//initializes the datepicker
function initDatepickers(){
    $(document).ready(function(){
        $('.date').datepicker(
            $.datepicker.regional[ "sk" ]
        );
    });
}
