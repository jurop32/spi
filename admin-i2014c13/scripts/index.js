//global vars
var listhoverclass = 'ui-state-active';

$(document).ready(function(){
    initButtons();
    initButtonsets();
    initMenu();
    initTabs();
    initSpinners();
    $( "#filemanagerholder" ).dialog({
        width: 647,
        modal: true,
        resizable: false,
        autoOpen: false
    });
}).ajaxStop(function(){
    initButtons();
    initButtonsets();
    initHelpBoxes();
    initTabs();
    initSpinners();
});

//function initializes jquery ui style buttons
function initButtons(){
    $('.button').each(function(){
        var button = $(this);
        button.removeClass('button');
        var temp = button.attr('class');
        var classnames = temp.split(" ");
        if (/ui-icon/.test(classnames[0])){
            button.removeClass(classnames[0]);
            if (/notext/.test(classnames[1])){
                button.removeClass(classnames[1]);
                button.button({
                    icons: {
                        primary: classnames[0]
                    },
                    text: false
                });
            }else{
                button.button({
                    icons: {
                        primary: classnames[0]
                    }
                });
            }

        }else{
            button.button();
        }
    });
}

//function initializes jquery ui style buttons
function initButtonsets(){
    $('.buttonset').each(function(){
        var button = $(this);
        button.removeClass('buttonset');
        button.buttonset();
    });
}

//initializes the help boxes
function initHelpBoxes(){
    var helpboxes = $('div.help');
    helpboxes.removeClass('help').addClass('helpbox ui-icon ui-icon-info').hover(function(){
        $(this).children('p').fadeIn(200);
    },function(){
        $(this).children('p').fadeOut(200);
    });
    helpboxes.children('p').addClass('ui-state-highlight ui-corner-all');
}

//initializes the menubar
function initMenu(){
    $('#menu').height($('#adminbar').height());
    $('#menu>ul').children('li').hover(function(){
        var element = $(this);
        //element.addClass('ui-state-default');
        element.addClass('shadowed');
        element.children('a,span').addClass('ui-state-hover');
        element.children('ul').slideDown(100);
    },function(){
        var element = $(this);
        //element.removeClass('ui-state-default');
        element.removeClass('shadowed');
        element.children('a,span').removeClass('ui-state-hover');
        element.children('ul').css('display','none');
    });
    $('#menu>ul>li>ul a').hover(function(){
        $(this).addClass('ui-state-active');
    },function(){
        $(this).removeClass('ui-state-active');
    });
}

//initializes the tabs on all pages
function initTabs(){
    $( ".tabs" ).tabs();
}

//initializes the spinners
function initSpinners(){
    $( ".spinner" ).spinner({min: 1,numberFormat: "n"});
}

//display auto dissapearing message
function displayMessage(type,message){
    $('#messageholder').addClass('ui-state-'+type).html('<p>'+message+'</p>').show('bounce',100);
    setTimeout('$("#messageholder").removeClass("ui-state-'+type+'").hide("drop",{ direction: "down" },200)',3000);
}

//opening filemanager iframe
function openFilemanagerWindow(url){
    var iframe = '<iframe src="'+url+'" frameborder="0" style="width:620px;height:440px;"></iframe>';
    var mywindow = $( "#filemanagerholder" );
    mywindow.children('div').html(iframe);
    mywindow.dialog("open");
}

//closing filemanager iframe
function closeFilemanagerWindow(){
    var mywindow = $( "#filemanagerholder" );
    mywindow.dialog("close");
    mywindow.children('div').html('');
}

//function sends request to server
function sendRequest(module,requestdata,callback){
    requestdata['method'] = module+'->'+requestdata['action'];
    delete requestdata['action'];
    $('#overlay').css('display','block');
    
    $.ajax({
        type: 'POST',
        url: 'ajax.php',
        data: requestdata,
        dataType: 'json',
        success: function(data){
            if(typeof data == 'object'){
                if(data.state == 'ok'){
                    callback(data.data);
                }else if(data.state == 'highlight'){
                    callback('');
                    displayMessage(data.state,data.data);
                }else{
                    displayMessage(data.state,data.data);
                    if(typeof data.field != 'undefined'){
                        $('input.error, textarea.error, select.error').removeClass('error');
                        $('#'+data.field).addClass('error');
                    }
                }
            }else{
                console.log(data);
            }

            $('#overlay').css('display','none');
        },
        error: function(jqXHR,textStatus,errorThrown){
            alert(jqXHR.responseText);
            $('#overlay').css('display','none');
        }            
    });
}

//iterates trought given elements and sets the background color of every second
function highlightElements(elements){
    var counter = 1;
    elements.each(function(){
        var element = $(this);
        if((counter%2) == 1){
            element.addClass('even');
        }
        element.hover(function(){
            $(this).addClass(listhoverclass);
        },function(){
            $(this).removeClass(listhoverclass);
        });
        counter++;
    });
}

//displays the item deletion confirmation
function confirmDeleteItem(id,name){
    name = name || null;
    var confirm = $( "#dialog-confirm" );
    confirm.find('.itemname').html(((name == null)?id:name));
    confirm.dialog({
        resizable: false,
        width: 'auto',
        modal: true,
        buttons: [{
            text: texts['delete'],
            click: function() {
                deleteItem(id);
                $( this ).dialog( "close" );
            }
        },{
            text: texts['cancel'],
            click: function() {
                $( this ).dialog( "close" );
            }
        }]
    });
}

//function generates random string
function generateRandomString(input,length){
    var alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    var pass = [];
    var alphaLength = alphabet.length;
    var n;
    for (var i = 0; i < length; i++) {
        n = Math.floor(Math.random()*alphaLength);
        pass[pass.length] = alphabet[n];
    }
    $(input).val(pass.join(''));
}