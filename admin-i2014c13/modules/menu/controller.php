<?php
/*
 * the categories
 */

class menu extends maincontroller{
    
    /*
     * Restricted pagenames array
     */
    private $restrictedpagenames = array(
        'offline',
        'intro',
        'notfound',
        'videoplayer'
    );
    
    public function __construct($registry){
        parent::__construct($registry);
    }
    
    /*
     * returning items
     */
    public function getItems(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['menu'][0] == '0') die($this->text('dont_have_permission'));
        
        $where = '';
        if(isset($this->data['lang']) && $this->data['lang'] != ''){
            $where .= " WHERE lang='".$this->data['lang']."'";
        }
        
        $temp = dibi::query('SELECT * FROM '.DB_TABLEPREFIX.'MENU'.$where.' ORDER BY orderno ASC');
        $items = $temp->fetchAll();

        if (count($items) > 0) {
            $markup = '<table><tr>
            <th class="control ui-corner-all ui-state-hover">'.$this->text('id').'</th>
            <th class="text ui-corner-all ui-state-hover">'.$this->languager->getText('common','name').'</th>
            <th class="text ui-corner-all ui-state-hover">'.$this->text('link').'</th>
            <th class="control ui-corner-all ui-state-hover">'.$this->text('layout').'</th>
            <th class="control ui-corner-all ui-state-hover">W</th>
            <th class="control ui-corner-all ui-state-hover">P</th>
            <th class="control ui-corner-all ui-state-hover">N</th>
            <th class="control ui-corner-all ui-state-hover">M</th>
            <th class="control ui-corner-all ui-state-hover">F</th>';
            if($_SESSION[PAGE_SESSIONID]['privileges']['menu'][1] == '1'){
                $markup .= '<th class="control ui-corner-all ui-state-hover"><span class="ui-icon ui-icon-pencil" title="'.$this->text('edit').'"></span></th>
            <th class="control ui-corner-all ui-state-hover"><span class="ui-icon ui-icon-carat-2-n-s" title="'.$this->text('move').'"></span></th>';
            }
            if($_SESSION[PAGE_SESSIONID]['privileges']['menu'][2] == '1'){
            $markup .= '<th class="control ui-corner-all ui-state-hover"><span class="ui-icon ui-icon-trash" title="'.$this->text('delete').'"></span></th>';
            }
            $markup .= '</tr>
            '.$this->getMenuLevelItems(0, $items, '&nbsp;&nbsp;&nbsp;').'
            </table>';
            
            return array('state'=>'ok','data'=> $markup);
        } else {
            return array('state'=>'ok','data'=> '<p>'.$this->text('nocat_indb').'</p>');
        }
    }

    /*
     * getting menuelements recursilevly
     */
    private function getMenuLevelItems($parentid, $elements, $indent) {
        $markup = '';
        foreach ($elements as $element) {
            if ($element['parentid'] == $parentid) {
                $markup .= '<tr>';
                $markup .= '<td>'.$element['id'].'</td>';
                $markup .= '<td class="text">'.$indent.'<sup>|_</sup> <a href="'.PAGE_URL.'index.php?category='.$element['id'].'" target="_blank">'.$element['name'].'</a></td>';
                $markup .= '<td class="text">'.(($element['type'] != 's')?$this->text('linktypes.'.$element['type']).':':'').' <b>'.$element['link'].'</b></td>';
                $markup .= '<td>'.$this->text('layouts.'.$element['layout']).'</td>';
                $markup .= '<td>'.$element['viewcount'].'</td>';
                $markup .= '<td>';
                if($element['published'] == 1) $markup .= '*';
                $markup .= '</td><td>';
                if($element['display_new_articles'] == 1) $markup .= '*';
                $markup .= '</td><td>';
                if($element['show_in_menu'] == 1) $markup .= '*';
                $markup .= '</td><td>';
                if($element['show_in_footer'] == 1) $markup .= '*';
                $markup .= '</td>';
                if($_SESSION[PAGE_SESSIONID]['privileges']['menu'][1] == '1'){
                    $markup .= '<td><button class="ui-icon-pencil notext button" onclick="editItem('.$element['id'].');">'.$this->text('edit').'</button></td>';
                    $markup .= '<td><button class="ui-icon-carat-2-n-s notext button" onclick="moveItem('.$element['id'].');">'.$this->text('move').'</button></td>';
                }
                if($_SESSION[PAGE_SESSIONID]['privileges']['menu'][2] == '1'){
                    $markup .= '<td><button class="ui-icon-trash notext button" onclick="confirmDeleteItem('.$element['id'].',\''.$element['name'].'\');">'.$this->text('delete').'</button></td>';
                }
                $markup .= '</tr>';
                $markup .= $this->getMenuLevelItems($element['id'], $elements ,$indent.'&nbsp;&nbsp;&nbsp;');
            }
        }
        return $markup;
    }
    
    /*
     * returning form for adding
     */
    public function getNewItemForm(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['menu'][1] == '0') die($this->text('dont_have_permission'));
        
        $data = array();
        if(isset($this->data['lang']) && $this->data['lang'] != ''){
            $data['lang'] = $this->data['lang'];
        }
        $markup = '<div><h4 class="left">'.$this->text('new_item').'</h4>';
        $markup .= $this->getForm('add',$data);
        $markup .= '</div>';
        return array('state'=>'ok','data'=> $markup);
    }
    
    /*
     * returning form for editing
     */
    public function getEditItemForm(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['menu'][1] == '0') die($this->text('dont_have_permission'));
        
        if(isset($this->data['id']) && $this->data['id'] != ''){
            $temp = dibi::query('SELECT * FROM ' . DB_TABLEPREFIX . 'MENU WHERE id='.$this->data['id']);
            $item = $temp->fetchAssoc('id');
            if(count($temp) == 1){
                $markup = '<div><h4 class="left">'.$this->text('edit_item').'</h4>';
                $item = $item[$this->data['id']];
                $markup .= $this->getForm('update',$item);
                $markup .= '</div>';
                return array('state'=>'ok','data'=> $markup);
            }
        }else{
            return array('state'=>'ok','data'=> $this->text('e1'));
        }
    }

    /*
     * returning moveitem form for editing
     */
    public function getMoveItemForm(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['menu'][1] == '0') die($this->text('dont_have_permission'));
        
        $this->datavalidator->addValidation('id','req',$this->text('e1'));
        $this->datavalidator->addValidation('id','numeric',$this->text('e2'));
        $result = $this->datavalidator->ValidateData($this->data);
        if(!$result){
            $errors = $this->datavalidator->GetErrors();
            return array('state'=>'error','data'=> reset($errors));
        }

        $temp = dibi::query('SELECT * FROM ' . DB_TABLEPREFIX . 'MENU WHERE id='.$this->data['id']);
        $item = $temp->fetchAssoc('id');
        if(count($item) == 1){
            $item = $item[$this->data['id']];
            $markup = '<div><h4 class="left">'.$this->text('move_item').': '.$item['name'].'</h4>';
            $markup .= $this->getMoveForm($item);
            $markup .= '</div>';
            return array('state'=>'ok','data'=> $markup);
        }
    }
    
    /*
     * adding item to db
     */
    public function addItem(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['menu'][1] == '0') die($this->text('dont_have_permission'));

        //checking if data is valid
        $result = $this->checkData();
        if($result !== true) return $result;

        $this->datavalidator->addValidation('parentid','req',$this->text('e3'));
        $this->datavalidator->addValidation('parentid','numeric',$this->text('e4'));
        $result = $this->datavalidator->ValidateData($this->data);
        if(!$result){
            $errors = $this->datavalidator->GetErrors();
            return array('state'=>'error','data'=> reset($errors));
        }
        
        $this->data['alias'] = $this->registry['textprocessors']->createClearAlphanumericString($this->data['name']);

        if($this->languager->getLangsCount() < 2){
            $this->data['lang'] = $this->languager->getDefaultLangId();
        }
        
        $temp = dibi::query('SELECT MAX(orderno) FROM ' . DB_TABLEPREFIX . 'MENU WHERE parentid='.$this->data['parentid']);
        if(count($temp) > 0){
            $result = $temp->fetchSingle();
            
            if($result == null){
                $this->data['orderno'] = 1;
            }else{
                $this->data['orderno'] = $result + 1;
            }
            
            $temp = dibi::query("INSERT INTO " . DB_TABLEPREFIX . "MENU",$this->data);
            $lastid = dibi::insertId();
            if($temp == 0 || $temp == 1){
                $returnvalue = array('state'=>'highlight','data'=> $this->text('s1'));                
            }else{
                $returnvalue = array('state'=>'error','data'=> $this->text('e5'));
            }
        
            if($this->data['keywords'] == ''){
                $keywordgenerator = new keywordgenerator(strip_tags(preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", file_get_contents(PAGE_URL.'index.php?category='.$lastid.'&'.PAGE_SESSIONID))));
                $keywords = $keywordgenerator->get_keywords();
                $temp = dibi::query("UPDATE " . DB_TABLEPREFIX . "MENU SET keywords='" .$keywords. "' WHERE id=" . $lastid);
                if($temp == false) $returnvalue['data'] .= '<br />'.$this->text('e41');
            }
        }else{
            $returnvalue = array('state'=>'error','data'=> $this->text('e6'));
        }

        return $returnvalue;
    }
    
    /*
     * updating item in db
     */
    public function updateItem(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['menu'][1] == '0') die($this->text('dont_have_permission'));
        
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
        
        $this->data['alias'] = $this->registry['textprocessors']->createClearAlphanumericString($this->data['name']);
        
        if($this->languager->getLangsCount() < 2){
            $this->data['lang'] = $this->languager->getDefaultLangId();
        }
        
        $id = $this->data['id'];
        unset($this->data['id']);

        $temp = dibi::query("UPDATE " . DB_TABLEPREFIX . "MENU SET ",$this->data,"WHERE id=" . $id);
        $returnvalue = array();
        if($temp == 0 || $temp == 1){
            $returnvalue = array('state'=>'highlight','data'=> $this->text('s1'));
        }else{
            $returnvalue = array('state'=>'error','data'=> $this->text('e5'));
        }
        
        if($this->data['keywords'] == ''){
            $keywordgenerator = new keywordgenerator(strip_tags(preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", file_get_contents(PAGE_URL.'index.php?category='.$id.'&'.PAGE_SESSIONID))));
            $keywords = $keywordgenerator->get_keywords();
            $temp = dibi::query("UPDATE " . DB_TABLEPREFIX . "MENU SET keywords='" .$keywords. "' WHERE id=" . $id);
            if($temp == false) $returnvalue['data'] .= '<br />'.$this->text('e41');
        }

        return $returnvalue;
    }

    /*
     * updating move item in db
     */
    public function updateMoveItem(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['menu'][1] == '0') die($this->text('dont_have_permission'));

        //checking if data is valid
        $this->datavalidator->addValidation('id','req',$this->text('e1'));
        $this->datavalidator->addValidation('id','numeric',$this->text('e2'));
        $this->datavalidator->addValidation('moveaction','req',$this->text('e7'));
        $this->datavalidator->addValidation('newcategory','req',$this->text('e8'));
        $this->datavalidator->addValidation('newcategory','numeric',$this->text('e9'));
        $result = $this->datavalidator->ValidateData($this->data);
        if(!$result){
            $errors = $this->datavalidator->GetErrors();
            return array('state'=>'error','data'=> reset($errors));
        }

        if($this->data['id'] == $this->data['newcategory']){
            return array('state'=>'error','data'=> $this->text('e10'));
        }

        //checking if destination is not sources subitem or supitem
        $temp = dibi::query('SELECT id,parentid FROM ' . DB_TABLEPREFIX . 'MENU');
        $items = $temp->fetchAssoc('id');
        $itemid = $this->data['newcategory'];
        $issubitem = false;
        while($itemid != '0'){
            if($items[$itemid]['parentid'] == $this->data['id']) $issubitem = true;
            $itemid = $items[$itemid]['parentid'];
        }
        $itemid = $this->data['id'];
        $issupitem = false;
        while($itemid != '0'){
            if($items[$itemid]['parentid'] == $this->data['newcategory']) $issupitem = true;
            $itemid = $items[$itemid]['parentid'];
        }
        if($issubitem || $issupitem) return array('state'=>'error','data'=> $this->text('e11'));

        //source item
        $temp = dibi::query('SELECT id,parentid,orderno FROM ' . DB_TABLEPREFIX . 'MENU WHERE id=' . $this->data['id']);
        $src = $temp->fetchAll();
        if (count($src) == 1) {
            $src = $src[0];
        } else {
            return array('state'=>'error','data'=> $this->text('e12'));
        }

        //destination category
        $temp = dibi::query('SELECT id,parentid,orderno FROM ' . DB_TABLEPREFIX . 'MENU WHERE id=' . $this->data['newcategory']);
        $dst = $temp->fetchAll();
        if (count($dst) == 1) {
            $dst = $dst[0];
        } else {
            return array('state'=>'error','data'=> $this->text('e12'));
        }

        $returnvalue = array('state'=>'error','data'=> $this->text('e12'));

        if ($this->data['moveaction'] == 'exchange') {
            $temp = dibi::query("UPDATE " . DB_TABLEPREFIX . "MENU SET parentid='" . $dst['parentid'] . "',orderno='" . $dst['orderno'] . "' WHERE id=" . $src['id']);
            $temp1 = dibi::query("UPDATE " . DB_TABLEPREFIX . "MENU SET parentid='" . $src['parentid'] . "',orderno='" . $src['orderno'] . "' WHERE id=" . $dst['id']);
            if ($temp >= 0 && $temp1 >= 0) {
                $returnvalue = array('state'=>'highlight','data'=> $this->text('s2'));
            } 
        } else if ($this->data['moveaction'] == 'before') {  
            $temp = dibi::query("UPDATE " . DB_TABLEPREFIX . "MENU SET orderno=orderno+1 WHERE parentid=" . $dst['parentid'] . ' AND orderno>=' . $dst['orderno']);
            $temp1 = dibi::query("UPDATE " . DB_TABLEPREFIX . "MENU SET parentid='" . $dst['parentid'] . "',orderno='" . $dst['orderno'] . "' WHERE id=" . $src['id']);
            if ($temp >= 0 && $temp1 >= 0) {
                $returnvalue = array('state'=>'highlight','data'=> $this->text('s2'));
            }
        } else if ($this->data['moveaction'] == 'after') {
            $temp = dibi::query("UPDATE " . DB_TABLEPREFIX . "MENU SET orderno=orderno+1 WHERE parentid=" . $dst['parentid'] . ' AND orderno>' . $dst['orderno']);
            $temp1 = dibi::query("UPDATE " . DB_TABLEPREFIX . "MENU SET parentid='" . $dst['parentid'] . "',orderno='" . ($dst['orderno'] + 1) . "' WHERE id=" . $src['id']);
            if ($temp >= 0 && $temp1 >= 0) {
                $returnvalue = array('state'=>'highlight','data'=> $this->text('s2'));
            }
        } else if ($this->data['moveaction'] == 'into') {
            $temp = dibi::query("SELECT MAX(orderno) FROM " . DB_TABLEPREFIX . "MENU WHERE parentid=" . $dst['id']);
            if (count($temp) == 1) {
                $orderno = $temp->fetchSingle();
                if ($orderno == NULL) {
                    $temp = dibi::query("UPDATE " . DB_TABLEPREFIX . "MENU SET parentid='" . $dst['id'] . "',orderno=1 WHERE id=" . $src['id']);
                    if ($temp >= 0) {
                        $returnvalue = array('state'=>'highlight','data'=> $this->text('s2'));
                    }
                } else {
                    $temp = dibi::query("UPDATE " . DB_TABLEPREFIX . "MENU SET parentid='" . $dst['id'] . "',orderno=" . ($orderno + 1) . " WHERE id=" . $src['id']);
                    if ($temp >= 0) {
                        $returnvalue = array('state'=>'highlight','data'=> $this->text('s2'));
                    }
                }
            }
        }

        return $returnvalue;
    }
    
    /*
     * deleting item from db
     */
    public function deleteItem(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['menu'][2] == '0') die($this->text('dont_have_permission'));
        
        $this->datavalidator->addValidation('id','req',$this->text('e1'));
        $this->datavalidator->addValidation('id','numeric',$this->text('e2'));
        $result = $this->datavalidator->ValidateData($this->data);
        if(!$result){
            $errors = $this->datavalidator->GetErrors();
            return array('state'=>'error','data'=> reset($errors));
        }
        
        $temp = dibi::query('SELECT * FROM ' . DB_TABLEPREFIX . 'MENU WHERE id='.$this->data['id']);
        $item = $temp->fetchAssoc('id');
        if(count($item) == 1){
            $result = $this->deleteCategory($this->data['id'],((isset($this->data['deletearticles']))?$this->data['deletearticles']:'0'));
            
            $item = $item[$this->data['id']];
            
            //tidy up orderno
            dibi::query("UPDATE " . DB_TABLEPREFIX . "MENU SET orderno=orderno-1 WHERE parentid=" . $item['parentid']." AND orderno>".$item['orderno']);
            
            if($result){
                $returnvalue = array('state'=>'highlight','data'=> $this->text('s3'));
            }else{
                $returnvalue = array('state'=>'error','data'=> $this->text('e13'));
            }
        }else{
            $returnvalue = array('state'=>'error','data'=> $this->text('e14'));
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
        <legend>'.$this->languager->getText('common','name').':</legend>
        <input type="text" name="name" id="name" value="'.((isset($data['name']))?$data['name']:'').'" class="ui-autocomplete-input ui-widget-content ui-corner-all" />
        </fieldset>
        <fieldset>
        <legend>'.$this->text('description').':</legend>
        <textarea name="description" id="description" class="ui-autocomplete-input ui-widget-content ui-corner-all">'.((isset($data['description']))?$data['description']:'').'</textarea>
        </fieldset>
        <fieldset>
        <legend>'.$this->text('keywords').':</legend>
        <div class="help"><p>'.$this->text('h10').'</p></div>
        <input type="text" name="keywords" id="keywords" value="'.((isset($data['keywords']))?$data['keywords']:'').'" class="ui-autocomplete-input ui-widget-content ui-corner-all" />
        </fieldset>
        <fieldset id="imagebox"'.((!isset($data['type']) || (isset($data['type']) && ($data['type'] == 's' || $data['type'] == 'v')))?'':' style="display:none;"').'>
            <legend>'.$this->text('categoryimage').':</legend>
            <div class="help"><p>'.$this->text('h11').'</p></div>
            <input class="splittedleft ui-autocomplete-input ui-widget-content ui-corner-all" type="text" name="categoryimage" id="categoryimage" value="'.((isset($data['categoryimage']))?$data['categoryimage']:'').'" />
            <button class="ui-icon-folder-collapsed button splittedright" onclick="selectCategoryFile();">'.$this->text('choose_file').'</button>
            </fieldset>
        <fieldset>
        <legend>'.$this->text('link').':</legend>
        <div class="help"><p>'.$this->text('h5').'<br />'.$this->text('h6').'<br />'.$this->text('h7').'<br />'.$this->text('h8').'</p></div>
        '.$this->getLinkTypeCombobox((isset($data['type']))?$data['type']:'').'
        <input'.((!isset($data['type']) || (isset($data['type']) && $data['type'] == 's') || (isset($data['type']) && $data['type'] == 'p'))?' style="display:none;"':'').' type="text" name="link" id="link" value="'.((isset($data['link']) && (isset($data['type']) && $data['type'] != 'p'))?$data['link']:'').'" class="splittedleft ui-autocomplete-input ui-widget-content ui-corner-all" />
        '.$this->getSubpageCombobox(((isset($data['type']) && $data['type'] == 'p')?true:false),(isset($data['link']))?$data['link']:'').'
        </fieldset>';
        if($this->languager->getLangsCount() > 1){
            $markup .= '<fieldset>
            <legend>'.$this->text('language').':</legend>
            '.$this->getLanguagesCombobox((isset($data['lang']))?$data['lang']:'').'
            </fieldset>';
        }
        if($formaction == 'add'){
            $markup .= '<fieldset>
            <legend>'.$this->text('to_category').':</legend>
            '.$this->getMenucategoriesCombobox((isset($data['parentid']))?$data['parentid']:'').'
            </fieldset>';
        }
        $markup .= '<fieldset id="layoutbox"'.((!isset($data['type']) || (isset($data['type']) && ($data['type'] == 's' || $data['type'] == 'v')))?'':' style="display:none;"').'>
        <legend>'.$this->text('catlayout').':</legend>
        '.$this->getLayoutCombobox((isset($data['layout']))?$data['layout']:'').'
        </fieldset>
        <fieldset>
        <legend>'.$this->text('settings').':</legend>
        <input type="checkbox" name="published" id="published" value="1"'.((isset($data['published']) && $data['published'] == '0')?'':' checked="checked"').' /> - '.$this->text('publish').'
        <br />
        <input type="checkbox" name="show_in_menu" id="show_in_menu" value="1"'.((isset($data['show_in_menu']) && $data['show_in_menu'] == '1')?' checked="checked"':'').'/> - '.$this->text('show_in_menu').'
        <br />
        <input type="checkbox" name="show_in_footer" id="show_in_footer" value="1"'.((isset($data['show_in_footer']) && $data['show_in_footer'] == '1')?' checked="checked"':'').'/> - '.$this->text('show_in_footer').'
        <br />
        <input type="checkbox" name="display_new_articles" id="display_new_articles" value="1"'.((isset($data['display_new_articles']) && $data['display_new_articles'] == '1')?' checked="checked"':'').' /> - '.$this->text('t5').'
        </fieldset>
        <button class="ui-icon-cancel button right" onclick="cancelAction();">'.$this->text('cancel').'</button>
        <button class="ui-icon-disk button right" onclick="'.$formaction.'Item(\''.((isset($data['id']))?$data['id']:'').'\');">'.$this->text('ok').'</button>
        <div class="clear"></div>';
        
        return $markup;
    }

    /*
     * returns moveitem form
     */
    private function getMoveForm($data = array()){

        $markup = '
        <button class="ui-icon-cancel button right" onclick="cancelAction();">'.$this->text('cancel').'</button>
        <button class="ui-icon-disk button right" onclick="updateMoveItem(\''.((isset($data['id']))?$data['id']:'').'\');">'.$this->text('ok').'</button>
        <div class="clear"></div>
        <fieldset>
        <legend>'.$this->text('t10').':</legend>
        <div class="buttonset">
            <input type="radio" id="action_1" name="action" value="exchange" checked="checked" /><label for="action_1">'.$this->text('t6').'</label>
            <input type="radio" id="action_2" name="action" value="into" /><label for="action_2">'.$this->text('t7').'</label>
            <input type="radio" id="action_3" name="action" value="after" /><label for="action_3">'.$this->text('t8').'</label>
            <input type="radio" id="action_4" name="action" value="before" /><label for="action_4">'.$this->text('t9').'</label>
        </div>
        </fieldset>
        <fieldset>
        <legend>'.$this->text('t11').':</legend>
        '.$this->getMenucategoriesCombobox((isset($data['parentid']))?$data['parentid']:'').'
        </fieldset>
        <button class="ui-icon-cancel button right" onclick="cancelAction();">'.$this->text('cancel').'</button>
        <button class="ui-icon-disk button right" onclick="updateMoveItem(\''.((isset($data['id']))?$data['id']:'').'\');">'.$this->text('ok').'</button>
        <div class="clear"></div>';

        return $markup;
    }

    /*
     * Returns the menucategories combobox
     */
    private function getMenucategoriesCombobox($active_category = 'x',$forparent = 0,$disablealias = false){
        $markup = '
        <select name="parentid" id="parentid" class="ui-autocomplete-input ui-widget-content ui-corner-all">
            <option value="0">'.$this->text('maincategory').'</option>
            '.$this->getMenucategories($active_category,$forparent,$disablealias).'
        </select>';
        return $markup;
    }

    /*
     * generates the layout combobox
     */
    private function getLayoutCombobox($activelayout = ''){
        $markup = '';
        $markup .= '<select name="layout" id="layout" class="ui-autocomplete-input ui-widget-content ui-corner-all">';
        foreach($this->text('layouts') as $key => $value){
            $markup .= '<option value="'.$key.'"'.(($key == $activelayout)?' selected="selected"':'').'>'.$value.'</option>';
        }
        $markup .= '</select>';
        return $markup;
    }
    
    /*
     * generates the link type combobox
     */
    private function getLinkTypeCombobox($activetype = ''){
        $markup = '';
        $markup .= '<select name="type" id="type" class="splittedright ui-autocomplete-input ui-widget-content ui-corner-all" onchange="controlCategoryType();">';
        foreach($this->text('linktypes') as $key => $value){
            $markup .= '<option value="'.$key.'"'.(($key == $activetype)?' selected="selected"':'').'>'.$value.'</option>';
        }
        $markup .= '</select>';
        return $markup;
    }
    
    /*
     * generates the link type combobox
     */
    private function getSubpageCombobox($visible, $active = ''){
        $markup = '';
        $subpages = scandir(FRONTEND_ABSDIRPATH.PAGESPATH);
        if($subpages == false) return '<span id="subpage">'.$this->text('e15').'</span>';
        $subpagescount = 0;
        $markup .= '<select name="subpage" id="subpage" class="splittedleft ui-autocomplete-input ui-widget-content ui-corner-all"'.(($visible)?'':' style="display:none;"').'>';
        foreach($subpages as $page){
            if(preg_match('/^.+\.php$/',$page) > 0){
                $subpagescount++;
                $pagename = str_replace('.php', '', $page);
                if(!in_array($pagename, $this->restrictedpagenames)){
                    $markup .= '<option value="'.$pagename.'"'.(($pagename == $active)?' selected="selected"':'').'>'.  ucfirst($pagename).'</option>';
                }
            }
        }
        $markup .= '</select>';
        if($subpagescount < 1) return '<span id="subpage">'.$this->text('e16').'</span>';
        return $markup;
    }
    
    /*
     * generates the layout combobox
     */
    private function getLanguagesCombobox($activelang = ''){
        $markup = '';
        $markup .= '<select name="lang" id="lang" onchange="controlCategoryLanguage(true);" class="ui-autocomplete-input ui-widget-content ui-corner-all">';
        $markup .= $this->languager->printLanguagesComboboxElements($activelang);
        $markup .= '</select>';
        return $markup;
    }

    /*
     * Checks the inputted data
     */
    private function checkData(){
        $this->datavalidator->addValidation('name','req',$this->text('e17'));
        $this->datavalidator->addValidation('name','maxlen=100',$this->text('e18'));
        $this->datavalidator->addValidation('description','req',$this->text('e19'));
        $this->datavalidator->addValidation('description','maxlen=600',$this->text('e20'));
        $this->datavalidator->addValidation('keywords','regexp=/^[^,\s]{3,}(,? ?[^,\s]{3,})*$/',$this->text('e42'));
        $this->datavalidator->addValidation('keywords','maxlen=200',$this->text('e43'));
        if(isset($this->data['categoryimage']) && substr($this->data['categoryimage'],0,7) == 'http://'){
            $this->datavalidator->addValidation('categoryimage','url',$this->text('e45'));
        }else{
            $this->datavalidator->addValidation('categoryimage','regexp=/(jpeg|jpg|png|gif)$/',$this->text('e46'));
        }
        $this->datavalidator->addValidation('categoryimage','maxlen=300',$this->text('e44'));
        $this->datavalidator->addValidation('type','req',$this->text('e21'));
        $this->datavalidator->addValidation('type','alpha',$this->text('e22'));
        switch($this->data['type']){
            case 'a':
                $this->datavalidator->addValidation('link','req',$this->text('e23'));
                $this->datavalidator->addValidation('link','numeric',$this->text('e24'));
                break;
            case 'c':
                $this->datavalidator->addValidation('link','req',$this->text('e25'));
                $this->datavalidator->addValidation('link','regexp=/^\d+(I\d+)?$/',$this->text('e26'));
                break;
            case 'v':
                $this->datavalidator->addValidation('link','req',$this->text('e27'));
                break;
            case 'e':
                $this->datavalidator->addValidation('link','req',$this->text('e28'));
                $this->datavalidator->addValidation('link','url',$this->text('e29'));
                break;
            case 'i':
                $this->datavalidator->addValidation('link','req',$this->text('e30'));
                break;
            case 'p':
                $this->datavalidator->addValidation('link','req',$this->text('e31'));
                break;
            default:
                $this->data['link'] = '';
        }
        $this->datavalidator->addValidation('link','maxlen=500',$this->text('e32'));
        $this->datavalidator->addValidation('layout','req',$this->text('e33'));
        $this->datavalidator->addValidation('layout','alpha',$this->text('e34'));
        if($this->languager->getLangsCount() > 1){
            $this->datavalidator->addValidation('lang','req',$this->text('e35'));
            $this->datavalidator->addValidation('lang','numeric',$this->text('e36'));
        }
        $this->datavalidator->addValidation('published','req',$this->text('e37'));
        $this->datavalidator->addValidation('display_new_articles','req',$this->text('e38'));
        $this->datavalidator->addValidation('show_in_menu','req',$this->text('e40'));
        $this->datavalidator->addValidation('show_in_footer','req',$this->text('e39'));

        $result = $this->datavalidator->ValidateData($this->data);
        if(!$result){
            $errors = $this->datavalidator->GetErrors();
            return array('state'=>'error','data'=> reset($errors));
        }else{
            return true;
        }
    }
}