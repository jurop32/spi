<?php

/*
 * prints menu footerlinks and other stuff related to categories
 */

class languager{
    
    /*
     * languages stored in db
     */
    private $languages = null;
    
    /*
     * languages stored in db
     */
    private $adminlanguages = null;
    
    /*
     * languages stored in db
     */
    private $defaultlangid = null;
    
    /*
     * Hods the active language texts
     */
    private $texts = array();
    
    /*
     * Registry
     */
    private $registry = null;
    
    /*
     * constructor
     */
    public function __construct($registry){
        
        $this->registry = $registry;
    
        $this->loadLanguages();
        
        //autoload common texts
        $this->loadTexts('common');
    }
    
    /*
     * returns the languages
     */
    public function getLangs(){
        return $this->languages;
    }
    
    /*
     * returns the languages
     */
    public function getAdminLangs(){
        $this->loadAdminLanguages();
        return $this->adminlanguages;
    }
    
    /*
     * returns the languages id array
     */
    public function getLangIds(){
        $langids = array();
        foreach($this->languages as $language){
            $langids[] = $language['id'];
        }
        return $langids;
    }
    
    /*
     * Retuns the admin language
     */
    public function getAdminLangCode(){
        return $this->registry['configuration']->getSetting('ADMINLANGCODE');
    }
    
    /*
     * Retuns the admin langid
     */
    public function getAdminLangId(){
        $langid = null;
        $adminlangcode = $this->getAdminLangCode();
        foreach($this->languages as $id => $language){
            if($language['shortcode'] == $adminlangcode){
                $langid = $id;
                break;
            }
        }
        return $langid;
    }
    
    /*
     * Returns the default language ID
     */
    public function getDefaultLangId(){
        if($this->defaultlangid === null){
            foreach($this->languages as $language){
                if($language['defaultlang'] == '1'){
                    $this->defaultlangid = $language['id'];
                }
            }
        }
        return $this->defaultlangid;
    }
    
    /*
     * Returns the default language code
     */
    public function getDefaultLangCode(){
        return $this->languages[$this->getDefaultLangId()]['shortcode'];
    }
    
    /*
     * Returns the count of langs
     */
    public function getLangsCount(){
        return count($this->languages);
    }
    
    /*
     * Returns if this site is multilanguage
     */
    public function siteMultilanguage(){
        if($this->getLangsCount() > 1){
            return true;
        }else{
            return false;
        }
    }
    
    /*
     * Returns the language selector combobox elements
     */
    public function printLanguagesComboboxElements($activeitem = null){
        if($activeitem == null){
            $activeitem = $this->getDefaultLangId();
        }
        $markup = '';
        foreach($this->languages as $id => $language){
            $markup .= '<option value="'.$id.'"'.(($id == $activeitem)?' selected="selected"':'').'>'.$language['name'].(($language['published'] == '0')?'-P':'').'</option>';
        }
        return $markup;
    }
    
    /*
     * returns the language name by id
     */
    public function getLangName($langid){
        if(isset($this->languages[$langid])){
            return $this->languages[$langid]['name'];
        }else{
            return null;
        }
    }
    
    /*
     * returns the language code by id
     */
    public function getLangCode($langid){
        if(isset($this->languages[$langid])){
            return strtoupper($this->languages[$langid]['shortcode']);
        }else{
            return null;
        }
    }
    
    /*
     * Loads the languages from db
     */
    private function loadLanguages(){
        if($this->languages == null){
            $temp = dibi::query('SELECT * FROM '.DB_TABLEPREFIX.'LANGUAGES ORDER BY name ASC');
            $temp = $temp->fetchAssoc('id');
            if(count($temp) > 0){
                $this->languages = $temp;
            }
        }
    }
    
    /*
     * Loads the available admin languages from filesystem
     */
    private function loadAdminLanguages(){
        if($this->adminlanguages == null){
            $this->adminlanguages = array();
            $adminlanguages = scandir(ADMIN_ABSDIRPATH.'languages');
            foreach($this->languages as $id => $language){
                if(in_array($language['shortcode'],$adminlanguages)){
                    $this->adminlanguages[$id] = $language;
                }
            }
        }
    }
    
    /*
     * returns the specified text for user
     */
    public function getText($module,$textid){
        if(!isset($this->texts[$module])){
            $this->loadTexts($module);
        }
        $tid = explode('.', $textid);
        if(count($tid) > 1){
            if(isset($this->texts[$module][$tid[0]][$tid[1]])){
                return $this->texts[$module][$tid[0]][$tid[1]];
            }else if(isset($this->texts['common'][$tid[0]][$tid[1]])){ //return from common if not defined in normal
                return $this->texts['common'][$tid[0]][$tid[1]];
            }else{
                return $module.':'.$textid;
            }
        }else{
            if(isset($this->texts[$module][$tid[0]])){
                return $this->texts[$module][$tid[0]];
            }else if(isset($this->texts['common'][$tid[0]])){ //return from common if not defined in normal
                return $this->texts['common'][$tid[0]];
            }else{
                return $module.':'.$textid;
            }
        }
    }
    
    /*
     * Loads the language for specified module
     */
    private function loadTexts($module){
        $langfilename = ADMIN_ABSDIRPATH.'languages/'.$this->getAdminLangCode().'/'.$module.'.lang';
        $langfilename1 = ADMIN_ABSDIRPATH.'modules/'.$module.'/lang/'.$this->getAdminLangCode().'.lang';
        $defaultfilename = ADMIN_ABSDIRPATH.'languages/sk/'.$module.'.lang';
        if(file_exists($langfilename)){
            $texts = parse_ini_file($langfilename,true);
        }else if(file_exists($langfilename1)){
            $texts = parse_ini_file($langfilename1,true);
        }else if(file_exists($defaultfilename)){
            $texts = parse_ini_file($defaultfilename,true);
        }else{
            die('Language error: No language files ('.$module.')!');
        }
        $this->texts[$module] = $texts;
    }
}