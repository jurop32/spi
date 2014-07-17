<?php
/*
 * returns the js global variables
 */

header("Content-type: application/x-javascript");

require_once dirname(__FILE__).'/../serverscripts/__loader.php';

$availablemodules = $___REGISTRY['modules']->getModuleIds();

?>
/*
 * options
 */
var mainpageurl = "<?= PAGE_URL; ?>";
var languages = [<?=implode(',',$___LANGUAGER->getLangIds()); ?>];
var adminlangcode = '<?=$___LANGUAGER->getAdminLangCode(); ?>';
var min_passwordlength = '<?=$___REGISTRY['configuration']->getSetting('MIN_PASSWORDLENGTH'); ?>';

/*
 * texts
 */
var texts = {
    'cancel': '<?=$___LANGUAGER->getText('common','cancel'); ?>',
<? if(in_array('slideshow',$availablemodules)){ ?>
    'slideshow_t27': '<?=$___LANGUAGER->getText('slideshow','t27'); ?>',
    'slideshow_t28': '<?=$___LANGUAGER->getText('slideshow','t28'); ?>',
<? } ?>
<? if(in_array('minislider',$availablemodules)){ ?>
    'minislider_t17': '<?=$___LANGUAGER->getText('minislider','t17'); ?>',
    'minislider_t18': '<?=$___LANGUAGER->getText('minislider','t18'); ?>',
<? } ?>
    'delete': '<?=$___LANGUAGER->getText('common','delete'); ?>'
}

/*
 * datepicker and timepicker translation
 */
jQuery(function($){
    
    //datepicker
    $.datepicker.regional[adminlangcode] = {
        closeText: '<?=$___LANGUAGER->getText('common','datetime.closeText'); ?>',
        prevText: '&#x3c;<?=$___LANGUAGER->getText('common','datetime.prevText'); ?>',
        nextText: '<?=$___LANGUAGER->getText('common','datetime.nextText'); ?>&#x3e;',
        currentText: '<?=$___LANGUAGER->getText('common','datetime.currentText'); ?>',
        monthNames: ['<?=implode("','",$___LANGUAGER->getText('common','months')); ?>'],
        monthNamesShort: ['<?=implode("','",$___LANGUAGER->getText('common','shortmonths')); ?>'],
        dayNames: ['<?=implode("','",$___LANGUAGER->getText('common','days')); ?>'],
        dayNamesShort: ['<?=implode("','",$___LANGUAGER->getText('common','shortdays')); ?>'],
        dayNamesMin: ['<?=implode("','",$___LANGUAGER->getText('common','extrashortdays')); ?>'],
        weekHeader: '<?=$___LANGUAGER->getText('common','datetime.weekHeader'); ?>',
        dateFormat: '<?=JSDATE_FORMAT; ?>',
        firstDay: <?=FIRSTDAY; ?>,
        showMonthAfterYear: false,
        yearSuffix: ''
    };
    $.datepicker.setDefaults($.datepicker.regional[adminlangcode]);
    
    //timepicker
    $.timepicker.regional[adminlangcode] = {
        timeOnlyTitle: '<?=$___LANGUAGER->getText('common','datetime.timeOnlyTitle'); ?>',
        timeText: '<?=$___LANGUAGER->getText('common','datetime.timeText'); ?>',
        hourText: '<?=$___LANGUAGER->getText('common','datetime.hourText'); ?>',
        minuteText: '<?=$___LANGUAGER->getText('common','datetime.minuteText'); ?>',
        secondText: '<?=$___LANGUAGER->getText('common','datetime.secondText'); ?>',
        millisecText: '<?=$___LANGUAGER->getText('common','datetime.millisecText'); ?>',
        timezoneText: '<?=$___LANGUAGER->getText('common','datetime.timezoneText'); ?>',
        currentText: '<?=$___LANGUAGER->getText('common','datetime.currentText'); ?>',
        closeText: '<?=$___LANGUAGER->getText('common','datetime.closeText'); ?>',
        timeFormat: '<?=JSTIME_FORMAT; ?>',
        amNames: ['<?=$___LANGUAGER->getText('common','datetime.amNames'); ?>', 'AM', 'A'],
        pmNames: ['<?=$___LANGUAGER->getText('common','datetime.pmNames'); ?>', 'PM', 'P']
    };
    $.timepicker.setDefaults($.timepicker.regional[adminlangcode]);
});