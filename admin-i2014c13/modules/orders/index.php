<?php
/*
 * orders main page
 */

//die if user accessing script by straight path
if(!defined('SYSTEM_VERSION')) die('Error: No permission!');

?>
<div id="dialog-confirm" title="<?=$___LANGUAGER->getText('orders','t33'); ?>">
    <p>
        <span class="ui-icon ui-icon-alert"></span>
        <?=$___LANGUAGER->getText('orders','t8'); ?>: <span class="itemname"></span>?
    </p>
</div>
<h3><?=$___LANGUAGER->getText('orders','name'); ?></h3>
<div id="listcontent">
    <div id="selectopts">
        <?=$___LANGUAGER->getText('orders','t1'); ?>:
        <select class="ui-autocomplete-input ui-widget-content ui-corner-all" name="owner" id="owner">
            <option value="-">*</option>
            <?='<pre>'.print_r($___REGISTRY, 1).'</pre>'?>
            <?=$___REGISTRY['orders']->getUsersComboboxElements($_SESSION[PAGE_SESSIONID]['id']); ?>
        </select> &raquo;
        <?=$___LANGUAGER->getText('orders','t2'); ?>:
        <select class="ui-autocomplete-input ui-widget-content ui-corner-all" name="state" id="state">
            <option value="-">*</option>
            <option value="datum_platnosti<NOW()"><?=$___LANGUAGER->getText('orders','s_1'); ?></option>
            <option value="datum_platnosti>=NOW()"><?=$___LANGUAGER->getText('orders','s_2'); ?></option>
            <option value="ukoncene=1"><?=$___LANGUAGER->getText('orders','s_3'); ?></option>
        </select> &raquo;
        <?=$___LANGUAGER->getText('common','order'); ?>:
        <select class="ui-autocomplete-input ui-widget-content ui-corner-all" name="order" id="order">
            <option value="id ASC"><?=$___LANGUAGER->getText('common','id'); ?> &#9650;</option>
            <option value="id DESC" selected="selected"><?=$___LANGUAGER->getText('common','id'); ?> &#9660;</option>
            <option value="datum_pridania ASC"><?=$___LANGUAGER->getText('orders','t3'); ?> &#9650;</option>
            <option value="datum_pridania DESC"><?=$___LANGUAGER->getText('orders','t3'); ?> &#9660;</option>
            <option value="datum_platnosti ASC"><?=$___LANGUAGER->getText('orders','t4'); ?> &#9650;</option>
            <option value="datum_platnosti DESC"><?=$___LANGUAGER->getText('orders','t4'); ?> &#9660;</option>
            <option value="datum_ukoncenia ASC"><?=$___LANGUAGER->getText('orders','t5'); ?> &#9650;</option>
            <option value="datum_ukoncenia DESC"><?=$___LANGUAGER->getText('orders','t5'); ?> &#9660;</option>
        </select> &raquo;
        <?=$___LANGUAGER->getText('common','show'); ?> #:
        <select class="ui-autocomplete-input ui-widget-content ui-corner-all" name="resultcount" id="resultcount">
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50" selected="selected">50</option>
            <option value="75">75</option>
            <option value="100">100</option>
        </select> &raquo;
        <?=$___LANGUAGER->getText('common','page'); ?>:
        <select class="ui-autocomplete-input ui-widget-content ui-corner-all" name="resultpage" id="resultpage">
        </select>
        <button class="ui-icon-refresh button right" onclick="showItems();">
            <?=$___LANGUAGER->getText('common','show'); ?></button>
        <div class="clear"></div>
    </div>
    <div id="listholder" class="ui-widget-content ui-corner-all"></div>
    <? if ($_SESSION[PAGE_SESSIONID]['privileges']['orders'][1] == '1') { ?>
        <button class="ui-icon-circle-plus button right" onclick="checkItem()"><?= $___LANGUAGER->getText('orders','t6') ?></button>
    <? } ?>
    <div class="clear"></div>
</div>
<div id="editorholder"></div>
