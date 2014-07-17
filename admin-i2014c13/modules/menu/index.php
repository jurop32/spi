<?php
/*
 * Menueditor page
 */

//die if user accessing script by straight path
if(!defined('SYSTEM_VERSION')) die('Error: No permission!');

?>
<div id="dialog-confirm" title="<?=$___LANGUAGER->getText('menu','t0'); ?>">
    <p>
        <span class="ui-icon ui-icon-alert"></span>
        <?=$___LANGUAGER->getText('menu','t1'); ?> <span class="itemname"></span> <?=$___LANGUAGER->getText('menu','t2'); ?><br />
        <?=$___LANGUAGER->getText('menu','t3'); ?>
        <? if($_SESSION[PAGE_SESSIONID]['privileges']['menu'][3] == '1'){ ?>
            <br /><br /><input type="checkbox" value="1" name="deletearticles" id="deletearticles" /> - <?=$___LANGUAGER->getText('menu','t4'); ?>
        <? } ?>
    </p>
</div>
<h3><?=$___LANGUAGER->getText('menu','name'); ?></h3>
<div id="listcontent">
    <div id="selectopts">
        <? if($___LANGUAGER->getLangsCount() > 1){ ?>
        <?=$___LANGUAGER->getText('menu','language'); ?>:
        <select class="ui-autocomplete-input ui-widget-content ui-corner-all" name="show_lang" id="show_lang" onchange="showItems();">
            <?
            echo $___LANGUAGER->printLanguagesComboboxElements();
            ?>
        </select>
        <? } ?>
        <div class="clear"></div>
    </div>
    <div id="listholder" class="ui-widget-content ui-corner-all">
    </div>
    <div class="help">
        <p>
            <b>W</b> - <?=$___LANGUAGER->getText('menu','h1'); ?><br />
            <b>P</b> - <?=$___LANGUAGER->getText('menu','h2'); ?><br />
            <b>N</b> - <?=$___LANGUAGER->getText('menu','h3'); ?><br />
            <b>M</b> - <?=$___LANGUAGER->getText('menu','h9'); ?><br />
            <b>F</b> - <?=$___LANGUAGER->getText('menu','h4'); ?>
        </p>
    </div>
    <? if($_SESSION[PAGE_SESSIONID]['privileges']['menu'][1] == '1'){ ?>
        <button class="ui-icon-circle-plus button right" onclick="newItem();"><?=$___LANGUAGER->getText('menu','new_item'); ?></button>
    <? } ?>       
    <div class="clear"></div>
</div>
<div id="editorholder"></div>
