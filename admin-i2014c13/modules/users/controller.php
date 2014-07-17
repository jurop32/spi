<?php
/*
 * users controller
 */

class users extends maincontroller{
    
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
        if($_SESSION[PAGE_SESSIONID]['privileges']['users'][0] == '0') die($this->text('dont_have_permission'));
        
        $temp = dibi::query("SELECT * FROM " . DB_TABLEPREFIX . "USERS WHERE usergroup>1 ORDER BY firstname ASC");
        if (count($temp) > 0) {
            $markup = '';
            $items = $temp->fetchAll();
            
            //getting user groups
            $groups = $this->getUsergroups();
            
            $markup = '<table>
            <tr>
            <th class="control ui-corner-all ui-state-hover">'.$this->text('id').'</th>
            <th class="text ui-corner-all ui-state-hover">'.$this->text('t5').'</th>
            <th class="text ui-corner-all ui-state-hover">'.$this->text('t6').'</th>
            <th class="text ui-corner-all ui-state-hover">'.$this->text('email').'</th>
            <th class="text ui-corner-all ui-state-hover">'.$this->text('t7').'</th>
            <th class="control ui-corner-all ui-state-hover">L</th>
            <th class="control ui-corner-all ui-state-hover">D</th>';
            if($_SESSION[PAGE_SESSIONID]['privileges']['users'][1] == '1'){
                $markup .= '<th class="control ui-corner-all ui-state-hover"><span class="ui-icon ui-icon-pencil" title="'.$this->text('edit').'"></span></th>';
            }
            if($_SESSION[PAGE_SESSIONID]['privileges']['users'][3] == '1'){
                $markup .= '<th class="control ui-corner-all ui-state-hover"><span class="ui-icon ui-icon-trash" title="'.$this->text('delete').'"></span></th>';
            }
            $markup .= '</tr>';
            foreach($items as $item){
                $markup .= '<tr>';
                $markup .= '<td>'.((Int)$item['id']-1).'</td>';
                $markup .= '<td class="text">'.$item['firstname'].' '.$item['surename'].'</td>';
                $markup .= '<td class="text">'.$item['username'].'</td>';
                $markup .= '<td class="text">'.$item['email'].'</td>';
                $markup .= '<td class="text">';
                if(isset($groups[$item['usergroup']]['name'])){
                    $markup .= $groups[$item['usergroup']]['name'];
                }else{
                    $markup .= '-';
                }
                $markup .= '</td>';
                $markup .= '<td>';
                if($item['loggedin'] == '1') $markup .= '*';
                $markup .= '</td>';
                $markup .= '<td>';
                if($item['denylogin'] == '1' || $groups[$item['usergroup']]['denylogin'] == '1') $markup .= '*';
                $markup .= '</td>';
                if($_SESSION[PAGE_SESSIONID]['privileges']['users'][1] == '1'){
                    $markup .= '<td><button class="ui-icon-pencil notext button" onclick="editItem('.$item['id'].');">'.$this->text('edit').'</button></td>';
                }
                if($_SESSION[PAGE_SESSIONID]['privileges']['users'][3] == '1'){
                    $markup .= '<td><button class="ui-icon-trash notext button" onclick="confirmDeleteItem('.$item['id'].',\''.$item['firstname'].' '.$item['surename'].'\');">'.$this->text('delete').'</button></td>';
                }
                $markup .= '</tr>';
            }
            $markup .= '</table>';
            return array('state'=>'ok','data'=> $markup);
        } else {
            return array('state'=>'ok','data'=> '<p>'.$this->text('t8').'</p>');
        }
    }
    
    /*
     * returning form for adding
     */
    public function getNewItemForm(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['users'][1] == '0') die($this->text('dont_have_permission'));

        $markup = '<div><h4 class="left">'.$this->text('t4').'</h4>';
        $markup .= $this->getForm('add');
        $markup .= '</div>';
        return array('state'=>'ok','data'=> $markup);
    }
    
    /*
     * returning form for editing
     */
    public function getEditItemForm(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['users'][1] == '0') die($this->text('dont_have_permission'));

        $this->datavalidator->addValidation('id','req', $this->text('e1'));
        $this->datavalidator->addValidation('id','numeric',$this->text('e7'));        
        $result = $this->datavalidator->ValidateData($this->data);
        if(!$result){
            $errors = $this->datavalidator->GetErrors();
            return array('state'=>'error','data'=> reset($errors));
        }
        
        $temp = dibi::query('SELECT * FROM ' . DB_TABLEPREFIX . 'USERS WHERE id='.$this->data['id']);
        $item = $temp->fetchAssoc('id');
        if(count($temp) == 1){
            $markup = '<div><h4 class="left">'.$this->text('t9').'</h4>';
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
        if($_SESSION[PAGE_SESSIONID]['privileges']['users'][1] == '0') die($this->text('dont_have_permission'));

        //checking if data is valid
        $result = $this->checkData();
        if($result !== true) return $result;

        $this->datavalidator->addValidation('password','req',$this->text('e2'));
        $this->datavalidator->addValidation('password','minlen='.$this->configuration->getSetting('MIN_PASSWORDLENGTH'), sprintf($this->text('e3'),$this->configuration->getSetting('MIN_PASSWORDLENGTH')));
        $result = $this->datavalidator->ValidateData($this->data);
        if(!$result){
            $errors = $this->datavalidator->GetErrors();
            return array('state'=>'error','data'=> reset($errors),'field' => key($errors));
        }

        //setting denylogin to default if user not allowed to set it
        if($_SESSION[PAGE_SESSIONID]['privileges']['users'][2] == '0'){
            $this->data['denylogin'] = '0';
        }

        //checking if username and email are unique
        $temp = dibi::query("SELECT * FROM " . DB_TABLEPREFIX . "USERS WHERE username='".$this->data['username']."'");
        $result = $temp->fetchAll();
        if(count($result) > 0) return array('state'=>'error','data'=> $this->text('e4'),'field' => 'username');
        $temp = dibi::query("SELECT * FROM " . DB_TABLEPREFIX . "USERS WHERE email='".$this->data['email']."'");
        $result = $temp->fetchAll();
        if(count($result) > 0) return array('state'=>'error','data'=> $this->text('e5'),'field' => 'email');

        $sendnotification = false;
        if($this->data['notify'] == '1'){
            $sendnotification = true;
            $emailprops = array(
                $this->data['fronttitles'].' '.$this->data['firstname'].' '.$this->data['surename'].' '.$this->data['endtitles'],
                $this->configuration->getSetting('PAGE_TITLEPREFIX'),
                PAGE_URL.ADMINDIRNAME,
                $this->data['username'],
                $this->data['password']
            );
        }
        unset($this->data['notify']);
        
        $this->data['password'] = md5($this->data['password']);
        $this->data['cookiesecret'] = md5($this->data['username']);

        $temp = dibi::query("INSERT INTO " . DB_TABLEPREFIX . "USERS ",$this->data);
        $returnvalue = array();
        if ($temp == 0 || $temp == 1){
            $returnvalue = array('state'=>'highlight','data'=> $this->text('s1'));
            //sending email about new account
            if($sendnotification){
                $result = ECMSMailer::sendMail(MAILER_FROMMAIL, $this->data['email'], $this->text('t33').$this->configuration->getSetting('PAGE_TITLEPREFIX'), vsprintf($this->text('mailtemplate1'),$emailprops));
                if($result === false){
                    $returnvalue['data'] .= $this->text('e34');
                }
            }
        }else {
            $returnvalue = array('state'=>'error','data'=> $this->text('e6'));
        }

        return $returnvalue;
    }
    
    /*
     * updating item in db
     */
    public function updateItem(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['users'][1] == '0') die($this->text('dont_have_permission'));

        //checking if data is valid
        $result = $this->checkData();
        if($result !== true) return $result;

        $this->datavalidator->addValidation('id','req', $this->text('e1'));
        $this->datavalidator->addValidation('id','numeric',$this->text('e7'));
        
        if(strlen($this->data['password']) >= 1){
            $this->datavalidator->addValidation('password','minlen='.$this->configuration->getSetting('MIN_PASSWORDLENGTH'),sprintf($this->text('e3'),$this->configuration->getSetting('MIN_PASSWORDLENGTH')));
        }
        
        $result = $this->datavalidator->ValidateData($this->data);
        if(!$result){
            $errors = $this->datavalidator->GetErrors();
            return array('state'=>'error','data'=> reset($errors),'field' => key($errors));
        }

        //setting denylogin to default if user not allowed to set it
        if($_SESSION[PAGE_SESSIONID]['privileges']['users'][2] == '0'){
            unset($this->data['denylogin']);
        }

        //checking if username and email are unique
        $temp = dibi::query("SELECT * FROM " . DB_TABLEPREFIX . "USERS WHERE username='".$this->data['username']."' AND id!=".$this->data['id']);
        $result = $temp->fetchAll();
        if(count($result) > 0) return array('state'=>'error','data'=> $this->text('e4'),'field' => 'username');
        $temp = dibi::query("SELECT * FROM " . DB_TABLEPREFIX . "USERS WHERE email='".$this->data['email']."' AND id!=".$this->data['id']);
        $result = $temp->fetchAll();
        if(count($result) > 0) return array('state'=>'error','data'=> $this->text('e5'),'field' => 'email');

        $id = $this->data['id'];
        unset($this->data['id']);

        if(strlen($this->data['password']) > 0){
            $this->data['password'] = md5($this->data['password']);
        }else{
            unset($this->data['password']);
        }
        
        $this->data['cookiesecret'] = md5($this->data['username']);

        $temp = dibi::query("UPDATE " . DB_TABLEPREFIX . "USERS SET ",$this->data,"WHERE id=" . $id);
        $returnvalue = array();
        if ($temp == 0 || $temp == 1){
            $returnvalue = array('state'=>'highlight','data'=> $this->text('s1'));
        }else {
            $returnvalue = array('state'=>'error','data'=> $this->text('e6'));
        }

        return $returnvalue;
    }
    
    /*
     * deleting item from db
     */
    public function deleteItem(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['users'][3] == '0') die($this->text('dont_have_permission'));

        $this->datavalidator->addValidation('id','req', $this->text('e1'));
        $this->datavalidator->addValidation('id','numeric',$this->text('e7'));
        $result = $this->datavalidator->ValidateData($this->data);
        if(!$result){
            $errors = $this->datavalidator->GetErrors();
            return array('state'=>'error','data'=> reset($errors));
        }
        
        
        $temp = dibi::query('DELETE FROM ' . DB_TABLEPREFIX . 'USERS WHERE id='.$this->data['id']);
        $returnvalue = array();
        if ($temp) {
            $returnvalue = array('state'=>'highlight','data'=> $this->text('s2'));
            
            //assigning orders to current user
            if(in_array('orders',$this->registry['modules']->getModuleIds())){
                $temp = $this->registry['orders']->preassignOrders($this->data['id'],$_SESSION[PAGE_SESSIONID]['id']);
                if ($temp === false) {
                    $returnvalue['data'] .= '<br />'.$this->text('e35');
                }
            }
        
            if(isset($this->data['deletearticles']) && $this->data['deletearticles'] == '1' && $_SESSION[PAGE_SESSIONID]['privileges']['users'][4] == '1'){
                $temp = dibi::query('SELECT id FROM ' . DB_TABLEPREFIX . 'ARTICLES WHERE added_by='.$this->data['id']);
                $articleids = $temp->fetchAssoc('id');

                $temp = dibi::query('DELETE FROM ' . DB_TABLEPREFIX . 'ARTICLES WHERE added_by='.$this->data['id']);
                if(!$temp){
                    $returnvalue['data'] .= '<br />'.$this->text('e8');
                }

                //deleting article files
                foreach($articleids as $article){
                    if (file_exists(FRONTEND_ABSDIRPATH . ARTICLEIMAGESPATH . $article['id'])) {
                        if (!$this->destroyDir(FRONTEND_ABSDIRPATH . ARTICLEIMAGESPATH . $article['id'])) {
                            $returnvalue['data'] .= '<br />'.$this->text('e9');
                        }
                    }
                }
            }
        }else{
            $returnvalue = array('state'=>'error','data'=> $this->text('e10'));
        }
            
        return $returnvalue;
    }
    
    /*
     * returning form
     */
    private function getForm($formaction = 'add',$data = array()){
        
        $usergroupcombobox = $this->getUsergroupCombobox((isset($data['usergroup'])?$data['usergroup']:''));
        if($usergroupcombobox != null){
        $markup = '
            <button class="ui-icon-cancel button right" onclick="cancelAction();">'.$this->text('cancel').'</button>
            <button class="ui-icon-disk button right" onclick="'.$formaction.'Item(\''.((isset($data['id']))?$data['id']:'').'\');">'.$this->text('ok').'</button>
            <div class="clear"></div>
            <fieldset>
            <legend>'.$this->text('t5').':</legend>
            <input class="ui-autocomplete-input ui-widget-content ui-corner-all splittedright" type="text" name="fronttitles" id="fronttitles" value="'.((isset($data['fronttitles']))?$data['fronttitles']:'').'" placeholder="'.$this->text('t13').'" />
            <input class="ui-autocomplete-input ui-widget-content ui-corner-all splittedright" type="text" name="firstname" id="firstname" value="'.((isset($data['firstname']))?$data['firstname']:'').'" placeholder="'.$this->text('t10').'" />
            <input class="ui-autocomplete-input ui-widget-content ui-corner-all splittedright" type="text" name="surename" id="surename" value="'.((isset($data['surename']))?$data['surename']:'').'" placeholder="'.$this->text('t11').'" />
            <input class="ui-autocomplete-input ui-widget-content ui-corner-all splittedright" type="text" name="endtitles" id="endtitles" value="'.((isset($data['endtitles']))?$data['endtitles']:'').'" placeholder="'.$this->text('t14').'" />
            </fieldset>
            <fieldset>
            <legend>'.$this->text('t30').':</legend>
            <input class="ui-autocomplete-input ui-widget-content ui-corner-all splittedleft" type="text" name="email" id="email" value="'.((isset($data['email']))?$data['email']:'').'" placeholder="'.$this->text('email').'" />            
            <input class="ui-autocomplete-input ui-widget-content ui-corner-all splittedright" type="text" name="phone" id="phone" value="'.((isset($data['phone']))?$data['phone']:'').'" placeholder="'.$this->text('t28').'" />
            </fieldset>
            <fieldset>
            <legend>'.$this->text('t29').':</legend>
            <div class="help"><p>'.sprintf($this->text('h3'),$this->configuration->getSetting('MIN_PASSWORDLENGTH')).'</p></div>
            <input class="ui-autocomplete-input ui-widget-content ui-corner-all splittedright" type="text" name="username" id="username" value="'.((isset($data['username']))?$data['username']:'').'" placeholder="'.$this->text('t15').'" />            
            <input class="ui-autocomplete-input ui-widget-content ui-corner-all splittedright" type="text" name="password" id="password" value="" placeholder="'.$this->text('t16').'" />
            <button class="ui-icon-arrowrefresh-1-w button splittedright" onclick="generateRandomString(\'#password\',min_passwordlength);">'.$this->text('t32').'</button>
            </fieldset>
            <fieldset>
            <legend>'.$this->text('t22').':</legend>
            <input class="ui-autocomplete-input ui-widget-content ui-corner-all" type="text" name="company" id="company" value="'.((isset($data['company']))?$data['company']:'').'" placeholder="'.$this->text('t31').'" />
            <br /><br />
            <input class="ui-autocomplete-input ui-widget-content ui-corner-all splittedright" type="text" name="company_id" id="company_id" value="'.((isset($data['company_id']))?$data['company_id']:'').'" placeholder="'.$this->text('t23').'" />
            <input class="ui-autocomplete-input ui-widget-content ui-corner-all splittedright" type="text" name="vat_id" id="vat_id" value="'.((isset($data['vat_id']))?$data['vat_id']:'').'" placeholder="'.$this->text('t24').'" />
            <input class="ui-autocomplete-input ui-widget-content ui-corner-all splittedright" type="text" name="tax_id" id="tax_id" value="'.((isset($data['tax_id']))?$data['tax_id']:'').'" placeholder="'.$this->text('t25').'" />
            </fieldset>
            <fieldset>
            <legend>'.$this->text('t26').':</legend>
            <input class="ui-autocomplete-input ui-widget-content ui-corner-all splittedleft" type="text" name="address" id="address" value="'.((isset($data['address']))?$data['address']:'').'" placeholder="'.$this->text('h4').'" />            
            '.$this->getCountryCombobox((isset($data['country']))?$data['country']:'').'
            </fieldset>
            <fieldset>
            <legend>'.$this->text('t17').':</legend>
            '.$this->getMenuCombobox((isset($data['categoryaccess']))?$data['categoryaccess']:'').'
            </fieldset>
            <fieldset>
            <legend>'.$this->text('t7').':</legend>
            '.$usergroupcombobox.'
            </fieldset>
            <fieldset>
            <legend>'.$this->text('settings').':</legend>';
            if($_SESSION[PAGE_SESSIONID]['privileges']['users'][2] == '1'){
                $markup .= '<input type="checkbox" name="denylogin" id="denylogin" value="1"'.((isset($data['denylogin']) && $data['denylogin'] == '1')?' checked="checked"':'').'" /> - '.$this->text('t18').'<br />';
            }
            $markup .= '
            <input type="checkbox" name="newsletter" id="newsletter" value="1"'.((isset($data['newsletter']) && $data['newsletter'] == '1')?' checked="checked"':'').'" /> - '.$this->text('t19').'
            '.(($formaction == 'add')?'<br /><input type="checkbox" name="notify" id="notify" value="1" checked="checked" /> - '.$this->text('t34'):'').'
            </fieldset>
            <button class="ui-icon-cancel button right" onclick="cancelAction();">'.$this->text('cancel').'</button>
            <button class="ui-icon-disk button right" onclick="'.$formaction.'Item(\''.((isset($data['id']))?$data['id']:'').'\');">'.$this->text('ok').'</button>
            <div class="clear"></div>        
        ';
        }else{
            $markup = '<div class="clear"></div>'.$this->text('t20').' <button class="button" onclick="cancelAction();">'.$this->text('ok').'</button>';
        }
        return $markup;
    }

    /*
     * Checks the inputted data
     */
    private function checkData(){
        $this->datavalidator->addValidation('fronttitles','maxlen=20',$this->text('e15'));
        $this->datavalidator->addValidation('firstname','req',$this->text('e11'));
        $this->datavalidator->addValidation('firstname','maxlen=50',$this->text('e12'));
        $this->datavalidator->addValidation('surename','req',$this->text('e13'));
        $this->datavalidator->addValidation('surename','maxlen=50',$this->text('e14'));
        $this->datavalidator->addValidation('endtitles','maxlen=20',$this->text('e16'));
        $this->datavalidator->addValidation('email','req',$this->text('e19'));
        $this->datavalidator->addValidation('email','email',$this->text('e20'));
        $this->datavalidator->addValidation('phone','maxlen=25',$this->text('e27'));
        $this->datavalidator->addValidation('username','req',$this->text('e17'));
        $this->datavalidator->addValidation('username','maxlen=50',$this->text('e18'));
        $this->datavalidator->addValidation('company','maxlen=50',$this->text('e28'));
        $this->datavalidator->addValidation('company_id','maxlen=20',$this->text('e29'));
        $this->datavalidator->addValidation('vat_id','maxlen=20',$this->text('e30'));
        $this->datavalidator->addValidation('tax_id','maxlen=20',$this->text('e31'));
        $this->datavalidator->addValidation('address','maxlen=100',$this->text('e32'));
        $this->datavalidator->addValidation('country','req',$this->text('e33'));
        $this->datavalidator->addValidation('categoryaccess','req',$this->text('e25'));
        $this->datavalidator->addValidation('categoryaccess','numeric',$this->text('e26'));
        $this->datavalidator->addValidation('usergroup','req',$this->text('e21'));
        $this->datavalidator->addValidation('usergroup','numeric',$this->text('e22'));
        $this->datavalidator->addValidation('denylogin','req',$this->text('e23'));
        $this->datavalidator->addValidation('newsletter','req',$this->text('e24'));

        $result = $this->datavalidator->ValidateData($this->data);
        if(!$result){
            $errors = $this->datavalidator->GetErrors();
            return array('state'=>'error','data'=> reset($errors),'field' => key($errors));
        }else{
            return true;
        }
    }

    /*
     * Retuns the user groups combobox
     */
    private function getUsergroupCombobox($activegroup){
        $markup = '';
        $groups = $this->getUsergroups();
        if(count($groups)>0){
            $markup .= '<select name="usergroup" id="usergroup" class="ui-autocomplete-input ui-widget-content ui-corner-all">';
            foreach($groups as $group){
                $markup .= '<option value="'.$group['id'].'"';
                if($group['id'] == $activegroup) $markup .= ' selected="selected"';
                $markup .= '>'.$group['name'].'</option>';
            }
            $markup .= '</select>';
        }else{
            return null;
        }
        return $markup;
    }

    /*
     * Returns the usergroups
     */
    private function getUsergroups(){
        $temp = dibi::query('SELECT id,name,denylogin FROM ' . DB_TABLEPREFIX . 'USERGROUPS WHERE id>1');
        $groups = $temp->fetchAssoc('id');
        return $groups;
    }
    
    /*
     * Returns the menucategories combobox
     */
    private function getMenuCombobox($active_category = 'x',$forparent = 0){
        $markup = '
        <select name="categoryaccess" id="categoryaccess" class="ui-autocomplete-input ui-widget-content ui-corner-all">
            <option value="0">'.$this->text('t21').'</option>
            '.$this->getMenucategories($active_category,$forparent).'
        </select>';
        return $markup;
    }
    
    /*
     * Returns the countries combobox
     */
    private function getCountryCombobox($active_country = 'x'){
        if(($active_country == 'x' || $active_country == '') && isset($_SERVER["HTTP_ACCEPT_LANGUAGE"])){
            $active_country = substr($_SERVER["HTTP_ACCEPT_LANGUAGE"],0,2);
        }
        $markup = '
        <select name="country" id="country" class="ui-autocomplete-input ui-widget-content ui-corner-all splittedright">
            '.$this->registry['localizer']->generateCountriesComboBoxElements($active_country).'
        </select>';
        return $markup;
    }
    
    /*
     * Returns the users list for specified category/ies
     */
    public function getUsersList($groups = array()){
        $users = array();
        $query = 'SELECT id,fronttitles,firstname,surename,endtitles,company FROM ' . DB_TABLEPREFIX . 'USERS';
        if(count($groups) > 0){
            $query .= ' WHERE usergroup IN ('.implode(',',$groups).')';
        }
        $query .= ' ORDER BY firstname ASC';
        $temp = dibi::query($query);
        $temp = $temp->fetchAssoc('id');
        
        if(count($temp) > 0){
            foreach($temp as $values){
                if($values['id'] == 1) continue;
                $users[$values['id']] = $values['fronttitles'].' '.$values['firstname'].' '.$values['surename'].' '.$values['endtitles'].((strlen($values['company']) > 0)?' ('.$values['company'].')':'');
            }
        }
        
        return $users;
    }
    
    /*
     * Returns the users list for specified category/ies
     */
    public function getUsersListAllprop($groups = array()){
        $users = array();
        $query = 'SELECT id,fronttitles,firstname,surename,endtitles,company,email,phone,newsletter FROM ' . DB_TABLEPREFIX . 'USERS';
        if(count($groups) > 0){
            $query .= ' WHERE usergroup IN ('.implode(',',$groups).')';
        }
        $query .= ' ORDER BY firstname ASC';
        $temp = dibi::query($query);
        $temp = $temp->fetchAssoc('id');
        
        if(count($temp) > 0){
            if(isset($temp['1'])) unset($temp['1']);   
            $users = $temp;         
        }
        
        return $users;
    }
    
    /*
     * Returns the requested user if exists
     */
    public function getUser($id){
        $user = null;
        $temp = dibi::query('SELECT id,fronttitles,firstname,surename,endtitles,company,email,phone FROM ' . DB_TABLEPREFIX . 'USERS WHERE id='.$id);
        $temp = $temp->fetchAll();
        if(count($temp) == 1){
            $user = $temp[0];
        }
        
        return $user;
    }
}
