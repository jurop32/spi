<?php
/*
 * Help
 */

class home extends maincontroller{
    
    public function __construct($registry){
        parent::__construct($registry);
    }
    
    /*
     * returning items
     */
    public function getItems(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['home'][0] == '0') die($this->text('dont_have_permission'));
        
        $modules = $this->registry['modules']->getModules();
        $markup1 = '';
        $markup2 = '';
        $markup3 = '';
        
        foreach($modules as $moduleid => $module){
            if(isset($module['id'])){
                if($_SESSION[PAGE_SESSIONID]['privileges'][$moduleid][0] == 1){
                    $markup1 .= '<div class="right ui-corner-all ui-state-default"><a href="index.php?page='.$moduleid.'" class="home-icon hi-'.$moduleid.' ui-corner-all">'.$this->languager->getText($moduleid,'name').'</a></div>';
                }
            }else{
                foreach($module as $submoduleid => $submodule){
                    if($_SESSION[PAGE_SESSIONID]['privileges'][$submoduleid][0] == 1){
                        $markup3 .= '<div class="left ui-corner-all ui-state-default"><a href="index.php?page='.$submoduleid.'" class="home-icon hi-'.$submoduleid.' ui-corner-all">'.$this->languager->getText($submoduleid,'name').'</a></div>';
                    }
                }
                if($markup3 != ''){
                    $markup2 .= '<div class="left"><h5>'.$this->languager->getText('adminmenu','menugroups.'.$moduleid).'</h5>'.$markup3.'<div class="clear"></div> </div>';
                    $markup3 = '';
                }
            }
        }
        
        $markup = '<div class="left">'.$markup2.'</div><div class="right">'.$markup1.'</div>';
        
        return array('state'=>'ok','data'=> $markup);
    }
}