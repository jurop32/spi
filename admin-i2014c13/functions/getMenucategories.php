<?php
/*
 * Returning the menucategories combobox
 */

function getMenucategories($languager, $disablealias = false, $active_category = 'x',$forparent = 0){
    
    require_once ADMIN_ABSDIRPATH.'serverscripts/configuration.inc.php';
    require_once ADMIN_ABSDIRPATH.'serverscripts/connect.php';
        
    $markup = '';
    
    if($forparent != 0){
        $temp = dibi::query("SELECT * FROM ".DB_TABLEPREFIX."MENU ORDER BY orderno ASC");
        $menuitems = $temp->fetchAssoc('id');
        $markup .= '<option value="'.$forparent.'">'.$menuitems[$forparent]['name'].'</option>';
        if(count($menuitems) > 0){
            $markup .= getMenuLevel($forparent,$menuitems,'&nbsp;&nbsp;&nbsp;&nbsp;',$active_category,$disablealias);
        }
    }else{
        if($languager->siteMultilanguage()){
            foreach($languager->getLangs() as $language){
                $temp = dibi::query("SELECT * FROM ".DB_TABLEPREFIX."MENU ORDER BY orderno ASC");
                $menuitems = $temp->fetchAssoc('id');
                $markup .= '<optgroup label="'.$language['name'].'" title="'.$language['id'].'">';
                if(count($menuitems) > 0){
                    $markup .= getMenuLevel($forparent,$menuitems,'&nbsp;&nbsp;&nbsp;&nbsp;',$active_category,$disablealias);
                }
                $markup .= '</optgroup>';
            }
        }else{
            $temp = dibi::query("SELECT * FROM ".DB_TABLEPREFIX."MENU WHERE lang=".$languager->getDefaultLangId()." ORDER BY orderno ASC");
            $menuitems = $temp->fetchAssoc('id');
            if(count($menuitems) > 0){
                $markup .= getMenuLevel($forparent,$menuitems,'&nbsp;&nbsp;&nbsp;&nbsp;',$active_category,$disablealias);
            }
        }
    }
    
    return $markup;
}

//getting menuelements recursilevly
function getMenuLevel($parentid, $elements, $prefix, $active_category, $disablealias) {
    
    $menupiece = '';
    foreach ($elements as $element) {
        if ($element['parentid'] == $parentid) {
            $menupiece .= '<option value="' . $element['id'] . '"';
            if($element['id'] == $active_category) $menupiece .= ' selected="selected"';
            if($disablealias == true && $element['type'] != 's') $menupiece .= ' disabled="disabled"';
            $menupiece .= '>' . $prefix;
            if($element['type'] != 's'){
                $menupiece .= '(Alias) ';
            }
            $menupiece .= $element['name'];
            if($element['published'] == 0){
                $menupiece .= '-NP';
            }
            /*if($element['display_new_articles'] == 0){
                $menupiece .= '-N';
            }*/
            $menupiece .= '</option>';
            $menupiece .= getMenuLevel($element['id'], $elements, $prefix . '&nbsp;&nbsp;&nbsp;&nbsp;',$active_category, $disablealias);
        }
    }

    return $menupiece;
}
?>
