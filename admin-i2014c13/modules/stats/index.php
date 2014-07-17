<?php
/*
 * Admin homepage
 */

//die if user accessing script by straight path
if(!isset($_SESSION[PAGE_SESSIONID]['id'])) die('Error: tno permission!');

?>
<h3><?=$___LANGUAGER->getText('stats','name'); ?></h3>
<div id="stats" class="backgroundlogo">
</div>