<?php
/*
 * Languages page
 */

//die if user accessing script by straight path
if(!defined('SYSTEM_VERSION')) die('Error: No permission!');

?>
<div id="dialog-confirm" title="<?=$___LANGUAGER->getText('languages','t1'); ?>">
    <p>
        <span class="ui-icon ui-icon-alert"></span>
        <?=$___LANGUAGER->getText('languages','t2'); ?> <span class="itemname"></span>? <?=$___LANGUAGER->getText('languages','t3'); ?>
    </p>
</div>
<h3><?=$___LANGUAGER->getText('languages','name'); ?></h3>
<div id="listcontent">
    <div id="listholder" class="ui-widget-content ui-corner-all">
    </div>
    <div class="help">
        <p>
            <b>D</b> - <?=$___LANGUAGER->getText('languages','t4'); ?><br />
            <b>P</b> - <?=$___LANGUAGER->getText('languages','t5'); ?>
        </p>
    </div>
    <? if($_SESSION[PAGE_SESSIONID]['privileges']['languages'][1] == '1'){ ?>
        <button class="ui-icon-circle-plus button right" onclick="newItem();"><?=$___LANGUAGER->getText('languages','new_language'); ?></button>
    <? } ?>       
    <div class="clear"></div>
</div>
<div id="editorholder"></div>
