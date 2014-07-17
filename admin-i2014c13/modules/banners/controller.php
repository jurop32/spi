<?php

/*
 * script for managing banners
 */

class banners extends maincontroller{
    /*
     * Holds additional position names
     */
    private $bannerpositions = array();
    
    /*
     * Holds the translation table for script banner source
     */
    private $translation = array(
        '>' => '&gt;',
        '<' => '&lt;',
        '"' => '&quot;',
        '<!--' => '',
        '//-->' => ''
    );
    
    /*
     * Constructor
     */
    public function __construct($registry){
        parent::__construct($registry);
        
        //additional template defined banner positions
        $this->bannerpositions['h'] = $this->text('bannerpositions.h');
        $this->bannerpositions['f'] = $this->text('bannerpositions.f');
        $this->bannerpositions['r'] = $this->text('bannerpositions.r');
        $this->bannerpositions['l'] = $this->text('bannerpositions.l');
        $this->bannerpositions['c'] = $this->text('bannerpositions.c');
        $this->bannerpositions['0'] = $this->text('bannerpositions.a');
    }
    
    /*
     * returning items
     */
    public function getItems(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['banners'][0] == '0') die($this->text('dont_have_permission'));
        
        $temp = dibi::query('SELECT * FROM ' . DB_TABLEPREFIX . 'BANNERS ORDER BY active DESC, id DESC');
        $banners = $temp->fetchAll();

