<?php

/*
 * Generates the adminmenu
 */

class adminmenu extends mainclass{
    
    public function __construct($registry){
        parent::__construct($registry);
    }
    
    /*
     * Returns the generated amin menu
     */
    public function getAdminMenu(){
        $markup = '<div id="menu" class="ui-widget-header ui-corner-all"><ul>';
        $markup1 = '';

        $modules = $this->registry['modules']->getModules();
        
        foreach($modules as $moduleid => $module){
            if(isset($module['id'])){
                if($_SESSION[PAGE_SESSIONID]['privileges'][$moduleid][0] == 1){
                    $markup .= '<li class="ui-state-default ui-corner-all"><a href="index.php?page='.$moduleid.'" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-'.$module['icon'].'"></span>'.$this->languager->getText($moduleid,'name').'</a></li>';
                }
            }else{
                foreach($module as $submoduleid => $submodule){
                    if($_SESSION[PAGE_SESSIONID]['privileges'][$submoduleid][0] == 1){
                        $markup1 .= '<li><a href="index.php?page='.$submoduleid.'"><span class="ui-icon ui-icon-'.$submodule['icon'].'"></span>'.$this->languager->getText($submoduleid,'name').'</a></li>';
                    }
                }
                if($markup1 != ''){
                    $markup .= '<li class="ui-state-default ui-corner-all"><span class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-carat-1-s"></span>'.$this->text('menugroups.'.$moduleid).'</span><ul>'.$markup1.'</ul></li>';
                    $markup1 = '';
                }
            }
        }
        $markup .= '</ul></div>';
        
        return $markup;
    }
}