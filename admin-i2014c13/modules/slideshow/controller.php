<?php

/*
 * Slideshow editor
 */

class slideshow extends maincontroller{
    
    /*
     * constructor
     */
    public function __construct($registry){
        parent::__construct($registry);
    }
    
    /*
     * returning items
     */
    public function getItems(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['slideshow'][0] == '0') die($this->text('dont_have_permission'));
        
        $where = '';
        if(isset($this->data['lang']) && $this->data['lang'] != ''){
            $where .= " AND lang='".$this->data['lang']."'";
        }
        
        $markup = '<table>';
        $markup .= '<tr><th colspan="10">'.$this->text('t12').':</th></tr>';
        
        $temp = dibi::query("SELECT * FROM ". DB_TABLEPREFIX ."SLIDESHOW WHERE publish_from<='".date('Y-m-d')."' AND publish_to>='".date('Y-m-d')."'".$where." ORDER BY orderno ASC");
        $slides = $temp->fetchAll();
        
        if (count($slides) > 0) {
            
            $markup .= '<tr>
                <th class="control ui-corner-all ui-state-hover">'.$this->text('id').'</th>
                <th class="control ui-corner-all ui-state-hover">'.$this->text('t5').'</th>
                <th class="text ui-corner-all ui-state-hover">'.$this->text('t6').'</th>
                <th class="text ui-corner-all ui-state-hover">'.$this->text('t7').'</th>
                <th class="text ui-corner-all ui-state-hover">'.$this->text('t8').'</th>
                <th class="control ui-corner-all ui-state-hover">'.$this->text('t9').'</th>
                <th class="control ui-corner-all ui-state-hover">'.$this->text('t10').'</th>';
                if($_SESSION[PAGE_SESSIONID]['privileges']['slideshow'][1] == '1'){
                    $markup .= '<th class="control ui-corner-all ui-state-hover"><span class="ui-icon ui-icon-carat-2-n-s" title="'.$this->text('t11').'"></span></th>
                    <th class="control ui-corner-all ui-state-hover"><span class="ui-icon ui-icon-pencil" title="'.$this->text('edit').'"></span></th>
                    <th class="control ui-corner-all ui-state-hover"><span class="ui-icon ui-icon-trash" title="'.$this->text('delete').'"></span></th>';
                }
                $markup .= '</tr>';
            foreach($slides as $slide){
                $markup .= '<tr>';
                $markup .= '<td>'.$slide['id'].'</td>';
                $markup .= '<td><img class="thumb" src="'.((substr($slide['file'],0,7) == 'http://')?$slide['file']:PAGE_URL.SLIDESHOWIMAGESPATH.$slide['file']).'" /></td>';
                $markup .= '<td class="text">'.$slide['heading'].'</td>';
                $markup .= '<td class="text">'.$slide['description'].'</td>';
                $markup .= '<td class="text">'.$slide['link'].'</td>';
                $markup .= '<td>'.date('d.m.Y',strtotime($slide['publish_from'])).'</td>';
                $markup .= '<td>'.date('d.m.Y',strtotime($slide['publish_to'])).'</td>';
                if($_SESSION[PAGE_SESSIONID]['privileges']['slideshow'][1] == '1'){
                    $markup .= '<td><button class="ui-icon-carat-2-n-s notext button" onclick="exchangeItems('.$slide['id'].');">'.$this->text('t11').'</button></td>';
                    $markup .= '<td><button class="ui-icon-pencil notext button" onclick="editItem('.$slide['id'].');">'.$this->text('edit').'</button></td>';
                    $markup .= '<td><button class="ui-icon-trash notext button" onclick="confirmDeleteItem('.$slide['id'].');">'.$this->text('delete').'</button></td>';
                }
                $markup .= '</tr>';
            }
        } else {
            $markup .= '<tr><td colspan="10">'.$this->text('t13').'</td></tr>';
        }
        
        $markup .= '<tr><td colspan="10" style="height:20px;"></td></tr><tr><th colspan="10">'.$this->text('t14').':</th></tr>';
        
        $temp = dibi::query("SELECT * FROM ". DB_TABLEPREFIX ."SLIDESHOW WHERE publish_from>'".date('Y-m-d')."' OR publish_to<'".date('Y-m-d')."'".$where." ORDER BY orderno ASC");
        $slides = $temp->fetchAll();

