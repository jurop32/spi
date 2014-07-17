<?php
/*
 * Tools controller
 */

class tools extends maincontroller{
    
    public function __construct($registry){
        parent::__construct($registry);
    }
    
    /*
     * returns the homescreen
     */
    public function getItems(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['tools'][0] == '0') die($this->text('dont_have_permission'));
        
        $markup = '
            <fieldset>
                <legend>'.$this->text('t1').':</legend>
                '.$this->text('t2').': <b id="cachedpagecount">'.$this->getCachedPagesCount().'</b> 
                <button class="ui-icon-trash button right" onclick="clearPageCache();"'.(($this->configuration->getSetting('CACHING') !== true)?' disabled="disabled">'.$this->text('t3'):'>'.$this->text('t4')).'</button>
                <div class="clear"></div>
            </fieldset>
            <fieldset>
                <legend>'.$this->text('t5').':</legend>
                '.$this->text('t6').': <b id="logerrorcount">'.$this->getLogErrorsCount().'</b>
                <button class="ui-icon-trash button right" onclick="clearErrorLog();">'.$this->text('t7').'</button>
                <button class="ui-icon-document-b button right" onclick="showErrorLog();">'.$this->text('t8').'</button>
                <div class="clear"></div>
            </fieldset>
            <fieldset>
                <legend>'.$this->text('t9').':</legend>
                '.$this->text('t10').': <b id="logerrorcount">'.SYSTEM_CHANGEVERSION.'</b>
                <button class="ui-icon-document-b button right" onclick="showChangeLog();">'.$this->text('t11').'</button>
                <div class="clear"></div>
            </fieldset>';
        
        return array('state'=>'ok','data'=> $markup);
    }
    
    /*
     * clears the page cachedir
     */
    public function clearPageCache(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['tools'][0] == '0') die($this->text('dont_have_permission'));
        
        $dirname = FRONTEND_ABSDIRPATH.CACHEDIR;
        $files = scandir($dirname);
        if(count($files) > 2){
            foreach($files as $file){
                $filepath = $dirname.DIRECTORY_SEPARATOR.$file;
                if($file != '.' && $file != '..' && !is_dir($filepath)){
                    $result = unlink($filepath);
                    if(!$result) return array('state'=>'error','data'=> $this->text('e1').': '.CACHEDIR);
                }
            }
        }
        return array('state'=>'highlight','data'=> $this->text('s1'));
    }
    
    /*
     * clears the error log file
     */
    public function clearErrorLog(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['tools'][0] == '0') die($this->text('dont_have_permission'));
        
        $handle = @fopen(ADMIN_ABSDIRPATH.'error.log','w');
        if($handle != false){
            $result = fclose($handle);
            if($result != false){
                return array('state'=>'highlight','data'=> $this->text('s2'));
            }else{
                return array('state'=>'error','data'=> $this->text('e2'));
            }
        }else{
            return array('state'=>'error','data'=> $this->text('e3'));
        }
    }
    
    /*
     * shows the error log contents
     */
    public function showErrorLog(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['tools'][0] == '0') die($this->text('dont_have_permission'));
        
        $filecontents = file(ADMIN_ABSDIRPATH.'error.log');
        $markup = '<div>
            <h4 class="left">'.$this->text('t5').'</h4>
            <button class="button right" onclick="cancelAction();">'.$this->text('ok').'</button>
            <div class="clear"></div>
            <div id="logfilecontents" class="ui-widget-content ui-corner-all">
            ';
        if(count($filecontents) > 0){
            foreach($filecontents as $id => $line){
                $markup .= '<span>'.str_replace(' ','&nbsp;',str_pad((String)($id+1).'.', 6, " ", STR_PAD_RIGHT)).'</span>';
                if(substr($line,0,1) != '['){
                    $markup .= '&nbsp;<span>&rarr;</span> &nbsp; &nbsp;'.$line;
                }else{
                    $markup .= $line;
                }
                $markup .= '<br />';
            }
        }else{
            $markup .= $this->text('t12');
        }
        $markup .= '</div>
            <button class="button right" onclick="cancelAction();">'.$this->text('ok').'</button>
            <div class="clear"></div>
            </div>';
        
        return array('state'=>'ok','data'=> $markup);
    }
    
    /*
     * shows the change log contents
     */
    public function showChangeLog(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['tools'][0] == '0') die($this->text('dont_have_permission'));
        
        $filecontents = file(ADMIN_ABSDIRPATH.'change.log');
        $markup = '<div>
            <h4 class="left">'.$this->text('t9').'</h4>
            <button class="button right" onclick="cancelAction();">'.$this->text('ok').'</button>
            <div class="clear"></div>
            <div id="logfilecontents" class="ui-widget-content ui-corner-all">
            ';
        if(count($filecontents) > 0){
            foreach($filecontents as $id => $line){
                $markup .= '<span>'.str_replace(' ','&nbsp;',str_pad((String)($id+1).'.', 6, " ", STR_PAD_RIGHT)).'</span>'.$line.'<br />';
            }
        }else{
            $markup .= $this->text('t13');
        }
        $markup .= '</div>
            <button class="button right" onclick="cancelAction();">'.$this->text('ok').'</button>
            <div class="clear"></div>
            </div>';
        
        return array('state'=>'ok','data'=> $markup);
    }
    
    /*
     * returns the errors count in the log file
     */
    private function getLogErrorsCount(){
        $logerrorcount = 0;
        if(file_exists(ADMIN_ABSDIRPATH.'error.log')){
            $logerrorcount = 0;
            $lines = file(ADMIN_ABSDIRPATH.'error.log');
            foreach($lines as $line){
                if(substr($line,0,1) == '['){
                    $logerrorcount++;
                }
            }
        }
        return $logerrorcount;
    }
    
    /*
     * returns the count of cached files
     */
    private function getCachedPagesCount(){
        return (count(scandir(FRONTEND_ABSDIRPATH.CACHEDIR)) - 2);
    }
}