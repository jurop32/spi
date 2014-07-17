<?php
/*
 * docs
 */

class docs extends maincontroller{
    
    public function __construct($registry){
        parent::__construct($registry);
    }
    
    /*
     * returning items
     */
    public function getItems(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['docs'][0] == '0') die($this->text('dont_have_permission'));
        
        $modules = $this->registry['modules']->getModules();
        
        $markup = '<table class="infotable">
            <tr>
                <th class="ui-corner-all ui-state-hover">'.$this->text('module').'</th>
                <th class="ui-corner-all ui-state-hover">'.$this->text('url').'</th>
            </tr>
        ';
        $markup1 = '';
        
        foreach($modules as $moduleid => $module){
            if(isset($module['id'])){
                if($_SESSION[PAGE_SESSIONID]['privileges'][$moduleid][0] == 1){
                    $markup .= '<tr>';
                    $markup .= '<td><b>'.$this->languager->getText($moduleid,'name').'</b></td>';
                    $markup .= '<td>'.((isset($module['help']))?'<a href="'.$module['help'].'" target="_blank">'.$module['help'].'</a>':'').'</td>';
                    $markup .= '</tr>';
                }
            }else{
                foreach($module as $submoduleid => $submodule){
                    if($_SESSION[PAGE_SESSIONID]['privileges'][$submoduleid][0] == 1){
                        $markup1 .= '<tr>';
                        $markup1 .= '<td> &#8226; '.$this->languager->getText($submoduleid,'name').'</td>';
                        $markup1 .= '<td>'.((isset($submodule['help']))?'<a href="'.$submodule['help'].'" target="_blank">'.urldecode($submodule['help']).'</a>':'').'</td>';
                        $markup1 .= '</tr>';
                    }
                }
                if($markup1 != ''){
                    $markup .= '<tr>';
                    $markup .= '<td><b>'.$this->languager->getText('adminmenu','menugroups.'.$moduleid).'</b></td>';
                    $markup .= '<td></td>';
                    $markup .= '</tr>';
                    $markup .= $markup1;
                    $markup1 = '';
                }
            }
        }
                
        $markup .= '</table>';
        
        return array('state'=>'ok','data'=> $markup);
    }
}