<?php
/**
 * Runs the ajax action from user 
 */

require_once 'serverscripts/__loader.php';

if(!isset($_SESSION[PAGE_SESSIONID]['id'])) die($___REGISTRY['languager']->getText('common','sytem_logged_out'));

if(!isset($___REGISTRY['_POST']['method']) || !preg_match('/\w+->\w+/',$___REGISTRY['_POST']['method'])) die('{"state":"error","data":"'.$___REGISTRY['languager']->getText('common','wrong_params').'"}');

$method = explode('->',$___REGISTRY['_POST']['method']);
if($_SESSION[PAGE_SESSIONID]['privileges'][$method[0]][0] != '1') die($___REGISTRY['languager']->getText('common','dont_have_permission'));

$classpath = ADMIN_ABSDIRPATH.'modules'.DIRECTORY_SEPARATOR.$method[0].DIRECTORY_SEPARATOR.'controller.php';
if(!file_exists($classpath)) die('{"state":"error","data":"'.$___REGISTRY['languager']->getText('common','module_notfound').'"}');
        
require_once $classpath;
if(!method_exists($method[0], $method[1])) die('{"state":"error","data":"'.$___REGISTRY['languager']->getText('common','method_notfound').'"}');

unset($_POST['method']);
$___REGISTRY['userdata'] = array_merge($_GET,$_POST);
$class = new $method[0]($___REGISTRY);

//header('Content-Type: application/json');
die(preg_replace('/\s+/',' ',json_encode($class->$method[1]())));