        if (count($slides) > 0) {
        
            $markup .= '<tr>
                <th class="control ui-corner-all ui-state-hover">'.$this->text('id').'</th>
                <th class="control ui-corner-all ui-state-hover">'.$this->text('t5').'</th>
                <th class="text ui-corner-all ui-state-hover">'.$this->text('t6').'</th>
                <th class="text ui-corner-all ui-state-hover">'.$this->text('t7').'</th>
                <th class="text ui-corner-all ui-state-hover">'.$this->text('t8').'</th>
                <th class="control ui-corner-all ui-state-hover">'.$this->text('t9').'</th>
                <th class="control ui-corner-all ui-state-hover">'.$this->text('t10').'</th>';
                if($_SESSION[PAGE_SESSIONID]['privileges']['slideshow'][1] == '1'){
                    $markup .= '<th class="control ui-corner-all ui-state-hover"><span class="ui-icon ui-icon-carat-2-n-s" title="'.$this->text('t11').'"></span></th>
                    <th class="control ui-corner-all ui-state-hover"><span class="ui-icon ui-icon-pencil" title="'.$this->text('edit').'"></span></th>
                    <th class="control ui-corner-all ui-state-hover"><span class="ui-icon ui-icon-trash" title="'.$this->text('delete').'"></span></th>';
                }
                $markup .= '</tr>';
            foreach($slides as $slide){
                $markup .= '<tr>';
                $markup .= '<td>'.$slide['id'].'</td>';
                $markup .= '<td><img class="thumb" src="'.((substr($slide['file'],0,7) == 'http://')?$slide['file']:PAGE_URL.SLIDESHOWIMAGESPATH.$slide['file']).'" /></td>';
                $markup .= '<td class="text">'.$slide['heading'].'</td>';
                $markup .= '<td class="text">'.$slide['description'].'</td>';
                $markup .= '<td class="text">'.$slide['link'].'</td>';
                $markup .= '<td>'.date('d.m.Y',strtotime($slide['publish_from'])).'</td>';
                $markup .= '<td>'.date('d.m.Y',strtotime($slide['publish_to'])).'</td>';
                if($_SESSION[PAGE_SESSIONID]['privileges']['slideshow'][1] == '1'){
                    $markup .= '<td>-</td>';
                    $markup .= '<td><button class="ui-icon-pencil notext button" onclick="editItem('.$slide['id'].');">'.$this->text('edit').'</button></td>';
                    $markup .= '<td><button class="ui-icon-trash notext button" onclick="confirmDeleteItem('.$slide['id'].');">'.$this->text('delete').'</button></td>';
                }
                $markup .= '</tr>';
            }
        } else {
            $markup .= '<tr><td colspan="10">'.$this->text('t15').'</td></tr>';
        }
        $markup .= '</table>';
            
