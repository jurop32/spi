<?php
/*
 * Admin homepage
 */

//die if user accessing script by straight path
if(!defined('SYSTEM_VERSION')) die('Error: No permission!');

?>
<h3><?=$___LANGUAGER->getText('settings','name'); ?></h3>
<div id="listcontent">
    <div id="settings"></div>
    <? if($_SESSION[PAGE_SESSIONID]['privileges']['settings'][1] == '1'){ ?>
        <button class="ui-icon-pencil button right" onclick="editItems();"><?=$___LANGUAGER->getText('common','edit'); ?></button>
    <? } ?>
    <div class="clear"></div>
</div>
<div id="editorholder"></div>