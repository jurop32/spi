<?php

/*
 * Minislider editor
 */

class minislider extends maincontroller{
    
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
        if($_SESSION[PAGE_SESSIONID]['privileges']['minislider'][0] == '0') die($this->text('dont_have_permission'));
        
        $where = '';
        if(isset($this->data['lang']) && $this->data['lang'] != ''){
            $where .= " WHERE lang='".$this->data['lang']."'";
        }
        
        $markup = '<table>';
        
        $temp = dibi::query("SELECT * FROM ". DB_TABLEPREFIX ."MINISLIDER ".$where." ORDER BY orderno DESC");
        $slides = $temp->fetchAll();
        
        if (count($slides) > 0) {
            $markup .= '<tr>
                <th class="control ui-corner-all ui-state-hover">'.$this->text('id').'</th>
                <th class="control ui-corner-all ui-state-hover">'.$this->text('t5').'</th>
                <th class="text ui-corner-all ui-state-hover">'.$this->text('t20').'</th>
                <th class="control ui-corner-all ui-state-hover">A</th>';
                if($_SESSION[PAGE_SESSIONID]['privileges']['minislider'][1] == '1'){
                    $markup .= '
                    <th class="control ui-corner-all ui-state-hover"><span class="ui-icon ui-icon-pencil" title="'.$this->text('edit').'"></span></th>
                    <th class="control ui-corner-all ui-state-hover"><span class="ui-icon ui-icon-trash" title="'.$this->text('delete').'"></span></th>';
                }
                $markup .= '</tr>';
            foreach($slides as $slide){
                $markup .= '<tr>';
                $markup .= '<td>'.$slide['id'].'</td>';
                $markup .= '<td>';
                if($slide['filename'] != ''){
                    if(substr($slide['filename'],0,7) == 'http://'){
                        $markup .= '<img class="thumb" src="'.$slide['filename'].'" />';
                    }else if(file_exists(FRONTEND_ABSDIRPATH.MINISLIDERIMAGESPATH.$slide['filename'])){
                        $markup .= '<img class="thumb" src="'.PAGE_URL.MINISLIDERIMAGESPATH.$slide['filename'].'" />';
                    }
                }
                $markup .= '</td>';
                $markup .= '<td class="text">'.$slide['name'].'</td>';
                $markup .= '<td>';
                if($slide['active'] == '1')
                    $markup .= '*';
                $markup .= '</td>';
                if($_SESSION[PAGE_SESSIONID]['privileges']['minislider'][1] == '1'){
                    
                    $markup .= '<td><button class="ui-icon-pencil notext button" onclick="editItem('.$slide['id'].');">'.$this->text('edit').'</button></td>';
                    $markup .= '<td><button class="ui-icon-trash notext button" onclick="confirmDeleteItem('.$slide['id'].');">'.$this->text('delete').'</button></td>';
                }
                $markup .= '</tr>';
            }
        } else {
            $markup .= '<p>'.$this->text('t8').'</p>';
        }
        
        $markup .= '</table>';
            
