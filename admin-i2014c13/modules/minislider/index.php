<?php
/*
 * Slideshow page
 */

//die if user accessing script by straight path
if(!defined('SYSTEM_VERSION')) die('Error: No permission!');

?>
<div id="dialog-confirm" title="<?=$___LANGUAGER->getText('minislider','t1'); ?>">
    <p>
        <span class="ui-icon ui-icon-alert"></span>
        <?=$___LANGUAGER->getText('minislider','t2'); ?>: <span class="itemname"></span>?<br /><br />
        <input type="checkbox" value="1" name="deletefiles" id="deletefiles" /> - <?=$___LANGUAGER->getText('minislider','t3'); ?>
    </p>
</div>
<h3><?=$___LANGUAGER->getText('minislider','name'); ?></h3>
<div id="listcontent"><div id="selectopts">
        <? if($___LANGUAGER->getLangsCount() > 1){ ?>
        <?=$___LANGUAGER->getText('minislider','language'); ?>:
        <select class="ui-autocomplete-input ui-widget-content ui-corner-all" name="show_lang" id="show_lang" onchange="showItems();">
            <?
            echo $___LANGUAGER->printLanguagesComboboxElements();
            ?>
        </select>
        <? } ?>
        <div class="clear"></div>
    </div>
    <div id="listholder" class="ui-widget-content ui-corner-all"></div>
    <div class="help">
        <p>
            <b>A</b> - <?=$___LANGUAGER->getText('minislider','h0'); ?>
        </p>
    </div>
    <? if($_SESSION[PAGE_SESSIONID]['privileges']['minislider'][1] == '1'){ ?>
        <button class="ui-icon-circle-plus button right" onclick="newItem();"><?=$___LANGUAGER->getText('minislider','t4'); ?></button>
    <? } ?>       
    <div class="clear"></div>
</div>
<div id="editorholder"></div>