        return array('state'=>'ok','data'=> $markup);
    }
    
    /*
     * returning form for adding
     */
    public function getNewItemForm(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['slideshow'][1] == '0') die($this->text('dont_have_permission'));
        
        $data = array();
        if(isset($this->data['lang']) && $this->data['lang'] != ''){
            $data['lang'] = $this->data['lang'];
        }
        
        $markup = '<div><h4 class="left">'.$this->text('t4').'</h4>';
        $markup .= $this->getForm('add',$data);
        $markup .= '</div>';
        return array('state'=>'ok','data'=> $markup);
    }
    
    /*
     * returning form for editing
     */
    public function getEditItemForm(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['slideshow'][1] == '0') die($this->text('dont_have_permission'));
        
        $this->datavalidator->addValidation('id','req',$this->text('e1'));
        $this->datavalidator->addValidation('id','numeric',$this->text('e2'));
        $result = $this->datavalidator->ValidateData($this->data);
        if(!$result){
            $errors = $this->datavalidator->GetErrors();
            return array('state'=>'error','data'=> reset($errors));
        }

        $temp = dibi::query('SELECT * FROM ' . DB_TABLEPREFIX . 'SLIDESHOW WHERE id='.$this->data['id']);
        $item = $temp->fetchAssoc('id');
        if(count($temp) == 1){
            $item = $item[$this->data['id']];
            $markup = '<div><h4 class="left">'.$this->text('t16').': '.$item['id'].'</h4>';
            $markup .= $this->getForm('update',$item);
            $markup .= '</div>';
            return array('state'=>'ok','data'=> $markup);
        }
    }
    
    /*
     * adding item to db
     */
    public function addItem(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['slideshow'][1] == '0') die($this->text('dont_have_permission'));
        
        //checking if data is valid
        $result = $this->checkData();
        if($result !== true) return $result;
        
        //checking date correctness
        $fdcomponents = explode('.',$this->data['publish_from']);
        if(!checkdate($fdcomponents[1],$fdcomponents[0],$fdcomponents[2])){
            $this->data['publish_from'] = date('Y-m-d');
        }else{
            $this->data['publish_from'] = $fdcomponents[2].'-'.$fdcomponents[1].'-'.$fdcomponents[0];
        }
        $tdcomponents = explode('.',$this->data['publish_to']);
        if(!checkdate($tdcomponents[1],$tdcomponents[0],$tdcomponents[2]) || strtotime($tdcomponents[2].'-'.$tdcomponents[1].'-'.$tdcomponents[0]) < strtotime($this->data['publish_from'])){
            $this->data['publish_to'] = date('Y-m-d',strtotime("+1 week"));
        }else{
            $this->data['publish_to'] = $tdcomponents[2].'-'.$tdcomponents[1].'-'.$tdcomponents[0];
        }
        
        if($this->languager->getLangsCount() < 2){
            $this->data['lang'] = $this->languager->getDefaultLangId();
        }

        $temp = dibi::query("INSERT INTO " . DB_TABLEPREFIX . "SLIDESHOW ",$this->data);
        $returnvalue = array();
        if ($temp == 0 || $temp == 1) {
            //setting orderno
            dibi::query("UPDATE " . DB_TABLEPREFIX . "SLIDESHOW SET orderno=id WHERE id=".dibi::insertId());
            $returnvalue = array('state'=>'highlight','data'=> $this->text('s1'));
        }else {
            $returnvalue = array('state'=>'error','data'=> $this->text('e5'));
        }

        return $returnvalue;
    }
    
    /*
     * updating item in db
     */
    public function updateItem(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['slideshow'][1] == '0') die($this->text('dont_have_permission'));

        //checking if data is valid
        $result = $this->checkData();
        if($result !== true) return $result;

        $this->datavalidator->addValidation('id','req',$this->text('e1'));
        $this->datavalidator->addValidation('id','numeric',$this->text('e2'));
        $result = $this->datavalidator->ValidateData($this->data);
        if(!$result){
            $errors = $this->datavalidator->GetErrors();
            return array('state'=>'error','data'=> reset($errors));
        }

        $id = $this->data['id'];
        unset($this->data['id']);
        
        //checking date correctness
        $fdcomponents = explode('.',$this->data['publish_from']);
        if(!checkdate($fdcomponents[1],$fdcomponents[0],$fdcomponents[2])){
            $this->data['publish_from'] = date('Y-m-d');
        }else{
            $this->data['publish_from'] = $fdcomponents[2].'-'.$fdcomponents[1].'-'.$fdcomponents[0];
        }
        $tdcomponents = explode('.',$this->data['publish_to']);
        if(!checkdate($tdcomponents[1],$tdcomponents[0],$tdcomponents[2]) || strtotime($tdcomponents[2].'-'.$tdcomponents[1].'-'.$tdcomponents[0]) < strtotime($this->data['publish_from'])){
            $this->data['publish_to'] = date('Y-m-d',strtotime("+1 week"));
        }else{
            $this->data['publish_to'] = $tdcomponents[2].'-'.$tdcomponents[1].'-'.$tdcomponents[0];
        }
        
        if($this->languager->getLangsCount() < 2){
            $this->data['lang'] = $this->languager->getDefaultLangId();
        }

        $temp = dibi::query("UPDATE " . DB_TABLEPREFIX . "SLIDESHOW SET ",$this->data,'WHERE id='.$id);
        $returnvalue = array();
        if ($temp == 0 || $temp == 1){
            $returnvalue = array('state'=>'highlight','data'=> $this->text('s2'));
        }else {
            $returnvalue = array('state'=>'error','data'=> $this->text('e5'));
        }

        return $returnvalue;
    }
    
    /*
     * deleting item from db
     */
    public function deleteItem(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['slideshow'][1] == '0') die($this->text('dont_have_permission'));
        
        $this->datavalidator->addValidation('id','req',$this->text('e1'));
        $this->datavalidator->addValidation('id','numeric',$this->text('e2'));
        
        $result = $this->datavalidator->ValidateData($this->data);
        if(!$result){
            $errors = $this->datavalidator->GetErrors();
            return array('state'=>'error','data'=> reset($errors));
        }
        
        $temp = dibi::query('SELECT file FROM ' . DB_TABLEPREFIX . 'SLIDESHOW WHERE id=' . $this->data['id']);
        $image = $temp->fetchSingle();
        if (count($temp) == 1) {
            $temp = dibi::query('DELETE FROM ' . DB_TABLEPREFIX . 'SLIDESHOW WHERE id=' . $this->data['id']);
            if ($temp) {
                $returnvalue = array('state'=>'highlight','data'=> $this->text('s3'));
                if(isset($this->data['deletefiles']) && $this->data['deletefiles'] == 1){
                    if (substr($image,0,7) != 'http://' && file_exists(FRONTEND_ABSDIRPATH . SLIDESHOWIMAGESPATH . $image)) {
                        if(!unlink(FRONTEND_ABSDIRPATH . SLIDESHOWIMAGESPATH . $image)) {
                            $returnvalue['data'] .= '<br />'.$this->text('e6');
                        }
                    }
                }
            } else {
                $returnvalue = array('state'=>'error','data'=> $this->text('e7'));
            }
        }
        
        return $returnvalue;
    }
    
    /*
     * exchanges 2 items order
     */
    public function exchangeItems(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['slideshow'][1] == '0') die($this->text('dont_have_permission'));
        
        $this->datavalidator->addValidation('src_id','req',$this->text('t17'));
        $this->datavalidator->addValidation('src_id','numeric',$this->text('t18'));
        $this->datavalidator->addValidation('dst_id','req',$this->text('t19'));
        $this->datavalidator->addValidation('dst_id','numeric',$this->text('t20'));

        $result = $this->datavalidator->ValidateData($this->data);
        if(!$result){
            $errors = $this->datavalidator->GetErrors();
            return array('state'=>'error','data'=> reset($errors));
        }
        
        $temp = dibi::query("SELECT orderno FROM " . DB_TABLEPREFIX . "SLIDESHOW WHERE id=".$this->data['src_id']);
        $src_order = $temp->fetchSingle();
        $temp = dibi::query("SELECT orderno FROM " . DB_TABLEPREFIX . "SLIDESHOW WHERE id=".$this->data['dst_id']);
        $dst_order = $temp->fetchSingle();

        $temp = dibi::query("UPDATE " . DB_TABLEPREFIX . "SLIDESHOW SET orderno=".$dst_order." WHERE id=".$this->data['src_id']);
        $temp1 = dibi::query("UPDATE " . DB_TABLEPREFIX . "SLIDESHOW SET orderno=".$src_order." WHERE id=".$this->data['dst_id']);
        
        if ($temp == 0 && $temp1 == 0 || $temp == 1 && $temp1 == 1){
            $response = array('state'=>'highlight','data'=> $this->text('s4'));
        }else {
            $response = array('state'=>'error','data'=> $this->text('e8'));
        }
        return $response;
    }
    
    /*
     * returning form
     */
    private function getForm($formaction = 'add',$data = array()){
        
        $markup = '
            <button class="ui-icon-cancel button right" onclick="cancelAction();">'.$this->text('cancel').'</button>
            <button class="ui-icon-disk button right" onclick="'.$formaction.'Item(\''.((isset($data['id']))?$data['id']:'').'\');">'.$this->text('ok').'</button>
            <div class="clear"></div>';
            //if($formaction == 'add'){
                $markup .= '<fieldset>
                <legend>'.$this->text('t21').':</legend>
                <div class="help"><p>'.$this->text('h1').'</p></div>
                <input type="text" name="file" id="file" value="'.((isset($data['file']))?$data['file']:'').'" class="splittedleft ui-autocomplete-input ui-widget-content ui-corner-all" />
                <button class="ui-icon-folder-collapsed button splittedright" onclick="selectSlideFile();">'.$this->text('choose_file').'</button>
                </fieldset>';
            //}
            $markup .= '<fieldset>
            <legend>'.$this->text('t22').':</legend>
            <div class="help"><p>'.$this->text('h2').'</p></div>
            <input type="text" name="heading" id="heading" value="'.((isset($data['heading']))?$data['heading']:'').'" class="ui-autocomplete-input ui-widget-content ui-corner-all" />
            </fieldset>
            <fieldset>
            <legend>'.$this->text('t23').':</legend>
            <div class="help"><p>'.$this->text('h2').'</p></div>
            <input type="text" name="description" id="description" value="'.((isset($data['description']))?$data['description']:'').'" class="ui-autocomplete-input ui-widget-content ui-corner-all" />
            </fieldset>
            <fieldset>
            <legend>'.$this->text('t8').':</legend>
            <div class="help"><p>'.$this->text('h3').'</p></div>
            <input type="text" name="link" id="link" value="'.((isset($data['link']))?$data['link']:'').'" class="ui-autocomplete-input ui-widget-content ui-corner-all" />
            </fieldset>
            <fieldset>
            <legend>'.$this->text('t24').':</legend>
            <select id="textposition" name="textposition" class="ui-autocomplete-input ui-widget-content ui-corner-all" >
                <option value="notext"'.((isset($data['textposition']) && $data['textposition'] == 'notext')?' selected="selected"':'').'>'.$this->text('t29').'</option>
                <option value="left"'.((isset($data['textposition']) && $data['textposition'] == 'left')?' selected="selected"':'').'>'.$this->text('t25').'</option>
                <option value="right"'.((isset($data['textposition']) && $data['textposition'] == 'right')?' selected="selected"':'').'>'.$this->text('t26').'</option>
            </select>
            </fieldset>';
            if($this->languager->getLangsCount() > 1){
                $markup .= '<fieldset>
                <legend>'.$this->text('language').':</legend>
                '.$this->getLanguagesCombobox((isset($data['lang']))?$data['lang']:'').'
                </fieldset>';
            }
            $markup .= '<fieldset>
            <legend>'.$this->text('publish').':</legend>
            '.$this->text('from').': <input type="text" name="publish_from" id="publish_from" value="'.((isset($data['publish_from']))?date('d.m.Y',strtotime($data['publish_from'])):date('d.m.Y')).'" class="splittedright ui-autocomplete-input ui-widget-content ui-corner-all" /> 
            '.$this->text('to').': <input type="text" name="publish_to" id="publish_to" value="'.((isset($data['publish_to']))?date('d.m.Y',strtotime($data['publish_to'])):date('d.m.Y',strtotime("+1 month"))).'" class="splittedright ui-autocomplete-input ui-widget-content ui-corner-all" />
            </fieldset>
            <button class="ui-icon-cancel button right" onclick="cancelAction();">'.$this->text('cancel').'</button>
            <button class="ui-icon-disk button right" onclick="'.$formaction.'Item(\''.((isset($data['id']))?$data['id']:'').'\');">'.$this->text('ok').'</button>
            <div class="clear"></div>';
            
        return $markup;
    }
    
    /*
     * generates the layout combobox
     */
    private function getLanguagesCombobox($activelang = ''){
        $markup = '';
        $markup .= '<select name="lang" id="lang" class="ui-autocomplete-input ui-widget-content ui-corner-all">';
        $markup .= $this->languager->printLanguagesComboboxElements($activelang);
        $markup .= '</select>';
        return $markup;
    }

    /*
     * Checks the inputted data
     */
    private function checkData(){
        $this->datavalidator->addValidation('file','req',$this->text('e3'));
        $this->datavalidator->addValidation('file','maxlen=256',$this->text('e4'));
        if(strlen($this->data['heading']) > 0){
            $this->datavalidator->addValidation('heading','maxlen=200',$this->text('e9'));
        }
        if(strlen($this->data['description']) > 0){
            $this->datavalidator->addValidation('description','maxlen=300',$this->text('e10'));
        }
        if(strlen($this->data['link']) > 0){
            $this->datavalidator->addValidation('link','maxlen=500',$this->text('e11'));
        }
        $this->datavalidator->addValidation('textposition','req',$this->text('e12'));
        $this->datavalidator->addValidation('textposition','maxlen=10',$this->text('e13'));
        if($this->languager->getLangsCount() > 1){
            $this->datavalidator->addValidation('lang','req',$this->text('e14'));
            $this->datavalidator->addValidation('lang','numeric',$this->text('e15'));
        }
        $this->datavalidator->addValidation('publish_from','req',$this->text('e16'));
        $this->datavalidator->addValidation('publish_to','req',$this->text('e17'));

        $result = $this->datavalidator->ValidateData($this->data);
        if(!$result){
            $errors = $this->datavalidator->GetErrors();
            return array('state'=>'error','data'=> reset($errors));
        }else{
            return true;
        }
    }
}