        return array('state'=>'ok','data'=> $markup);
    }
    
    /*
     * returning form for adding
     */
    public function getNewItemForm(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['minislider'][1] == '0') die($this->text('dont_have_permission'));
        
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
        if($_SESSION[PAGE_SESSIONID]['privileges']['minislider'][1] == '0') die($this->text('dont_have_permission'));
        
        $this->datavalidator->addValidation('id','req',$this->text('e1'));
        $this->datavalidator->addValidation('id','numeric',$this->text('e2'));
        $result = $this->datavalidator->ValidateData($this->data);
        if(!$result){
            $errors = $this->datavalidator->GetErrors();
            return array('state'=>'error','data'=> reset($errors));
        }
            
        $_SESSION['minislider_filemanagerpath'] = MINISLIDERIMAGESPATH;

        $temp = dibi::query('SELECT * FROM ' . DB_TABLEPREFIX . 'MINISLIDER WHERE id='.$this->data['id']);
        $item = $temp->fetchAssoc('id');
        if(count($temp) == 1){
            $item = $item[$this->data['id']];
            $markup = '<div><h4 class="left">'.$this->text('t9').': '.$item['id'].'</h4>';
            $markup .= $this->getForm('update',$item);
            $markup .= '</div>';
            return array('state'=>'ok','data'=> $markup);
        }
    }
    
    /*
     * adding item to db
     */
    public function addItem(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['minislider'][1] == '0') die($this->text('dont_have_permission'));
        
        //checking if data is valid
        $result = $this->checkData();
        if($result !== true) return $result;
        
        if($this->languager->getLangsCount() < 2){
            $this->data['lang'] = $this->languager->getDefaultLangId();
        }

        $temp = dibi::query("INSERT INTO " . DB_TABLEPREFIX . "MINISLIDER ",$this->data);
        $returnvalue = array();
        if ($temp == 0 || $temp == 1) {
            //setting orderno
            dibi::query("UPDATE " . DB_TABLEPREFIX . "MINISLIDER SET orderno=id WHERE id=".dibi::insertId());
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
        if($_SESSION[PAGE_SESSIONID]['privileges']['minislider'][1] == '0') die($this->text('dont_have_permission'));

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
        
        if($this->languager->getLangsCount() < 2){
            $this->data['lang'] = $this->languager->getDefaultLangId();
        }

        $temp = dibi::query("UPDATE " . DB_TABLEPREFIX . "MINISLIDER SET ",$this->data,'WHERE id='.$id);
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
        if($_SESSION[PAGE_SESSIONID]['privileges']['minislider'][1] == '0') die($this->text('dont_have_permission'));
        
        $this->datavalidator->addValidation('id','req',$this->text('e1'));
        $this->datavalidator->addValidation('id','numeric',$this->text('e2'));
        
        $result = $this->datavalidator->ValidateData($this->data);
        if(!$result){
            $errors = $this->datavalidator->GetErrors();
            return array('state'=>'error','data'=> reset($errors));
        }
        
        $temp = dibi::query('SELECT file FROM ' . DB_TABLEPREFIX . 'MINISLIDER WHERE id=' . $this->data['id']);
        $image = $temp->fetchSingle();
        if (count($temp) == 1) {
            $temp = dibi::query('DELETE FROM ' . DB_TABLEPREFIX . 'MINISLIDER WHERE id=' . $this->data['id']);
            if ($temp) {
                $returnvalue = array('state'=>'highlight','data'=> $this->text('s3'));
                if(isset($this->data['deletefiles']) && $this->data['deletefiles'] == 1){
                    if (substr($image,0,7) != 'http://' && file_exists(FRONTEND_ABSDIRPATH . MINISLIDERIMAGESPATH . $image)) {
                        if(!unlink(FRONTEND_ABSDIRPATH . MINISLIDERIMAGESPATH . $image)) {
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
        if($_SESSION[PAGE_SESSIONID]['privileges']['minislider'][1] == '0') die($this->text('dont_have_permission'));
        
        $this->datavalidator->addValidation('src_id','req',$this->text('t10'));
        $this->datavalidator->addValidation('src_id','numeric',$this->text('t11'));
        $this->datavalidator->addValidation('dst_id','req',$this->text('t12'));
        $this->datavalidator->addValidation('dst_id','numeric',$this->text('t13'));

        $result = $this->datavalidator->ValidateData($this->data);
        if(!$result){
            $errors = $this->datavalidator->GetErrors();
            return array('state'=>'error','data'=> reset($errors));
        }
        
        $temp = dibi::query("SELECT orderno FROM " . DB_TABLEPREFIX . "MINISLIDER WHERE id=".$this->data['src_id']);
        $src_order = $temp->fetchSingle();
        $temp = dibi::query("SELECT orderno FROM " . DB_TABLEPREFIX . "MINISLIDER WHERE id=".$this->data['dst_id']);
        $dst_order = $temp->fetchSingle();

        $temp = dibi::query("UPDATE " . DB_TABLEPREFIX . "MINISLIDER SET orderno=".$dst_order." WHERE id=".$this->data['src_id']);
        $temp1 = dibi::query("UPDATE " . DB_TABLEPREFIX . "MINISLIDER SET orderno=".$src_order." WHERE id=".$this->data['dst_id']);
        
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
            <div class="clear"></div>
            <fieldset>
            <legend>'.$this->text('t20').':</legend>
            <input type="text" name="name" id="name" value="'.((isset($data['name']))?$data['name']:'').'" class="ui-autocomplete-input ui-widget-content ui-corner-all" />
            </fieldset>
            <fieldset>
            <legend>'.$this->text('t6').':</legend>
            <textarea name="text" id="text" class="ui-autocomplete-input ui-widget-content ui-corner-all">'.((isset($data['text']))?$data['text']:'').'</textarea>
            </fieldset>
            <fieldset>
            <legend>'.$this->text('t14').':</legend>
            <div class="help"><p>'.$this->text('h1').'</p></div>
            <input type="text" name="filename" id="filename" value="'.((isset($data['filename']))?$data['filename']:'').'" class="splittedleft ui-autocomplete-input ui-widget-content ui-corner-all" />
            <button class="ui-icon-folder-collapsed button splittedright" onclick="selectSlideFile();">'.$this->text('choose_file').'</button>
            </fieldset>
            <fieldset>
            <legend>'.$this->text('t15').':</legend>
            <div class="help"><p>'.$this->text('h2').'</p></div>
            <textarea name="url" id="url" class="ui-autocomplete-input ui-widget-content ui-corner-all">'.((isset($data['url']))?$data['url']:'').'</textarea>
            </fieldset>
            <fieldset>
            <legend>'.$this->text('t19').':</legend>
            x: <input type="text" name="x" id="x" value="'.((isset($data['x']))?$data['x']:'0').'" class="ui-autocomplete-input ui-widget-content ui-corner-all spinner splittedright" />
            y: <input type="text" name="y" id="y" value="'.((isset($data['y']))?$data['y']:'0').'" class="ui-autocomplete-input ui-widget-content ui-corner-all spinner splittedright" />
            </fieldset>';
            if($this->languager->getLangsCount() > 1){
                $markup .= '<fieldset>
                <legend>'.$this->text('language').':</legend>
                '.$this->getLanguagesCombobox((isset($data['lang']))?$data['lang']:'').'
                </fieldset>';
            }
            $markup .= '<fieldset>
            <legend>'.$this->text('settings').':</legend>
            <input type="checkbox" name="active" id="active" value="1"'.((isset($data['active']) && $data['active'] == '0')?'':' checked="checked"').' /> - '.$this->text('t16').'
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
        $this->datavalidator->addValidation('name','req',$this->text('e16'));
        $this->datavalidator->addValidation('name','maxlen=50',$this->text('e17'));
        $this->datavalidator->addValidation('text','req',$this->text('e15'));
        $this->datavalidator->addValidation('text','maxlen=300',$this->text('e9'));
        $this->datavalidator->addValidation('filename','maxlen=256',$this->text('e4'));
        $this->datavalidator->addValidation('x','req',$this->text('e14'));
        $this->datavalidator->addValidation('x','numeric',$this->text('e14'));
        $this->datavalidator->addValidation('y','req',$this->text('e14'));
        $this->datavalidator->addValidation('y','numeric',$this->text('e14'));
        $this->datavalidator->addValidation('url','maxlen=1000',$this->text('e10'));
        $this->datavalidator->addValidation('url','regexp=/^[^\n\r\|]+\|\|[^\n\r\|]+([\n\r][^\n\r\|]+\|\|[^\n\r\|]+)*$/',$this->text('e18'));
        if($this->languager->getLangsCount() > 1){
            $this->datavalidator->addValidation('lang','req',$this->text('e11'));
            $this->datavalidator->addValidation('lang','numeric',$this->text('e12'));
        }
        $this->datavalidator->addValidation('active','req',$this->text('e13'));

        $result = $this->datavalidator->ValidateData($this->data);
        if(!$result){
            $errors = $this->datavalidator->GetErrors();
            return array('state'=>'error','data'=> reset($errors));
        }else{
            return true;
        }
    }
}