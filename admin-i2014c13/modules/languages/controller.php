<?php

class languages extends maincontroller{
    
    /*
     * Constructor
     */
    public function __construct($registry){
        parent::__construct($registry);
    }
    
    /*
     * returning items
     */
    public function getItems(){
        
        $temp = dibi::query('SELECT * FROM ' . DB_TABLEPREFIX . 'LANGUAGES ORDER BY id ASC');
        $items = $temp->fetchAll();

        if (count($items) > 0) {
            $markup = '';
            
            $markup = '<table><tr>
            <th class="control ui-corner-all ui-state-hover">'.$this->text('id').'</th>
            <th class="text ui-corner-all ui-state-hover">'.$this->languager->getText('common','name').'</th>
            <th class="control ui-corner-all ui-state-hover">D</th>
            <th class="control ui-corner-all ui-state-hover">P</th>
            <th class="control ui-corner-all ui-state-hover"><span class="ui-icon ui-icon-pencil" title="'.$this->text('edit').'"></span></th>
            <th class="control ui-corner-all ui-state-hover"><span class="ui-icon ui-icon-trash" title="'.$this->text('delete').'"></span></th>
            </tr>';
            foreach($items as $item){
                $markup .= '<tr>';
                $markup .= '<td>'.$item['id'].'</td>';
                $markup .= '<td class="text">'.$item['name'].'</td>';
                $markup .= '<td>';
                if($item['defaultlang'] == '1'){
                    $markup .= '*';
                }
                $markup .= '</td>';
                $markup .= '<td>';
                if($item['published'] == '1'){
                    $markup .= '*';
                }
                $markup .= '</td>';
                if($_SESSION[PAGE_SESSIONID]['privileges']['languages'][1] == '1'){
                    $markup .= '<td><button class="ui-icon-pencil notext button" onclick="editItem('.$item['id'].');">'.$this->text('edit').'</button></td>';
                    $markup .= '<td>';
                    if($item['id'] != '1'){
                        $markup .= '<button class="ui-icon-trash notext button" onclick="confirmDeleteItem('.$item['id'].',\''.$item['name'].'\');">'.$this->text('delete').'</button>';
                    }
                    $markup .= '</td>';
                }
                $markup .= '</tr>';
            }
            $markup .= '</table>';
            return array('state'=>'ok','data'=> $markup);
        } else {
            return array('state'=>'ok','data'=> '<p>'.$this->text('nolanguages').'</p>');
        }
    }
    
    /*
     * returning form for adding
     */
    public function getNewItemForm(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['languages'][1] == '0') die($this->text('dont_have_permission'));
        
