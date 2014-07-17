<?php

/**
 * Runs the page loading preprocess
 */

//need php version 5.3 or higher
if(version_compare(PHP_VERSION,'5.3') < 0) die('You need PHP version larger than 5.3 to run this CMS.');

// reading configuration
require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'configuration.inc.php';

//error reporting, disable on production
if(DEVELOPMENT_STATUS == true){
    ini_set('error_reporting', E_ALL);
    error_reporting(E_ALL);
    ini_set('display_errors',true);
}else{
    ini_set('error_reporting', E_ALL);
    error_reporting(E_ALL);
    ini_set('log_errors',true);
    ini_set('html_errors',false);
    ini_set('error_log',ADMIN_ABSDIRPATH.'error.log');
    ini_set('display_errors',false);
}

//starting session
session_start();

//class autoloader
require_once ADMIN_ABSDIRPATH.'serverscripts/libs/classAutoloader.php';
//connecting to DB
require_once ADMIN_ABSDIRPATH.'serverscripts/connect.php';

//global registry array
$___REGISTRY = new registry();
$___REGISTRY['_POST'] = $_POST;
$___REGISTRY['_GET'] = $_GET;
$___REGISTRY['_COOKIE'] = $_COOKIE;
//$___REGISTRY['configuration'] = new configuration();
//$___REGISTRY['languager'] = new languager();

$___CONFIGURATION = $___REGISTRY['configuration'];
$___LANGUAGER = $___REGISTRY['languager'];

//sending proper header
header("HTTP/1.0 200 OK");
header('X-Powered-By: '.SYSTEM_VERSION.':'.SYSTEM_CHANGEVERSION);
header("Content-Type: text/html; charset=UTF-8");

//setting timezone
date_default_timezone_set(CMS_TIMEZONE);