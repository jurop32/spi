<?php
/*
 * articles main page
 */

//die if user accessing script by straight path
if(!defined('SYSTEM_VERSION')) die('Error: No permission!');

?>
<div id="dialog-confirm" title="<?=$___LANGUAGER->getText('articles','t1'); ?>">
    <p>
        <span class="ui-icon ui-icon-alert"></span>
        <?=$___LANGUAGER->getText('articles','t2'); ?>: <span class="itemname"></span>?
    </p>
</div>
<script type="text/javascript" src="scripts/libs/tiny_mce/jquery.tinymce.js"></script>
<h3><?=$___LANGUAGER->getText('articles','name'); ?></h3>
<div id="listcontent">
    <div id="selectopts">
        <?=$___LANGUAGER->getText('common','category'); ?>:
        <select class="ui-autocomplete-input ui-widget-content ui-corner-all" name="kategoria" id="kategoria">
            <option value="-">*</option>
            <?php
                require_once ADMIN_ABSDIRPATH.'functions/getMenucategories.php';
                if($_SESSION[PAGE_SESSIONID]['privileges']['categoryaccess'] == 0) echo '<option value="0">'.$___LANGUAGER->getText('common','without_category').'</option>';
                echo getMenucategories($___LANGUAGER,true,'x',$_SESSION[PAGE_SESSIONID]['privileges']['categoryaccess']);
            ?>
        </select> &raquo; 
        <?=$___LANGUAGER->getText('common','order'); ?>:
        <select class="ui-autocomplete-input ui-widget-content ui-corner-all" name="order" id="order">
            <option value="id ASC"><?=$___LANGUAGER->getText('common','id'); ?> &#9650;</option>
            <option value="id DESC"><?=$___LANGUAGER->getText('common','id'); ?> &#9660;</option>
            <option value="article_title ASC"><?=$___LANGUAGER->getText('common','name'); ?> &#9650;</option>
            <option value="article_title DESC"><?=$___LANGUAGER->getText('common','name'); ?> &#9660;</option>
            <option value="viewcount ASC"><?=$___LANGUAGER->getText('common','opencount'); ?> &#9650;</option>
            <option value="viewcount DESC"><?=$___LANGUAGER->getText('common','opencount'); ?> &#9660;</option>
            <option value="article_createDate ASC"><?=$___LANGUAGER->getText('common','datecreated'); ?> &#9650;</option>
            <option value="article_createDate DESC" selected="selected"><?=$___LANGUAGER->getText('common','datecreated'); ?> &#9660;</option>
            <option value="id_menucategory ASC"><?=$___LANGUAGER->getText('common','category'); ?> &#9650;</option>
            <option value="id_menucategory DESC"><?=$___LANGUAGER->getText('common','category'); ?> &#9660;</option>
        </select> &raquo; 
        <?=$___LANGUAGER->getText('common','show'); ?> #:
        <select class="ui-autocomplete-input ui-widget-content ui-corner-all" name="resultcount" id="resultcount">
            <option value="10">10</option>
            <option value="20">20</option>
            <option value="50" selected="selected">50</option>
            <option value="100">100</option>
            <option value="200">200</option>
        </select> &raquo; 
        <?=$___LANGUAGER->getText('common','page'); ?>:
        <select class="ui-autocomplete-input ui-widget-content ui-corner-all" name="resultpage" id="resultpage">
        </select> &raquo; 
        <?=$___LANGUAGER->getText('common','name'); ?>: <input class="ui-autocomplete-input ui-widget-content ui-corner-all" type="text" name="nazov" id="nazov" />
        <button class="ui-icon-refresh button right" onclick="showItems();"><?=$___LANGUAGER->getText('common','show'); ?></button>
        <input value="1" type="checkbox" name="onlynotpublished" id="onlynotpublished" class="button" /><label for="onlynotpublished" class="right"><?=$___LANGUAGER->getText('common','notpublished'); ?></label>
        <div class="clear"></div>
    </div>
    <div id="listholder" class="ui-widget-content ui-corner-all"></div>
    <div class="help">
        <p>
            <b>L</b> - <?=$___LANGUAGER->getText('articles','t6'); ?><br />
            <b>W</b> - <?=$___LANGUAGER->getText('articles','t3'); ?><br />
            <b>P</b> - <?=$___LANGUAGER->getText('articles','t4'); ?><br />
            <b>T</b> - <?=$___LANGUAGER->getText('articles','t5'); ?><br />
            <b>H</b> - <?=$___LANGUAGER->getText('articles','h1'); ?>
        </p>
    </div>
    <? if($_SESSION[PAGE_SESSIONID]['privileges']['articles'][1] == '1' || $_SESSION[PAGE_SESSIONID]['privileges']['articles'][3] == '1'){ ?>
        <button class="ui-icon-circle-plus button right" onclick="newItem();"><?=$___LANGUAGER->getText('articles','new_article'); ?></button>
    <? } ?>       
    <div class="clear"></div>
</div>
<div id="editorholder"></div>
