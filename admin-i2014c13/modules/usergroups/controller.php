<?php
/*
 * usergroups admin controller
 */

class usergroups extends maincontroller{
    
    /*
     * Holds the available modules list
     */
    private $moduleslist;
    
    /*
     * holds the available modules ids
     */
    private $availablemodules;

    /*
     * Constructor
     */
    public function __construct($registry){
        parent::__construct($registry);
        $this->moduleslist = $this->registry['modules']->getModulesList();
        $this->availablemodules = $this->registry['modules']->getModuleIds();
    }
    
    /*
     * returning items
     */
    public function getItems(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['usergroups'][0] == '0') die($this->text('dont_have_permission'));
        
        $temp = dibi::query("SELECT * FROM " . DB_TABLEPREFIX . "USERGROUPS WHERE id>1 ORDER BY name ASC");
        if (count($temp) > 0) {
            $markup = '';
            $items = $temp->fetchAll();
            
            $markup = '<table>
            <tr>
            <th class="control ui-corner-all ui-state-hover">'.$this->text('id').'</th>
            <th class="text ui-corner-all ui-state-hover">'.$this->languager->getText('common','name').'</th>
            <th class="control ui-corner-all ui-state-hover">D</th>';
            if($_SESSION[PAGE_SESSIONID]['privileges']['usergroups'][1] == '1'){
                $markup .= '<th class="control ui-corner-all ui-state-hover"><span class="ui-icon ui-icon-pencil" title="'.$this->text('edit').'"></span></th>';
            }
            if($_SESSION[PAGE_SESSIONID]['privileges']['usergroups'][3] == '1'){
                $markup .= '<th class="control ui-corner-all ui-state-hover"><span class="ui-icon ui-icon-trash" title="'.$this->text('delete').'"></span></th>';
            }
            $markup .= '</tr>';
            foreach($items as $item){
                $markup .= '<tr>';
                $markup .= '<td>'.$item['id'].'</td>';
                $markup .= '<td class="text">'.$item['name'].'</td>';
                $markup .= '<td>';
                if($item['denylogin'] == '1') $markup .= '*';
                $markup .= '</td>';
                if($_SESSION[PAGE_SESSIONID]['privileges']['usergroups'][1] == '1'){
                    $markup .= '<td><button class="ui-icon-pencil notext button" onclick="editItem('.$item['id'].');">'.$this->text('edit').'</button></td>';
                }
                if($_SESSION[PAGE_SESSIONID]['privileges']['usergroups'][3] == '1'){
                    $markup .= '<td><button class="ui-icon-trash notext button" onclick="confirmDeleteItem('.$item['id'].',\''.$item['name'].'\');">'.$this->text('delete').'</button></td>';
                }
                $markup .= '</tr>';
            }
            $markup .= '</table>';
            return array('state'=>'ok','data'=> $markup);
        } else {
            return array('state'=>'ok','data'=> '<p>'.$this->text('t6').'</p>');
        }
    }
    
    /*
     * returning form for adding
     */
    public function getNewItemForm(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['usergroups'][1] == '0') die($this->text('dont_have_permission'));

        $markup = '<div><h4 class="left">'.$this->text('t5').'</h4>';
        $markup .= $this->getForm('add');
        $markup .= '</div>';
        return array('state'=>'ok','data'=> $markup);
    }
    
    /*
     * returning form for editing
     */
    public function getEditItemForm(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['usergroups'][1] == '0') die($this->text('dont_have_permission'));

        $this->datavalidator->addValidation('id','req', $this->text('e1'));
        $this->datavalidator->addValidation('id','numeric',$this->text('e2'));
        $result = $this->datavalidator->ValidateData($this->data);
        if(!$result){
            $errors = $this->datavalidator->GetErrors();
            return array('state'=>'error','data'=> reset($errors));
        }
        
        $temp = dibi::query('SELECT * FROM ' . DB_TABLEPREFIX . 'USERGROUPS WHERE id='.$this->data['id']);
        $item = $temp->fetchAssoc('id');
        if(count($temp) == 1){
            $markup = '<div><h4 class="left">'.$this->text('t7').'</h4>';
            $item = $item[$this->data['id']];
            $markup .= $this->getForm('update',$item);
            $markup .= '</div>';
            return array('state'=>'ok','data'=> $markup);
        }
    }
    
    /*
     * adding item to db
     */
    public function addItem(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['usergroups'][1] == '0') die($this->text('dont_have_permission'));

        //checking if data is valid
        $result = $this->checkData();
        if($result !== true) return $result;

        //setting denylogin to default if user not allowed to set it
        if($_SESSION[PAGE_SESSIONID]['privileges']['usergroups'][2] == '0'){
            $this->data['denylogin'] = '0';
        }

        //extracting privileges to array
        $newprivileges = array();
        $temp = explode('|',$this->data['privileges']);
        foreach($temp as $priv){
            $privarray = explode(':',$priv);
            $newprivileges[$privarray[0]] = $privarray[1];
        }
        unset($this->data['privileges']);

        foreach($this->availablemodules as $module){
            if(isset($newprivileges[$module]) && strlen($newprivileges[$module]) != 0){
                $this->data[$module] = $newprivileges[$module];
            }else{
                $this->data[$module] = implode("",$this->moduleslist[$module]['privileges']);
            }
        }

        $temp = dibi::query("INSERT INTO " . DB_TABLEPREFIX . "USERGROUPS ",$this->data);
        $returnvalue = array();
        if ($temp == 0 || $temp == 1){
            $returnvalue = array('state'=>'highlight','data'=> $this->text('s1'));
        }else {
            $returnvalue = array('state'=>'error','data'=> $this->text('e3'));
        }

        return $returnvalue;
    }
    
    /*
     * updating item in db
     */
    public function updateItem(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['usergroups'][1] == '0') die($this->text('dont_have_permission'));
        
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
        
        //setting denylogin to default if user not allowed to set it
        if($_SESSION[PAGE_SESSIONID]['privileges']['usergroups'][2] == '0'){
            unset($this->data['denylogin']);
        }
        
        //extracting privileges to array
        $newprivileges = array();
        $temp = explode('|',$this->data['privileges']);
        foreach($temp as $priv){
            $privarray = explode(':',$priv);
            $newprivileges[$privarray[0]] = $privarray[1];
        }
        unset($this->data['privileges']);
        
        $id = $this->data['id'];
        unset($this->data['id']);

        foreach($this->availablemodules as $module){
            if(isset($newprivileges[$module]) && strlen($newprivileges[$module]) != 0){
                $this->data[$module] = $newprivileges[$module];
            }
        }

        $temp = dibi::query("UPDATE " . DB_TABLEPREFIX . "USERGROUPS SET ",$this->data,"WHERE id=" . $id);
        $returnvalue = array();
        if ($temp == 0 || $temp == 1){
            $returnvalue = array('state'=>'highlight','data'=> $this->text('s1'));
        }else {
            $returnvalue = array('state'=>'error','data'=> $this->text('e3'));
        }

        return $returnvalue;
    }
    
    /*
     * deleting item from db
     */
    public function deleteItem(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['usergroups'][3] == '0') die($this->text('dont_have_permission'));

        $this->datavalidator->addValidation('id','req',$this->text('e1'));
        $this->datavalidator->addValidation('id','numeric',$this->text('e2'));
        $result = $this->datavalidator->ValidateData($this->data);
        if(!$result){
            $errors = $this->datavalidator->GetErrors();
            return array('state'=>'error','data'=> reset($errors));
        }
        
        $temp = dibi::query('DELETE FROM ' . DB_TABLEPREFIX . 'USERGROUPS WHERE id='.$this->data['id']);
        $returnvalue = array();
        if ($temp) {
            $returnvalue = array('state'=>'highlight','data'=> $this->text('s2'));
            $temp = dibi::query('SELECT id FROM ' . DB_TABLEPREFIX . 'USERS WHERE usergroup='.$this->data['id']);
            $users = $temp->fetchAssoc('id');
            if(count($users) > 0){
                foreach($users as $user){
                    $temp = dibi::query('DELETE FROM ' . DB_TABLEPREFIX . 'USERS WHERE id='.$user['id']);
                    
                    //assigning orders to current user
                    if(in_array('orders',$this->registry['modules']->getModuleIds())){
                        $this->registry['orders']->preassignOrders($user['id'],$_SESSION[PAGE_SESSIONID]['id']);
                    }
                    
                    if($temp){
                        if(isset($this->data['deletearticles']) && $this->data['deletearticles'] == '1' && $_SESSION[PAGE_SESSIONID]['privileges']['usergroups'][4] == '1'){
                            $temp = dibi::query('SELECT id FROM ' . DB_TABLEPREFIX . 'ARTICLES WHERE added_by='.$user['id']);
                            $articleids = $temp->fetchAssoc('id');

                            $temp = dibi::query('DELETE FROM ' . DB_TABLEPREFIX . 'ARTICLES WHERE added_by='.$user['id']);
                            if(!$temp){
                                $returnvalue['data'] .= '<br />'.$this->text('e4');
                            }

                            //deleting article files
                            foreach($articleids as $article){
                                if (file_exists(FRONTEND_ABSDIRPATH . ARTICLEIMAGESPATH . $article['id'])) {
                                    if (!$this->destroyDir(FRONTEND_ABSDIRPATH . ARTICLEIMAGESPATH . $article['id'])) {
                                        $returnvalue['data'] .= '<br />'.$this->text('e5');
                                    }
                                }
                            }
                        }
                    }else{
                        $returnvalue['data'] .= '<br />'.$this->text('e6');
                    }
                }
            }
        }else{
            $returnvalue = array('state'=>'error','data'=> $this->text('e7'));
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
        <legend>'.$this->text('t8').':</legend>
        <input class="ui-autocomplete-input ui-widget-content ui-corner-all" type="text" name="name" id="name" value="'.((isset($data['name']))?$data['name']:'').'" />
        </fieldset>
        <fieldset>
        <legend>'.$this->text('t9').':</legend>'
        .$this->getPrivilegesCheckboxes($data).
        '</fieldset>';
        if($_SESSION[PAGE_SESSIONID]['privileges']['usergroups'][2] == '1'){
            $markup .= '<fieldset>
            <legend>'.$this->text('settings').':</legend>
            <input type="checkbox" name="denylogin" id="denylogin" value="1"'.((isset($data['denylogin']) && $data['denylogin'] == '1')?' checked="checked"':'').'" /> - zakázať prihlásenie
            </fieldset>';
        }
        $markup .= '<button class="ui-icon-cancel button right" onclick="cancelAction();">'.$this->text('cancel').'</button>
        <button class="ui-icon-disk button right" onclick="'.$formaction.'Item(\''.((isset($data['id']))?$data['id']:'').'\');">'.$this->text('ok').'</button>
        <div class="clear"></div>';
        
        return $markup;
    }

    /*
     * Checks the inputted data
     */
    private function checkData(){
        $this->datavalidator->addValidation('name','req',$this->text('e8'));
        $this->datavalidator->addValidation('name','maxlen=50',$this->text('e9'));
        $this->datavalidator->addValidation('privileges','req',$this->text('e10'));
        $this->datavalidator->addValidation('denylogin','req',$this->text('e11'));

        $result = $this->datavalidator->ValidateData($this->data);
        if(!$result){
            $errors = $this->datavalidator->GetErrors();
            return array('state'=>'error','data'=> reset($errors));
        }else{
            return true;
        }
    }

    /*
     * returns the moduleprivileges checkboxes
     */
    private function getPrivilegesCheckboxes($data){
        $markup = '<input type="hidden" name="availablemodules" id="availablemodules" value="'.  implode(',', $this->availablemodules).'" />';
        if(count($this->availablemodules) > 0){
            foreach($this->availablemodules as $modulename){
                if($_SESSION[PAGE_SESSIONID]['privileges'][$modulename][0] == '0') continue;
                $markup .= '<br /><br /><b>'.$this->languager->getText($modulename,'name').':</b><br /><br />';

                //getting privileges default/or from group
                $moduleprivileges = '';
                if(isset($data[$modulename])){
                    $moduleprivileges = str_split($data[$modulename],1);
                }else{
                    $moduleprivileges = array_values($this->moduleslist[$modulename]['privileges']);
                }

                $i = 0;
                foreach($this->moduleslist[$modulename]['privileges'] as $privilegeid => $privilege){
                    $markup .= '<input type="checkbox"'.(($i == 0)?' onclick="controlPrivilegeHighlighting(this);"':(($moduleprivileges[0] == 0)?' disabled="disabled"':'')).' name="'.$modulename.'[]" class="'.$modulename.'" id="'.$modulename.$i.'" value="1"'.((isset($moduleprivileges[$i]) && $moduleprivileges[$i] == '1')?' checked="checked"':'').' /> - '.$this->languager->getText($modulename,'privileges.'.$privilegeid).'<br />';
                    $i++;
                }
            }
        }else{
            $markup .= $this->text('t10');
        }
        return $markup;
    }
}
