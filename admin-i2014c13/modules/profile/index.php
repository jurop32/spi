<?php
/*
 * Usereditor mainpage
 */

//die if user accessing script by straight path
if(!defined('SYSTEM_VERSION')) die('Error: No permission!');

?>
<h3><?=$___LANGUAGER->getText('profile','name'); ?></h3>
<div id="listcontent">
    <div id="vcardholder"></div>
    <button class="ui-icon-pencil button right" onclick="editItem();"><?=$___LANGUAGER->getText('common','edit'); ?></button>
    <div class="clear"></div>
</div>
<div id="editorholder"></div>
