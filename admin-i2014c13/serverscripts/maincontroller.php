<?php

/*
 * the main class contains all required functions
 */

abstract class maincontroller{   
    /*
     * hold sthe registry
     */
    protected $registry = null;
    
    /*
     * languager
     */
    protected $languager = null;
    
    /*
     * system configuration
     */
    protected $configuration = null;
    
    /*
     * the data from user form
     */
    protected $data = null;
    
    /*
     * The current children class name
     */
    protected $datavalidator = null;
    
    /*
     * The current children class name
     */
    protected $classname = null;
     
    /*
     * Holds the translation to remove from HTML
     */
    protected $htmltranslation = array(
        '"' => '&quot;',
        "'" => '&#039;',
        '&' => '&amp;',
        '<' => '&lt;',
        '>' => '&gt;'
    );
    
    public function __construct($registry){
        require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'configuration.inc.php';
        require_once ADMIN_ABSDIRPATH.'serverscripts/connect.php';
        $this->registry = $registry;
        $this->languager = $registry['languager'];
        $this->configuration = $registry['configuration'];
        $this->datavalidator = $registry['datavalidator'];
        if(isset($registry['userdata'])){
            $this->data = $registry['userdata'];
        }
        $this->classname = get_class($this);
    }
    
    /*
     * returns the text from the languager
     */
    protected function text($text){
        return $this->languager->getText($this->classname,$text);
    }
    
    /*
    * deletes directory with contents
    */
    protected function destroyDir($dir, $virtual = false) {
        $ds = '/';
        $dir = $virtual ? realpath($dir) : $dir;
        $dir = substr($dir, -1) == $ds ? substr($dir, 0, -1) : $dir;
        if (is_dir($dir) && $handle = opendir($dir)) {
            while ($file = readdir($handle)) {
                if ($file == '.' || $file == '..') {
                    continue;
                } elseif (is_dir($dir . $ds . $file)) {
                    $this->destroyDir($dir . $ds . $file);
                } else {
                    unlink($dir . $ds . $file);
                }
            }
            closedir($handle);
            rmdir($dir);
            return true;
        } else {
            return false;
        }
    }
    
    /*
     * Function cuts the string without loosing a word
     * input: string, length
     * output: cutted string with dots on the end
     */
    protected function cutStringToWord($str, $n, $delim=' ...') {
        $len = strlen($str);
        if ($len > $n) {
            return substr($str,0,strrpos(substr($str,0,$n),' ')).$delim;
        }
        else {
            return $str;
        }
    }
    
    /*
     * Returning the menucategories combobox
     */

    protected function getMenucategories($active_category = 'x',$forparent = 0, $disablealias = true){
        $markup = '';

        if($forparent != 0){
            $temp = dibi::query("SELECT * FROM ".DB_TABLEPREFIX."MENU ORDER BY orderno ASC");
            $menuitems = $temp->fetchAssoc('id');
            $markup .= '<option value="'.$forparent.'">'.$menuitems[$forparent]['name'].'</option>';
            if(count($menuitems) > 0){
                $markup .= $this->getMenuLevel($forparent,$menuitems,'&nbsp;&nbsp;&nbsp;&nbsp;',$active_category,$disablealias);
            }
        }else{
            if($this->languager->siteMultilanguage()){
                foreach($this->languager->getLangs() as $language){
                    $temp = dibi::query("SELECT * FROM ".DB_TABLEPREFIX."MENU WHERE lang=".$language['id']." ORDER BY orderno ASC");
                    $menuitems = $temp->fetchAssoc('id');
                    if(count($menuitems) > 0){
                        $markup .= '<optgroup label="'.$language['name'].'" title="'.$language['id'].'" class="lang_'.$language['id'].'">';
                        $markup .= $this->getMenuLevel($forparent,$menuitems,'&nbsp;&nbsp;&nbsp;&nbsp;',$active_category,$disablealias);
                        $markup .= '</optgroup>';
                    }
                }
            }else{
                $temp = dibi::query("SELECT * FROM ".DB_TABLEPREFIX."MENU WHERE lang=".$this->languager->getDefaultLangId()." ORDER BY orderno ASC");
                $menuitems = $temp->fetchAssoc('id');
                if(count($menuitems) > 0){
                    $markup .= $this->getMenuLevel($forparent,$menuitems,'&nbsp;&nbsp;&nbsp;&nbsp;',$active_category,$disablealias);
                }
            }
        }

        return $markup;
    }

    /*
     * getting menuelements recursilevly
     */
    protected function getMenuLevel($parentid, $elements, $prefix, $active_category, $disablealias) {

        $menupiece = '';
        foreach ($elements as $element) {
            if ($element['parentid'] == $parentid) {
                $menupiece .= '<option value="' . $element['id'] . '"';
                if($element['id'] == $active_category) $menupiece .= ' selected="selected"';
                if($disablealias == true && $element['type'] != 's') $menupiece .= ' disabled="disabled"';
                $menupiece .= '>' . $prefix;
                if($element['type'] != 's'){
                    $menupiece .= '(Alias) ';
                }
                $menupiece .= $element['name'];
                if($element['published'] == 0){
                    $menupiece .= '-P';
                }
                if($element['display_new_articles'] == 0){
                    $menupiece .= '-N';
                }
                $menupiece .= '</option>';
                $menupiece .= $this->getMenuLevel($element['id'], $elements, $prefix . '&nbsp;&nbsp;&nbsp;&nbsp;',$active_category, $disablealias);
            }
        }

        return $menupiece;
    }
    
    /*
     * deletes category with subcategories and articles
     */
    protected function deleteCategory($id,$deletearticles = '0'){
        $delete_OK = false;
        $temp = dibi::query('DELETE FROM ' . DB_TABLEPREFIX . 'MENU WHERE id='.$id);

        if($deletearticles == '1' && $_SESSION[PAGE_SESSIONID]['privileges']['menu'][3] == '1'){
            //deleting articles files
            $articleids = dibi::query('SELECT id FROM ' . DB_TABLEPREFIX . 'ARTICLES WHERE id_menucategory='.$id);
            $articleids = $articleids->fetchAssoc('id');
            dibi::query('DELETE FROM ' . DB_TABLEPREFIX . 'ARTICLES WHERE id_menucategory='.$id);
            foreach($articleids as $article){
                if (file_exists(FRONTEND_ABSDIRPATH . ARTICLEIMAGESPATH . $article['id'])) {
                    if (!destroyDir(FRONTEND_ABSDIRPATH . ARTICLEIMAGESPATH . $article['id'])) {
                        $delete_OK = false;
                    }
                }
            }
        }else{
            //if not delete articles move them to main category
            dibi::query('UPDATE ' . DB_TABLEPREFIX . 'ARTICLES SET id_menucategory=0 WHERE id_menucategory='.$id);
        }
        
        //set users with access to this category to access the main category
        dibi::query('UPDATE ' . DB_TABLEPREFIX . 'USERS SET categoryaccess=0 WHERE categoryaccess='.$id);

        if($temp){
            $delete_OK = true;
            $temp = dibi::query('SELECT * FROM ' . DB_TABLEPREFIX . 'MENU WHERE parentid='.$id);
            if(count($temp) > 0){
                $subcategories = $temp->fetchAssoc('id');
                foreach($subcategories as $subcategory){
                    if(!$this->deleteCategory($subcategory['id'],$deletearticles)) $delete_OK = false;
                }
            }
        }else{
            $delete_OK = false;
        }
        return $delete_OK;
    }
}