        if (count($banners) > 0) {
            
            //loading banner positions to display
            $temp = dibi::query('SELECT id,name FROM ' . DB_TABLEPREFIX . 'MENU');
            $temp = $temp->fetchAssoc('id');
            if(count($temp) > 0){
                foreach($temp as $id => $name){
                    $this->bannerpositions[$id] = $name['name'];
                }
            }
            
            $markup = '';
            
            $markup = '<table><tr>
            <th class="control ui-corner-all ui-state-hover">'.$this->text('id').'</th>
            <th class="control ui-corner-all ui-state-hover">'.$this->text('type').'</th>
            <th class="text ui-corner-all ui-state-hover">'.$this->text('url').'</th>
            <th class="text ui-corner-all ui-state-hover">'.$this->text('description').'</th>
            <th class="control ui-corner-all ui-state-hover">'.$this->text('category').'</th>
            <th class="control ui-corner-all ui-state-hover">'.$this->text('category_position').'</th>
            <th class="control ui-corner-all ui-state-hover">A</th>
            <th class="control ui-corner-all ui-state-hover">W</th>';
            if($_SESSION[PAGE_SESSIONID]['privileges']['banners'][1] == '1'){
                $markup .= '<th class="control ui-corner-all ui-state-hover"><span class="ui-icon ui-icon-pencil" title="'.$this->text('edit').'"></span></th>
                            <th class="control ui-corner-all ui-state-hover"><span class="ui-icon ui-icon-trash" title="'.$this->text('delete').'"></span></th>';
            }
            $markup .= '</tr>';
            foreach($banners as $banner){
                $markup .= '<tr>';
                $markup .= '<td>'.$banner['id'].'</td>';
                $markup .= '<td>'.$this->text('bannertypes.'.$banner['type']).'</td>';
                $markup .= '<td class="text">'.$banner['link'].'</td>';
                $markup .= '<td class="text">'.$banner['description'].'</td>';
                $markup .= '<td>'.$this->bannerpositions[$banner['location']].'</td>';
                $markup .= '<td>';
                if($banner['position'] == 'first') {
                    $markup .= $this->text('first');
                }else if($banner['position'] == 'last'){
                    $markup .= $this->text('last');
                }else{
                    $markup .= $banner['position'];
                }
                $markup .= '</td>';
                $markup .= '<td>';
                if($banner['active'] == '1') $markup .= '*';
                $markup .= '</td><td>'.$banner['viewcount'].'</td>';
                if($_SESSION[PAGE_SESSIONID]['privileges']['banners'][1] == '1'){
                    $markup .= '<td><button class="ui-icon-pencil notext button" onclick="editItem('.$banner['id'].');">'.$this->text('edit').'</button></td>';
                    $markup .= '<td><button class="ui-icon-trash notext button" onclick="confirmDeleteItem('.$banner['id'].',\''.$banner['filename'].'\');">'.$this->text('delete').'</button></td>';
                }
                $markup .= '</tr>';
            }
            $markup .= '</table>';
            
            return array('state'=>'ok','data'=> $markup);
        } else {
            return array('state'=>'ok','data'=> '<p>'.$this->text('t1').'</p>');
        }
    }
    
    /*
     * returning form for adding
     */
    public function getNewItemForm(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['banners'][1] == '0') die($this->text('dont_have_permission'));
        
        $markup = '<div><h4 class="left">'.$this->text('new_banner').'</h4>';
        $markup .= $this->getForm('add');
        $markup .= '</div>';
        return array('state'=>'ok','data'=> $markup);
    }
    
    /*
     * returning form for editing
     */
    public function getEditItemForm(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['banners'][1] == '0') die($this->text('dont_have_permission'));
        
        $this->datavalidator->addValidation('id','req',$this->text('e1'));
        $this->datavalidator->addValidation('id','numeric',$this->text('e2'));
        
        $result = $this->datavalidator->ValidateData($this->data);
        if(!$result){
            $errors = $this->datavalidator->GetErrors();
            return array('state'=>'error','data'=> reset($errors));
        }
            
        $temp = dibi::query('SELECT * FROM ' . DB_TABLEPREFIX . 'BANNERS WHERE id='.$this->data['id']);
        $item = $temp->fetchAssoc('id');
        if(count($item) == 1){
            $item = $item[$this->data['id']];
            $markup = '<div><h4 class="left">'.$this->text('edit_banner').'</h4>';
            $markup .= $this->getForm('update',$item);
            $markup .= '</div>';
            return array('state'=>'ok','data'=> $markup);
        }else{
            return array('state'=>'error','data'=> $this->text('e6'));
        }
    }
    
    /*
     * adding item to db
     */
    public function addItem(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['banners'][1] == '0') die($this->text('dont_have_permission'));

        //checking if data is valid
        $result = $this->checkData();
        if($result !== true) return $result;
        
        if($this->data['type'] == '2'){
            $this->data['link'] = strtr(stripslashes($this->data['link']),$this->translation);
        }
        
        if($this->data['maxviewcount'] == ''){
            $this->data['maxviewcount'] = 99999999999;
        }
        
        if($this->languager->getLangsCount() < 2){
            $this->data['lang'] = $this->languager->getDefaultLangId();
        }

        $temp = dibi::query("INSERT INTO " . DB_TABLEPREFIX . "BANNERS ",$this->data);
        $returnvalue = array();
        if ($temp == 0 || $temp == 1) {
            $returnvalue = array('state'=>'highlight','data'=> $this->text('banner_saved'));
        }else {
            $returnvalue = array('state'=>'error','data'=> $this->text('e3'));
        }

        return $returnvalue;
    }
    
    /*
     * updating item in db
     */
    public function updateItem(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['banners'][1] == '0') die($this->text('dont_have_permission'));

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
        
        if($this->data['type'] == '2'){
            $this->data['link'] = strtr(stripslashes($this->data['link']),$this->translation);
        }
        
        if($this->data['maxviewcount'] == ''){
            $this->data['maxviewcount'] = 99999999999;
        }
        
        if($this->languager->getLangsCount() < 2){
            $this->data['lang'] = $this->languager->getDefaultLangId();
        }

        $temp = dibi::query("UPDATE " . DB_TABLEPREFIX . "BANNERS SET ",$this->data,"WHERE id=" . $id);
        $returnvalue = array();
        if ($temp == 0 || $temp == 1) {
            $returnvalue = array('state'=>'highlight','data'=> $this->text('banner_saved'));
        }else {
            $returnvalue = array('state'=>'error','data'=> $this->text('e3'));
        }

        return $returnvalue;
    }
    
    /*
     * deleting item from db
     */
    public function deleteItem(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['banners'][1] == '0') die($this->text('dont_have_permission'));
        
        $this->datavalidator->addValidation('id','req',$this->text('e1'));
        $this->datavalidator->addValidation('id','numeric',$this->text('e2'));
        
        $result = $this->datavalidator->ValidateData($this->data);
        if(!$result){
            $errors = $this->datavalidator->GetErrors();
            return array('state'=>'error','data'=> reset($errors));
        }
        
        $temp = dibi::query('SELECT * FROM ' . DB_TABLEPREFIX . 'BANNERS WHERE id=' . $this->data['id']);
        $banner = $temp->fetchAssoc('id');
        if (count($banner) == 1) {
            $banner = $banner[$this->data['id']];
            $temp = dibi::query('DELETE FROM ' . DB_TABLEPREFIX . 'BANNERS WHERE id=' . $this->data['id']);
            if ($temp) {
                $returnvalue = array('state'=>'highlight','data'=> $this->text('banner_deleted'));
                if(isset($this->data['deletefiles']) && $this->data['deletefiles'] == 1){
                    if ($banner['filename'] != '' && substr($banner['filename'],0,7) != 'http://' && file_exists(FRONTEND_ABSDIRPATH . BANNERSPATH . $banner['filename'])) {
                        if(!unlink(FRONTEND_ABSDIRPATH . BANNERSPATH . $banner['filename'])) {
                            $returnvalue['data'] .= '<br />'.$this->text('e4');
                        }
                    }
                }
            } else {
                $returnvalue = array('state'=>'error','data'=> $this->text('e5'));
            }
        }else{
            $returnvalue = array('state'=>'error','data'=> $this->text('e6'));
        }
        
        return $returnvalue;
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
            <legend>'.$this->text('type').':</legend>
            <div class="help"><p>'.$this->text('h1').'</p></div>
            '.$this->getBannertypeCombobox((isset($data['type']))?$data['type']:'').'
            </fieldset>
            <fieldset id="bannerfile" '.((!isset($data['type']) || (isset($data['type']) && $data['type'] == '0'))?'':' style="display:none;"').'>
            <legend>'.$this->text('filename').':</legend>
            <div class="help"><p>'.$this->text('h2').'</p></div>
            <input class="splittedleft ui-autocomplete-input ui-widget-content ui-corner-all" type="text" name="filename" id="filename" value="'.((isset($data['filename']))?$data['filename']:'').'" />
            <button class="ui-icon-folder-collapsed button splittedright" onclick="selectBannerFile();">'.$this->text('choose_file').'</button>
            </fieldset>
            <fieldset>
            <legend>'.$this->text('description').':</legend>
            <input type="text" name="description" id="description" value="'.((isset($data['description']))?$data['description']:'').'" class="ui-autocomplete-input ui-widget-content ui-corner-all" />
            </fieldset>
            <fieldset>
            <legend>'.$this->text('urlscript').':</legend>
            <div class="help"><p>'.$this->text('h3').'</p></div>
            <input type="text" name="link" id="link" value="'.((isset($data['link']))?$data['link']:'').'" class="ui-autocomplete-input ui-widget-content ui-corner-all" />
            </fieldset>';
            if($this->languager->getLangsCount() > 1){
                $markup .= '<fieldset>
                <legend>'.$this->text('language').':</legend>
                '.$this->getLanguagesCombobox((isset($data['lang']))?$data['lang']:'').'
                </fieldset>';
            }
            $markup .= '<fieldset>
            <legend>'.$this->text('location').':</legend>
            '.$this->getCategoriesCombobox((isset($data['location']))?$data['location']:'').'
            </fieldset>
            <fieldset>
            <legend>'.$this->text('position').':</legend>
            <div class="help"><p>'.$this->text('h4').'</p></div>
            '.$this->getPositionCombobox((isset($data['position']))?$data['position']:'').'
            </fieldset>
            <fieldset>
            <legend>'.$this->text('settings').':</legend>
            <div class="help"><p>'.$this->text('h5').'</p></div>
            '.$this->text('viewcount').': <input class="splittedright ui-autocomplete-input ui-widget-content ui-corner-all" type="text" name="maxviewcount" id="maxviewcount" value="" /><br /><br />
            <input type="checkbox" name="active" id="active" value="1"'.((isset($data['active']) && $data['active'] == '0')?'':' checked="checked"').' /> - '.$this->text('active').'
            <div id="openinholder">
                <input type="checkbox" name="openin" id="openin" value="1"'.((isset($data['openin']) && $data['openin'] == '0')?'':' checked="checked"').' /> - '.$this->text('openin').'
            </div>
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
        $this->datavalidator->addValidation('type','req',$this->text('e7'));
        $this->datavalidator->addValidation('type','alpha',$this->text('e8'));
        if(isset($this->data['type'])){
            switch ($this->data['type']){
                case 'e':
                    $this->datavalidator->addValidation('link','url',$this->text('e9'));
                    break;
                case 'f':
                    $this->datavalidator->addValidation('link','regexp=/^<script.+<\/script>$/',$this->text('e10'));
                    break;
                default:
                    $this->datavalidator->addValidation('filename','req',$this->text('e11'));
                    if(isset($this->data['filename']) && substr($this->data['filename'],0,7) == 'http://'){
                        $this->datavalidator->addValidation('filename','url',$this->text('e12'));
                    }else{
                        $this->datavalidator->addValidation('filename','regexp=/(jpeg|jpg|png|gif|swf)$/',$this->text('e13'));
                    }
                    $this->datavalidator->addValidation('filename','maxlen=300',$this->text('e14'));
                    if(isset($this->data['link']) && preg_match('/^(article|category|video)=\d+$/',$this->data['link']) == 0 && preg_match('/^page=.+$/',$this->data['link']) == 0){
                        $this->datavalidator->addValidation('link','url',$this->text('e15'));
                    }
            }
        }
        $this->datavalidator->addValidation('description','maxlen=50',$this->text('e27'));
        $this->datavalidator->addValidation('link','req',$this->text('e16'));
        $this->datavalidator->addValidation('link','maxlen=500',$this->text('e17'));
        if($this->languager->getLangsCount() > 1){
            $this->datavalidator->addValidation('lang','req',$this->text('e18'));
            $this->datavalidator->addValidation('lang','numeric',$this->text('e19'));
        }
        $this->datavalidator->addValidation('location','req',$this->text('e20'));
        $this->datavalidator->addValidation('location','maxlen=10',$this->text('e21'));
        $this->datavalidator->addValidation('position','req',$this->text('e22'));
        $this->datavalidator->addValidation('position','maxlen=10',$this->text('e23'));
        $this->datavalidator->addValidation('maxviewcount','maxlen=11',$this->text('e24'));
        $this->datavalidator->addValidation('maxviewcount','numeric',$this->text('e25'));
        $this->datavalidator->addValidation('active','req',$this->text('e26'));
        $this->datavalidator->addValidation('openin','req',$this->text('e28'));

        $result = $this->datavalidator->ValidateData($this->data);
        if(!$result){
            $errors = $this->datavalidator->GetErrors();
            return array('state'=>'error','data'=> reset($errors));
        }else{
            return true;
        }
    }

    /*
     * Retuns the categories combobox
     */
    private function getCategoriesCombobox($active_category = '', $forparent = 0){
        $markup = '';
        $markup .= '<select name="location" id="location" class="ui-autocomplete-input ui-widget-content ui-corner-all">';
        foreach($this->bannerpositions as $key => $name){
            $markup .= '<option value="'.$key.'"';
            if($key === $active_category) $markup .= ' selected="selected"';
            $markup .= '>'.$name.'</option>';
        }
        $markup .= $this->getMenucategories($active_category, $forparent);
        $markup .= '</select>';
        return $markup;
    }
    
    /*
     * Retuns the bannertype combobox
     */
    private function getBannertypeCombobox($active_type = ''){
        $markup = '';
        $markup .= '<select name="type" id="type" onchange="showFilenameInput();" class="ui-autocomplete-input ui-widget-content ui-corner-all">';
        foreach($this->text('bannertypes') as $key => $name){
            $markup .= '<option value="'.$key.'"';
            if((String)$key == $active_type) $markup .= ' selected="selected"';
            $markup .= '>'.$name.'</option>';
        }
        $markup .= '</select>';
        return $markup;
    }

    /*
     * Retuns the category positions combobox
     */
    private function getPositionCombobox($active_position = 'x'){
        $markup = '';
        $markup .= '
        <select name="position" id="position" onchange="showPositionInput();" class="splittedleft ui-autocomplete-input ui-widget-content ui-corner-all">
            <option value="first"'.(($active_position == 'first')?' selected="selected"':'').'>'.$this->text('first').'</option>
            <option value="pos"'.((is_numeric($active_position))?' selected="selected"':'').'>'.$this->text('position_nr').'</option>
            <option value="last"'.(($active_position == 'last')?' selected="selected"':'').'>'.$this->text('last').'</option>
        </select>
        <input'.((!is_numeric($active_position))?' style="display:none;"':'').' type="text" name="position_number" id="position_number" value="'.((is_numeric($active_position))?$active_position:'1').'" onkeyup="validateTextinput(this);" onfocus="this.select();" class="splittedright ui-autocomplete-input ui-widget-content ui-corner-all" />';
        return $markup;
    }
    
    /*
     * generates the layout combobox
     */
    private function getLanguagesCombobox($activelang = ''){
        $markup = '';
        $markup .= '<select name="lang" id="lang" onchange="controlCategoryLanguage(e);" class="ui-autocomplete-input ui-widget-content ui-corner-all">';
        $markup .= $this->languager->printLanguagesComboboxElements($activelang);
        $markup .= '</select>';
        return $markup;
    }
}