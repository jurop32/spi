<?php
/*
 * Bannereditor page
 */

//die if user accessing script by straight path
if(!defined('SYSTEM_VERSION')) die('Error: No permission!');

?>
<div id="dialog-confirm" title="<?=$___LANGUAGER->getText('banners','t3'); ?>">
    <p>
        <span class="ui-icon ui-icon-alert"></span>
        <?=$___LANGUAGER->getText('banners','t4'); ?>: <span class="itemname"></span>?<br /><br />
        <input type="checkbox" value="1" name="deletefiles" id="deletefiles" /> - <?=$___LANGUAGER->getText('banners','t5'); ?>
    </p>
</div>
<h3><?=$___LANGUAGER->getText('banners','name'); ?></h3>
<div id="listcontent">
    <div id="listholder" class="ui-widget-content ui-corner-all">
    </div>
    <div class="help">
        <p>
            <b>A</b> - <?=$___LANGUAGER->getText('banners','t6'); ?><br />
            <b>W</b> - <?=$___LANGUAGER->getText('banners','t7'); ?>
        </p>
    </div>
    <? if($_SESSION[PAGE_SESSIONID]['privileges']['banners'][1] == '1'){ ?>
        <button class="ui-icon-circle-plus button right" onclick="newItem();"><?=$___LANGUAGER->getText('banners','new_banner'); ?></button>
    <? } ?>       
    <div class="clear"></div>
</div>
<div id="editorholder"></div>