        $markup = '<div><h4 class="left">'.$this->text('new_language').'</h4>';
        $markup .= $this->getForm('add');
        $markup .= '</div>';
        return array('state'=>'ok','data'=> $markup);
    }
    
    /*
     * returning form for editing
     */
    public function getEditItemForm(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['languages'][1] == '0') die($this->text('dont_have_permission'));
        
        $this->datavalidator->addValidation('id','req',$this->text('e1'));
        $this->datavalidator->addValidation('id','numeric',$this->text('e2'));
        
        $result = $this->datavalidator->ValidateData($this->data);
        if(!$result){
            $errors = $this->datavalidator->GetErrors();
            return array('state'=>'error','data'=> reset($errors));
        }
            
        $temp = dibi::query('SELECT * FROM ' . DB_TABLEPREFIX . 'LANGUAGES WHERE id='.$this->data['id']);
        $item = $temp->fetchAssoc('id');
        if(count($item) == 1){
            $item = $item[$this->data['id']];
            $markup = '<div><h4 class="left">'.$this->text('t6').': '.$item['name'].'</h4>';
            $markup .= $this->getForm('update',$item);
            $markup .= '</div>';
            return array('state'=>'ok','data'=> $markup);
        }else{
            return array('state'=>'error','data'=> $this->text('e3'));
        }
    }
    
    /*
     * adding item to db
     */
    public function addItem(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['languages'][1] == '0') die($this->text('dont_have_permission'));

        //checking if data is valid
        $result = $this->checkData();
        if($result !== true) return $result;
        
        if($this->data['defaultlang'] == '1'){
            $temp = dibi::query("UPDATE " . DB_TABLEPREFIX . "LANGUAGES SET defaultlang=0");
            if ($temp != 0 && $temp != 1) {
                $returnvalue = array('state'=>'error','data'=> $this->text('e4'));
            }
        }

        $temp = dibi::query("INSERT INTO " . DB_TABLEPREFIX . "LANGUAGES ",$this->data);
        $returnvalue = array();
        if ($temp == 0 || $temp == 1) {
            $returnvalue = array('state'=>'highlight','data'=> $this->text('h1'));
        }else {
            $returnvalue = array('state'=>'error','data'=> $this->text('e4'));
        }

        return $returnvalue;
    }
    
    /*
     * updating item in db
     */
    public function updateItem(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['languages'][1] == '0') die($this->text('dont_have_permission'));

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
        
        if($this->data['defaultlang'] == '1'){
            $temp = dibi::query("UPDATE " . DB_TABLEPREFIX . "LANGUAGES SET defaultlang=0");
            if ($temp != 0 && $temp != 1) {
                $returnvalue = array('state'=>'error','data'=> $this->text('e4'));
            }
        }
        
        $id = $this->data['id'];
        unset($this->data['id']);

        $temp = dibi::query("UPDATE " . DB_TABLEPREFIX . "LANGUAGES SET ",$this->data,"WHERE id=" . $id);
        $returnvalue = array();
        if ($temp == 0 || $temp == 1){
            $returnvalue = array('state'=>'highlight','data'=> $this->text('h1'));
        }else {
            $returnvalue = array('state'=>'error','data'=> $this->text('e4'));
        }

        return $returnvalue;
    }
    
    /*
     * deleting item from db
     */
    public function deleteItem(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['languages'][1] == '0') die($this->text('dont_have_permission'));
        
        $this->datavalidator->addValidation('id','req',$this->text('e1'));
        $this->datavalidator->addValidation('id','numeric',$this->text('e2'));

        $result = $this->datavalidator->ValidateData($this->data);
        if(!$result){
            $errors = $this->datavalidator->GetErrors();
            return array('state'=>'error','data'=> reset($errors));
        }
        
        $temp = dibi::query('SELECT * FROM ' . DB_TABLEPREFIX . 'LANGUAGES WHERE id=' . $this->data['id']);
        $langtodelete = $temp->fetchAll();
        if(count($langtodelete) > 0){
            if($langtodelete[0]['defaultlang'] == '1'){
                $temp = dibi::query("UPDATE " . DB_TABLEPREFIX . "LANGUAGES SET defaultlang=1 WHERE id=1");
                if ($temp != 0 && $temp != 1) {
                    return array('state'=>'error','data'=> $this->text('e5'));
                }
            }
            //deleting categories with all articles where language is language to delete
            $temp = dibi::query('SELECT id FROM ' . DB_TABLEPREFIX . 'MENU WHERE lang=' . $this->data['id']);
            $categoriestodelete = $temp->fetchAll();
            if(count($categoriestodelete) > 0){
                foreach($categoriestodelete as $category){
                    $result = $this->deleteCategory($category['id'],'1');
                    if(!$result) return array('state'=>'error','data'=> $this->text('e6'));
                }
            }
            //deleting banners where language is language to delete
            $temp = dibi::query('DELETE FROM ' . DB_TABLEPREFIX . 'BANNERS WHERE lang=' . $this->data['id']);
            
            $temp1 = dibi::query('DELETE FROM ' . DB_TABLEPREFIX . 'LANGUAGES WHERE id=' . $this->data['id']);
            if ($temp && $temp1) {
                return array('state'=>'highlight','data'=> $this->text('h2'));
            } else {
                return array('state'=>'error','data'=> $this->text('e5'));
            }
        }else{
            return array('state'=>'error','data'=> $this->text('e3'));
        }
    }
    
    /*
     * returning form
     */
    private function getForm($formaction = 'add',$data = array()){
        
        $markup = '
            <button class="ui-icon-cancel button right" onclick="cancelAction();">'.$this->text('cancel').'</button>
            <button class="ui-icon-disk button right" onclick="'.$formaction.'Item(\''.((isset($data['id']))?$data['id']:'').'\');">'.$this->text('ok').'</button>
            <div class="clear"></div>
            <fieldset>
            <legend>'.$this->languager->getText('common','name').':</legend>
            <input type="text" name="name" id="name" value="'.((isset($data['name']))?$data['name']:'').'" class="ui-autocomplete-input ui-widget-content ui-corner-all" />
            </fieldset>
            <fieldset>
            <legend>'.$this->text('t7').':</legend>
            <div class="help"><p>'.$this->text('t8').'<br />'.$this->text('t9').'<br />http://msdn.microsoft.com/en-us/library/ms533052(v=vs.85).aspx</p></div>
            '.$this->text('t10').': <input type="text" name="shortcode" id="shortcode" value="'.((isset($data['shortcode']))?$data['shortcode']:'').'" class="ui-autocomplete-input ui-widget-content ui-corner-all splittedright" />
            '.$this->text('t11').': <input type="text" name="longcode" id="longcode" value="'.((isset($data['longcode']))?$data['longcode']:'').'" class="ui-autocomplete-input ui-widget-content ui-corner-all splittedright" />
            </fieldset>
            <fieldset>
            <legend>'.$this->text('settings').':</legend>
            <div class="help"><p>'.$this->text('t12').'</p></div>
            <input type="checkbox" name="published" id="published" value="1"'.((isset($data['published']) && $data['published'] == '0')?'':' checked="checked"').(((isset($data['id']) && $data['id'] == '1') || (isset($data['defaultlang']) && $data['defaultlang'] == '1'))?' disabled="disabled"':'').' /> - '.$this->text('publish').'<br />
            <input type="checkbox" name="defaultlang" id="defaultlang" value="1"'.((isset($data['defaultlang']) && $data['defaultlang'] == '1')?' checked="checked"':'').((isset($data['defaultlang']) && $data['defaultlang'] == '1')?' disabled="disabled"':'').' /> - '.$this->text('default').'
            </fieldset>
            <button class="ui-icon-cancel button right" onclick="cancelAction();">'.$this->text('cancel').'</button>
            <button class="ui-icon-disk button right" onclick="'.$formaction.'Item(\''.((isset($data['id']))?$data['id']:'').'\');">'.$this->text('ok').'</button>
            <div class="clear"></div>
        ';
        return $markup;
    }

    /*
     * Checks the inputted data
     */
    private function checkData(){
        $this->datavalidator->addValidation('name','req',$this->text('e7'));
        $this->datavalidator->addValidation('name','maxlen=50',$this->text('e8'));
        $this->datavalidator->addValidation('shortcode','req',$this->text('e9'));
        $this->datavalidator->addValidation('shortcode','regexp=/^[a-z]{2}$/',$this->text('e10'));
        $this->datavalidator->addValidation('longcode','req',$this->text('e11'));
        $this->datavalidator->addValidation('longcode','regexp=/^[a-z]{2}-[A-Z]{2}$/',$this->text('e12'));
        $this->datavalidator->addValidation('published','req',$this->text('e13'));
        $this->datavalidator->addValidation('published','numeric',$this->text('e14'));
        $this->datavalidator->addValidation('defaultlang','req',$this->text('e15'));
        $this->datavalidator->addValidation('defaultlang','numeric',$this->text('e16'));

        $result = $this->datavalidator->ValidateData($this->data);
        if(!$result){
            $errors = $this->datavalidator->GetErrors();
            return array('state'=>'error','data'=> reset($errors));
        }else{
            return true;
        }
    }
}