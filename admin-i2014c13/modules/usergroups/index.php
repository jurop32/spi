<?php
/*
 * Usergroupseditor mainpage
 */

//die if user accessing script by straight path
if(!defined('SYSTEM_VERSION')) die('Error: No permission!');

?>
<div id="dialog-confirm" title="<?=$___LANGUAGER->getText('usergroups','t1'); ?>">
    <p>
        <span class="ui-icon ui-icon-alert"></span>
        <?=$___LANGUAGER->getText('usergroups','t2'); ?>: <span class="itemname"></span>? <?=$___LANGUAGER->getText('usergroups','t3'); ?>
        <? if(in_array('orders',$___REGISTRY['modules']->getModuleIds())){ ?>
            <br /><br /><?=$___LANGUAGER->getText('users','t35'); ?>
        <? } ?>
        <? if($_SESSION[PAGE_SESSIONID]['privileges']['usergroups'][4] == '1'){ ?>
            <br /><br /><input type="checkbox" value="1" name="deletearticles" id="deletearticles" /> - <?=$___LANGUAGER->getText('usergroups','t4'); ?>
        <? } ?>
    </p>
</div>
<h3><?=$___LANGUAGER->getText('usergroups','name'); ?></h3>
<div id="listcontent">
    <div id="listholder" class="ui-widget-content ui-corner-all"></div>
    <div class="help">
        <p>
            <b>D</b> - <?=$___LANGUAGER->getText('usergroups','h1'); ?>
        </p>
    </div>
    <? if($_SESSION[PAGE_SESSIONID]['privileges']['usergroups'][1] == '1'){ ?>
        <button class="ui-icon-circle-plus button right" onclick="newItem();"><?=$___LANGUAGER->getText('usergroups','t5'); ?></button>
    <? } ?>       
    <div class="clear"></div>
</div>
<div id="editorholder"></div>
