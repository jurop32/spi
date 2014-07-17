<?php
/*
 * Admin homepage
 */

//die if user accessing script by straight path
if(!defined('SYSTEM_VERSION')) die('Error: No permission!');

?>
<h3><?= $___CONFIGURATION->getSetting('PAGE_TITLEPREFIX').' - '.$___LANGUAGER->getText('home','administration'); ?></h3>
<div id="homepage" class="backgroundlogo">
    <h4><?=$___LANGUAGER->getText('home','welcome').' '.$_SESSION[PAGE_SESSIONID]['firstname']; ?> (<?=$_SESSION[PAGE_SESSIONID]['username']; ?>)</h4>
    <?=$___LANGUAGER->getText('home','member_of_group'); ?> <b><?=$_SESSION[PAGE_SESSIONID]['usergroup']; ?></b>
    <div>
        <div class="clear"></div>
    </div>
</div>