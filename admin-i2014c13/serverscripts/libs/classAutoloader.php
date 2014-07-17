<?php

/**
 * Class autoloader registration file
 */

function classAutoloader($class) {
    
    $ld = '';
    //determine if it is called from frontend or backend
    if(defined('ADMIN_ABSDIRPATH')){
        $ld = ADMIN_ABSDIRPATH;
    }else{
        trigger_error("Please define ADMIN_ABSDIRPATH ", E_USER_WARNING);
        return false;
    }
    
    $ad = $ld.'serverscripts'.DIRECTORY_SEPARATOR;
    $lad = $ad.'libs'.DIRECTORY_SEPARATOR;
        
    if ((file_exists($ad . $class . '.php') && include_once($ad . $class . '.php'))) {
        return true;
    }else if((file_exists($lad . $class . '.php') && include_once($lad . $class . '.php'))){
        return true;
    }else if((file_exists($ld.'modules'.DIRECTORY_SEPARATOR.$class.DIRECTORY_SEPARATOR.'controller.php') && include_once($ld.'modules'.DIRECTORY_SEPARATOR.$class.DIRECTORY_SEPARATOR.'controller.php'))){
        return true;
    }else{
        trigger_error("The class '$class' failed to autoload", E_USER_WARNING);
        return false;
    }
}

// registering the class autoload function
spl_autoload_register('classAutoloader');