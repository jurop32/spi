<?php
/*
 * profile controller
 */

class profile extends maincontroller{
    
    /*
     * Constructor
     */
    public function __construct($registry){
        parent::__construct($registry);
    }
    
    /*
     * returning items
     */
    public function getProfile(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['profile'][0] == '0') die($this->text('dont_have_permission'));
        
        $user = $this->getActiveUser();
        if ($user !== null) {
            
            $markup = '<table class="infotable">
            <tr>
                <td><b>'.$this->text('id').':</b></td>
                <td>'.$user['id'].'</td>
            </tr>
            <tr>
                <td><b>'.$this->text('t1').':</b></td>
                <td>'.$user['fronttitles'].' '.$user['firstname'].' '.$user['surename'].' '.$user['endtitles'].'</td>
            </tr>
            <tr>
                <td><b>'.$this->text('t2').':</b></td>
                <td>'.$user['username'].'</td>
            </tr>
            <tr>
                <td><b>'.$this->text('email').':</b></td>
                <td>'.$user['email'].'</td>
            </tr>
            <tr>
                <td><b>'.$this->text('t3').':</b></td>
                <td>'.$this->getUsergroup($user['usergroup']).'</td>
            </tr>
            <tr>
                <td><b>'.$this->text('t4').':</b></td>
                <td>'.(($user['newsletter'] == '0')?$this->text('tno'):$this->text('tyes')).'</td>
            </tr>
            </table>';
            
            return array('state'=>'ok','data'=> $markup);
        } else {
            return array('state'=>'ok','data'=> '<p>'.$this->text('t5').'</p>');
        }
    }
    
    /*
     * Retrurns th euser info from DB
     */
    private function getActiveUser(){
        $temp = dibi::query("SELECT * FROM " . DB_TABLEPREFIX . "USERS WHERE id=".$_SESSION[PAGE_SESSIONID]['id']);
        $temp = $temp->fetchAll();
        if(count($temp) != 1)
            return null;
        else
            return $temp[0];
    }
    
    /*
     * Returns the usergroups
     */
    private function getUsergroup($groupid){
        $temp = dibi::query('SELECT name FROM ' . DB_TABLEPREFIX . 'USERGROUPS WHERE id='.$groupid);
        return $temp->fetchSingle();
    }
    
    /*
     * returning form for editing
     */
    public function getEditItemForm(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['profile'][0] == '0') die($this->text('dont_have_permission'));
        
        $item = $this->getActiveUser();
        if($item !== null){
            $markup = '<div><h4 class="left">'.$this->text('t6').'</h4>';
            $markup .= $this->getForm($item);
            $markup .= '</div>';
            return array('state'=>'ok','data'=> $markup);
        }
    }
    
    /*
     * updating item in db
     */
    public function updateItem(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['profile'][0] == '0') die($this->text('dont_have_permission'));

        //checking if data is valid
        $result = $this->checkData();
        if($result !== true) return $result;

        if(strlen($this->data['password']) >= 1){
            $this->datavalidator->addValidation('password','minlen='.$this->configuration->getSetting('MIN_PASSWORDLENGTH'),$this->text('e3'));
        }
        
        $result = $this->datavalidator->ValidateData($this->data);
        if(!$result){
            $errors = $this->datavalidator->GetErrors();
            return array('state'=>'error','data'=> reset($errors),'field' => key($errors));
        }

        //checking if username and email are unique
        $temp = dibi::query("SELECT * FROM " . DB_TABLEPREFIX . "USERS WHERE username='".$this->data['username']."' AND id!=".$_SESSION[PAGE_SESSIONID]['id']);
        $result = $temp->fetchAll();
        if(count($result) > 0) return array('state'=>'error','data'=> $this->text('e4'),'field' => 'username');
        $temp = dibi::query("SELECT * FROM " . DB_TABLEPREFIX . "USERS WHERE email='".$this->data['email']."' AND id!=".$_SESSION[PAGE_SESSIONID]['id']);
        $result = $temp->fetchAll();
        if(count($result) > 0) return array('state'=>'error','data'=> $this->text('e5'),'field' => 'email');

        if(strlen($this->data['password']) > 0){
            $this->data['password'] = md5($this->data['password']);
        }else{
            unset($this->data['password']);
        }
        
        $this->data['cookiesecret'] = md5($this->data['username']);

        $temp = dibi::query("UPDATE " . DB_TABLEPREFIX . "USERS SET ",$this->data,"WHERE id=" . $_SESSION[PAGE_SESSIONID]['id']);
        $returnvalue = array();
        if ($temp == 0 || $temp == 1){
            $returnvalue = array('state'=>'highlight','data'=> $this->text('s1'));
        }else {
            $returnvalue = array('state'=>'error','data'=> $this->text('e6'));
        }

        return $returnvalue;
    }
    
    /*
     * returning form
     */
    private function getForm($data = array()){
        
        $markup = '
            <button class="ui-icon-cancel button right" onclick="cancelAction();">'.$this->text('cancel').'</button>
            <button class="ui-icon-disk button right" onclick="updateItem();">'.$this->text('ok').'</button>
            <div class="clear"></div>
            <fieldset>
            <legend>'.$this->text('t1').':</legend>
            <input class="ui-autocomplete-input ui-widget-content ui-corner-all splittedright" type="text" name="fronttitles" id="fronttitles" value="'.((isset($data['fronttitles']))?$data['fronttitles']:'').'" placeholder="'.$this->text('t8').'" />
            <input class="ui-autocomplete-input ui-widget-content ui-corner-all splittedright" type="text" name="firstname" id="firstname" value="'.((isset($data['firstname']))?$data['firstname']:'').'" placeholder="'.$this->text('t10').'" />
            <input class="ui-autocomplete-input ui-widget-content ui-corner-all splittedright" type="text" name="surename" id="surename" value="'.((isset($data['surename']))?$data['surename']:'').'" placeholder="'.$this->text('t7').'" />
            <input class="ui-autocomplete-input ui-widget-content ui-corner-all splittedright" type="text" name="endtitles" id="endtitles" value="'.((isset($data['endtitles']))?$data['endtitles']:'').'" placeholder="'.$this->text('t9').'" />
            </fieldset>
            <fieldset>
            <legend>'.$this->text('t13').':</legend>
            <input class="ui-autocomplete-input ui-widget-content ui-corner-all splittedleft" type="text" name="email" id="email" value="'.((isset($data['email']))?$data['email']:'').'" placeholder="'.$this->text('email').'" />            
            <input class="ui-autocomplete-input ui-widget-content ui-corner-all splittedright" type="text" name="phone" id="phone" value="'.((isset($data['phone']))?$data['phone']:'').'" placeholder="'.$this->text('t14').'" />
            </fieldset>
            <fieldset>
            <legend>'.$this->text('t15').':</legend>
            <div class="help"><p>'.sprintf($this->text('h2'),$this->configuration->getSetting('MIN_PASSWORDLENGTH')).'</p></div>
            <input class="ui-autocomplete-input ui-widget-content ui-corner-all splittedright" type="text" name="username" id="username" value="'.((isset($data['username']))?$data['username']:'').'" placeholder="'.$this->text('t2').'" />            
            <input class="ui-autocomplete-input ui-widget-content ui-corner-all splittedright" type="text" name="password" id="password" value="" placeholder="'.$this->text('t12').'" />
            <button class="ui-icon-arrowrefresh-1-w button splittedright" onclick="generateRandomString(\'#password\',min_passwordlength);">'.$this->text('t16').'</button>
            </fieldset>
            <fieldset>
            <legend>'.$this->text('t17').':</legend>
            <input class="ui-autocomplete-input ui-widget-content ui-corner-all" type="text" name="company" id="company" value="'.((isset($data['company']))?$data['company']:'').'" placeholder="'.$this->text('t18').'" />
            <br /><br />
            <input class="ui-autocomplete-input ui-widget-content ui-corner-all splittedright" type="text" name="company_id" id="company_id" value="'.((isset($data['company_id']))?$data['company_id']:'').'" placeholder="'.$this->text('t19').'" />
            <input class="ui-autocomplete-input ui-widget-content ui-corner-all splittedright" type="text" name="vat_id" id="vat_id" value="'.((isset($data['vat_id']))?$data['vat_id']:'').'" placeholder="'.$this->text('t20').'" />
            <input class="ui-autocomplete-input ui-widget-content ui-corner-all splittedright" type="text" name="tax_id" id="tax_id" value="'.((isset($data['tax_id']))?$data['tax_id']:'').'" placeholder="'.$this->text('t21').'" />
            </fieldset>
            <fieldset>
            <legend>'.$this->text('t22').':</legend>
            <input class="ui-autocomplete-input ui-widget-content ui-corner-all splittedleft" type="text" name="address" id="address" value="'.((isset($data['address']))?$data['address']:'').'" placeholder="'.$this->text('t23').'" />            
            '.$this->getCountryCombobox((isset($data['country']))?$data['country']:'').'
            </fieldset>
            <fieldset>
            <legend>'.$this->text('settings').':</legend>
            <input type="checkbox" name="newsletter" id="newsletter" value="1"'.((isset($data['newsletter']) && $data['newsletter'] == '1')?' checked="checked"':'').'" /> - '.$this->text('t11').'
            </fieldset>
            <button class="ui-icon-cancel button right" onclick="cancelAction();">'.$this->text('cancel').'</button>
            <button class="ui-icon-disk button right" onclick="updateItem();">'.$this->text('ok').'</button>
            <div class="clear"></div>        
        ';
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
        $this->datavalidator->addValidation('phone','maxlen=25',$this->text('e25'));
        $this->datavalidator->addValidation('username','req',$this->text('e17'));
        $this->datavalidator->addValidation('username','maxlen=50',$this->text('e18'));
        $this->datavalidator->addValidation('company','maxlen=50',$this->text('e26'));
        $this->datavalidator->addValidation('company_id','maxlen=20',$this->text('e27'));
        $this->datavalidator->addValidation('vat_id','maxlen=20',$this->text('e28'));
        $this->datavalidator->addValidation('tax_id','maxlen=20',$this->text('e29'));
        $this->datavalidator->addValidation('address','maxlen=100',$this->text('e30'));
        $this->datavalidator->addValidation('country','req',$this->text('e31'));
        $this->datavalidator->addValidation('newsletter','req',$this->text('e24'));

        $result = $this->datavalidator->ValidateData($this->data);
        if(!$result){
            $errors = $this->datavalidator->GetErrors();
            return array('state'=>'error','data'=> reset($errors),'field' => key($errors));
        }else{
            return true;
        }
    }
}