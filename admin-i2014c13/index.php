<?php

//run loader
require_once 'serverscripts/__loader.php';

//logging in user if not logged in
if(!isset($_SESSION[PAGE_SESSIONID]['id'])){
    authenticator::login($___REGISTRY);
}

//serving logout request
if(isset($_GET['logout']) && isset($_SESSION[PAGE_SESSIONID]['id'])){
    authenticator::logout();
    header("Location: ".urldecode($_GET['return']));
}

//setting requested content
$___AVAILABLEMODULES = $___REGISTRY['modules']->getModulesList();
$___PAGE = 'home';
if (isset($_GET['page']) && file_exists(ADMIN_ABSDIRPATH.'modules/' . $_GET['page'] . '/index.php')) {
    $___PAGE = $_GET['page'];
}else if(!file_exists(ADMIN_ABSDIRPATH.'modules/home/index.php')) {
    die(sprintf($___LANGUAGER->getText('index','errors.nomodules'),$___PAGE,(isset($_GET['page'])?$_GET['page']:'home')));
}

//last visited subpage
$_SESSION[PAGE_SESSIONID]['lastrequestaddress'] = 'http'.((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')?'s':'').'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];

echo '<?xml version = "1.0"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns = "http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="author" content="<?= PAGE_AUTHOR; ?>" />
        <meta name="Content-language" content="<?=$___LANGUAGER->getAdminLangCode(); ?>" />
        <meta name="robots" content="noindex, nofollow" />
        <title><?= $___CONFIGURATION->getSetting('PAGE_TITLEPREFIX').' - '.$___LANGUAGER->getText('index','administration'); ?></title>
        <link rel="icon" type="image/ico" href="../design/favicon.ico" />
        <link type="text/css" rel="stylesheet" href="styles/jqueryui.css" />
        <link type="text/css" rel="stylesheet" href="styles/style.css?<?=SYSTEM_CHANGEVERSION; ?>" />
        <script type="text/javascript" src="scripts/libs/jquery.js"></script>
        <script type="text/javascript" src="scripts/libs/jqueryui.js"></script>

        <? if (isset($_SESSION[PAGE_SESSIONID]['id'])) { ?>            
            <link type="text/css" rel="stylesheet" href="filemanager/elfinder/css/elfinder.min.css" />
            <link type="text/css" rel="stylesheet" href="filemanager/elfinder/css/theme.css" />
            <script type="text/javascript" src="filemanager/elfinder/js/elfinder.min.js"></script>
            <script type="text/javascript" src="filemanager/elfinder/js/i18n/elfinder.<?=$___LANGUAGER->getAdminLangCode(); ?>.js"></script>
            
            <link type="text/css" rel="stylesheet" href="styles/jquery-ui-timepicker-addon.css" />
            <script type="text/javascript" src="scripts/libs/jquery-ui-timepicker-addon.js"></script>
            
            <script type="text/javascript" src="scripts/jsvariables.php?<?=SYSTEM_CHANGEVERSION; ?>"></script>
            <script type="text/javascript" src="scripts/index.js?<?=SYSTEM_CHANGEVERSION; ?>"></script>
            <? if(file_exists(ADMIN_ABSDIRPATH.'modules/'.$___PAGE.'/script.js')){ ?>
                <script type="text/javascript" src="modules/<?=$___PAGE; ?>/script.js?<?=$___AVAILABLEMODULES[$___PAGE]['version']; ?>"></script>
            <? } if(file_exists(ADMIN_ABSDIRPATH.'modules/'.$___PAGE.'/style.css')){ ?>
                <link type="text/css" rel="stylesheet" href="modules/<?=$___PAGE; ?>/style.css?<?=$___AVAILABLEMODULES[$___PAGE]['version']; ?>" />
            <? } ?>
        <? } ?>

    </head>
    <body>
        <? if (isset($_SESSION[PAGE_SESSIONID]['id'])) { ?>
        
            <div id="secondbody" class="ui-widget">
                <div id="adminbar" class="ui-widget-header ui-state-focus ui-corner-all">
                    <span class="left"><?=$___LANGUAGER->getText('index','user').': '.$_SESSION[PAGE_SESSIONID]['fullname']; ?> (<?=$_SESSION[PAGE_SESSIONID]['usergroup'].': '.$_SESSION[PAGE_SESSIONID]['username']; ?>)</span> &nbsp;&nbsp;&lt;&gt;&nbsp; <?=$___LANGUAGER->getText('index','today_is').': '.date('d.m.Y'); ?>
                    <a href="index.php?logout=true&return=<?=urlencode('http'.((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')?'s':'').'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']); ?>" class="ui-icon-key button right ui-priority-secondary">
                        <?=$___LANGUAGER->getText('index','logout'); ?>
                    </a>
                    <a href="../" class="ui-icon-newwin button right ui-priority-secondary">
                        <?=$___LANGUAGER->getText('index','show_page'); ?>
                    </a>
                    <div class="clear"></div>
                </div>
                <?=$___REGISTRY['adminmenu']->getAdminMenu(); ?>
                <div class="editorpage ui-widget-content ui-corner-all">
                    <?
                        //redirect user if no privileges for module
                        if($_SESSION[PAGE_SESSIONID]['privileges'][$___PAGE][0] != '1') die('<script type="text/javascript">window.location = "'.PAGE_URL.ADMINDIRNAME.'index.php?page=home"</script>');
                        
                        $dependency = $___REGISTRY['modules']->checkDependency($___PAGE);
                        if($dependency === true){
                            require_once ADMIN_ABSDIRPATH.'modules/' . $___PAGE . '/index.php';
                            echo '<script type="text/javascript">var module_ready=true;</script>';
                        }else{
                            echo '<p>'.sprintf($___LANGUAGER->getText('index','errors.install_dependency_modules'),$dependency,$___PAGE).'</p>';
                        }
                    ?>
                </div>
                <div id="footer"><?=$___LANGUAGER->getText('index','system_author'); ?>: <a href="<?=PAGE_CREATEDBYWEB; ?>" target="_blank"><?=PAGE_CREATEDBY; ?></a></div>
            </div>
            <div id="overlay" class="ui-widget-overlay"></div>
            <div id="messageholder" class="ui-corner-all"></div>
            <div id="filemanagerholder" title="Správca súborov"><div></div></div>
        <? } else { 
            echo authenticator::printLoginForm($___REGISTRY);
        ?>
            
            <a href="<?= PAGE_URL; ?>">
                &raquo; <?=$___LANGUAGER->getText('index','show_page'); ?> &laquo;
            </a>
        <? } ?>
    </body>
</html>