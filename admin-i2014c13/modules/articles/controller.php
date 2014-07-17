<?php

/*
 * script for managing articles
 */

class articles extends maincontroller{
    
    /*
     * Constructor
     */
    public function __construct($registry){
        parent::__construct($registry);
        $this->deleteOldTempdirs();
    }
    
    /*
     * returning items
     */
    public function getItems(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['articles'][0] == '0') die($this->text('dont_have_permission'));
        
        $where = '';
        $order = '';
        $limit = '';
        if(isset($this->data['category']) && $this->data['category'] != '-' && $this->data['category'] != ''){
            $where .= " WHERE id_menucategory='".$this->data['category']."'";
        }
        if(isset($this->data['nazov']) && $this->data['nazov'] != '-' && $this->data['nazov'] != ''){
            if(strlen($where) == 0){
                $where .= " WHERE article_title LIKE '%".$this->data['nazov']."%'";
            }else{
                $where .= " AND article_title LIKE '%".$this->data['nazov']."%'";
            }
        }
        if(isset($this->data['onlynotpublished']) && $this->data['onlynotpublished'] == '1'){
            if(strlen($where) == 0){
                $where .= " WHERE published=0";
            }else{
                $where .= " AND published=0";
            }
        }
        if($_SESSION[PAGE_SESSIONID]['privileges']['articles'][1] == '1' && $_SESSION[PAGE_SESSIONID]['privileges']['articles'][3] == '0'){
            if(strlen($where) == 0){
                $where .= " WHERE added_by=".$_SESSION[PAGE_SESSIONID]['id'];
            }else{
                $where .= " AND added_by=".$_SESSION[PAGE_SESSIONID]['id'];
            }
        }
        
        //if user have access to specified category limit select to this categories
        $temp = dibi::query('SELECT id,parentid,name FROM '.DB_TABLEPREFIX.'MENU');
        $categories = $temp->fetchAssoc('id');
        if($_SESSION[PAGE_SESSIONID]['privileges']['categoryaccess'] != '0'){
            $subcategories = $this->getAllSubcategories($categories,$_SESSION[PAGE_SESSIONID]['privileges']['categoryaccess']);
            $subcategories[] = $_SESSION[PAGE_SESSIONID]['privileges']['categoryaccess'];
            if(strlen($where) == 0){
                $where .= " WHERE id_menucategory IN(".implode(',',$subcategories).")";
            }else{
                $where .= " AND id_menucategory IN(".implode(',',$subcategories).")";
            }
        }
        
        //ordering the selection
        if(isset($this->data['order']) && $this->data['order'] != ''){
            $order .= " ORDER BY ".$this->data['order'];
        }
        
        //checking limit and article count information
        $temp = dibi::query('SELECT COUNT(id) FROM ' . DB_TABLEPREFIX . 'ARTICLES'.$where);
        $allresultcount = $temp->fetchSingle();
        $resultpagescount = ceil($allresultcount/$this->data['resultcount']);
        if($resultpagescount == 0) $resultpagescount = 1;
        
        //setting limit
        if(isset($this->data['resultpage']) && $this->data['resultpage'] != ''){
            $resultpage = (Int)$this->data['resultpage'];
        }else{
            $resultpage = 0;
        }
        if(isset($this->data['resultcount']) && $this->data['resultcount'] != ''){
            $limit_od = $resultpage * (Int)$this->data['resultcount'];
            if($allresultcount < $limit_od){
                $limit_od = ($resultpagescount-1) * (Int)$this->data['resultcount'];
            }
            $limit .= " LIMIT ".$limit_od.",".$this->data['resultcount'];
        }
        //die($limit);
        //die('SELECT id,layout,article_title,article_createDate,id_menucategory,publish_date,published,viewcount,topped,homepage,lang FROM ' . DB_TABLEPREFIX . 'ARTICLES'.$where.$order.$limit);
        $temp = dibi::query('SELECT id,layout,article_title,article_createDate,id_menucategory,publish_date,published,viewcount,topped,homepage,lang FROM ' . DB_TABLEPREFIX . 'ARTICLES'.$where.$order.$limit);
        $articles = $temp->fetchAll();
        if (count($temp) > 0) {
            $markup = '';

            $markup = '<table>
            <tr>
            <th class="control ui-corner-all ui-state-hover">'.$this->text('id').'</th>
            <th class="text ui-corner-all ui-state-hover">'.$this->languager->getText('common','name').'</th>
            <th class="text ui-corner-all ui-state-hover">'.$this->text('layout').'</th>
            <th class="control ui-corner-all ui-state-hover">'.$this->text('datecreated').'</th>
            <th class="text ui-corner-all ui-state-hover">'.$this->text('category').'</th>';
            if($this->languager->getLangsCount() > 1){
                $markup .= '<th class="control ui-corner-all ui-state-hover">L</th>';
            }
            $markup .= '<th class="control ui-corner-all ui-state-hover">W</th>
            <th class="control ui-corner-all ui-state-hover">P</th>
            <th class="control ui-corner-all ui-state-hover">T</th>
            <th class="control ui-corner-all ui-state-hover">H</th>';
            if($_SESSION[PAGE_SESSIONID]['privileges']['articles'][1] == '1' || $_SESSION[PAGE_SESSIONID]['privileges']['articles'][3] == '1'){
                $markup .= '<th class="control ui-corner-all ui-state-hover"><span class="ui-icon ui-icon-pencil" title="'.$this->text('edit').'"></span></th>
                            <th class="control ui-corner-all ui-state-hover"><span class="ui-icon ui-icon-trash" title="'.$this->text('delete').'"></span></th>';
            }
            $markup .= '</tr>';
            foreach($articles as $article){
                $markup .= '<tr>';
                $markup .= '<td>'.$article['id'].'</td>';
                $markup .= '<td class="text"><a href="'.PAGE_URL.'index.php?article='.$article['id'].(($this->languager->siteMultilanguage())?'&lang='.$this->languager->getLangCode($article['lang']):'').'&adminrequest=1" target="_blank">'.$article['article_title'].'</a></td>';
                $markup .= '<td class="text">'.$this->text('layouts.'.$article['layout']).'</td>';
                $markup .= '<td>'.date('d.m.Y',strtotime($article['article_createDate'])).'</td>';
                $markup .= '<td class="text">';
                if(isset($categories[$article['id_menucategory']]['name'])){
                    $markup .= $categories[$article['id_menucategory']]['name'];
                }else{
                    $markup .= $this->text('without_category');
                }
                $markup .= '</td>';
                if($this->languager->getLangsCount() > 1){
                    $markup .= '<td>'.$this->languager->getLangCode($article['lang']).'</td>';
                }
                $markup .= '<td>'.$article['viewcount'].'</td>';
                $markup .= '<td>';
                if($article['published'] == '1'){
                    if(strtotime($article['publish_date']) <= time()){
                        $markup .= '*';
                    }else{
                        $markup .= date('d.m.Y',strtotime($article['publish_date']));
                    }
                }
                $markup .= '</td><td>';
                if($article['topped'] == '1'){
                    $markup .= '*';
                }
                $markup .= '</td><td>';
                if($article['homepage'] == '1'){
                    $markup .= '*';
                }
                $markup .= '</td>';
                if($_SESSION[PAGE_SESSIONID]['privileges']['articles'][1] == '1' || $_SESSION[PAGE_SESSIONID]['privileges']['articles'][3] == '1'){
                    $markup .= '<td><button class="ui-icon-pencil notext button" onclick="editItem('.$article['id'].');">'.$this->text('edit').'</button></td>';
                    $markup .= '<td><button class="ui-icon-trash notext button" onclick="confirmDeleteItem('.$article['id'].');">'.$this->text('delete').'</button></td>';
                }
                $markup .= '</tr>';
            }
            $markup .= '</table>';
            return array('state'=>'ok','data'=> array('html' => $markup, 'resultpagescount' => $resultpagescount));
        } else {
            return array('state'=>'ok','data'=> '<p>'.$this->text('noarticles').'</p>');
        }
    }
    
    /*
     * returning form for adding
     */
    public function getNewItemForm(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['articles'][1] == '0' && $_SESSION[PAGE_SESSIONID]['privileges']['articles'][3] == '0') die($this->text('dont_have_permission'));
        
        $tempdirname = $this->createNewTempdir();
        $data = array(
            'dirname' => $tempdirname,
            'id_menucategory' => $this->data['id_menucategory']
        );
        
        if(isset($this->data['lang']) && $this->data['lang'] != ''){
            $data['lang'] = $this->data['lang'];
        }
        
        $_SESSION['articles_dirname'] = $tempdirname;
        $markup = '<div><h4 class="left">'.$this->text('new_article').'</h4>';
        $markup .= $this->getForm('add',$data);
        $markup .= '</div>';
        return array('state'=>'ok','data'=> $markup);
    }
    
    /*
     * returning form for editing
     */
    public function getEditItemForm(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['articles'][1] == '0' && $_SESSION[PAGE_SESSIONID]['privileges']['articles'][3] == '0') die($this->text('dont_have_permission'));
        
        $this->datavalidator->addValidation('id','req',$this->text('e6'));
        $this->datavalidator->addValidation('id','numeric',$this->text('e7'));
        
        $result = $this->datavalidator->ValidateData($this->data);
        if(!$result){
            $errors = $this->datavalidator->GetErrors();
            return array('state'=>'error','data'=> reset($errors));
        }
        
        $temp = dibi::query('SELECT * FROM ' . DB_TABLEPREFIX . 'ARTICLES WHERE id='.$this->data['id']);
        $article = $temp->fetchAssoc('id');
        if(count($temp) == 1){
            $article = $article[$this->data['id']];

            //testng if article is in category wehere user can add articles
            if(!$this->isPranetOfMenuitem($_SESSION[PAGE_SESSIONID]['privileges']['categoryaccess'],$article['id_menucategory'])) return array('state'=>'error','data'=> $this->text('t7'));

            $_SESSION['articles_dirname'] = $this->data['id'];
            $markup = '<div><h4 class="left">'.$this->text('edit_article').'</h4>';
            $article['dirname'] = $article['id'];
            $markup .= $this->getForm('update',$article);
            $markup .= '</div>';
            return array('state'=>'ok','data'=> $markup);
        }else{
            return array('state'=>'error','data'=> $this->text('e29'));
        }
    }
    
    /*
     * adding item to db
     */
    public function addItem(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['articles'][1] == '0' && $_SESSION[PAGE_SESSIONID]['privileges']['articles'][3] == '0') die($this->text('dont_have_permission'));
        
        //if user cannot publish articles set values
        if($_SESSION[PAGE_SESSIONID]['privileges']['articles'][2] == '0') {
            $this->data['published'] = '0';
            $this->data['publish_date'] = date('Y-m-d');
        }
       
        //checking if data is valid
        $result = $this->checkData();
        if($result !== true) return $result;

        //checking if user is allowed to edit article category articles
        if(!$this->isPranetOfMenuitem($_SESSION[PAGE_SESSIONID]['privileges']['categoryaccess'],$this->data['id_menucategory'])) return array('state'=>'error','data'=> $this->text('t8'));
        
        $this->datavalidator->addValidation('dirname','req',$this->text('t9'));
        
        //if user can change article ownership set values
        if($_SESSION[PAGE_SESSIONID]['privileges']['articles'][4] == '0') {
            $this->data['added_by'] = $_SESSION[PAGE_SESSIONID]['id'];
        }else{
            $this->datavalidator->addValidation('added_by','req',$this->text('e1'));
            $this->datavalidator->addValidation('added_by','numeric',$this->text('e2'));
        }
        
        $result = $this->datavalidator->ValidateData($this->data);
        if(!$result){
            $errors = $this->datavalidator->GetErrors();
            return array('state'=>'error','data'=> reset($errors));
        }

        //$article_content = preg_replace('/&/i','&amp;',html_entity_decode($this->data['article_content'],ENT_COMPAT,'UTF-8'));
        $article_content = stripslashes($this->data['article_content']);
        unset($this->data['article_content']);

        $dirname = $this->data['dirname'];
        unset($this->data['dirname']);

        if($this->data['keywords'] == ''){
            $keywordgenerator = new keywordgenerator($article_content.' '.$this->data['article_prologue'].' '.$this->data['article_title']);
            $this->data['keywords'] = $keywordgenerator->get_keywords();
        }
        
        if(strlen($this->data['keywords']) > 500) $this->data['keywords'] = substr($this->data['keywords'],0,500);
        $this->data['alias'] = $this->registry['textprocessors']->createClearAlphanumericString($this->data['article_title']);
        $this->data['updated_by'] = $_SESSION[PAGE_SESSIONID]['id'];
        $this->data['article_title'] = strtr($this->data['article_title'],$this->htmltranslation);
        $this->data['article_prologue'] = strtr($this->data['article_prologue'],$this->htmltranslation);

        $this->data['article_changeDate'] = date('Y-m-d H:i:s');
        $this->data['publish_date'] = date('Y-m-d H:i:s',strtotime($this->data['publish_date']));
        
        if($this->languager->getLangsCount() < 2){
            $this->data['lang'] = $this->languager->getDefaultLangId();
        }
        
        $temp = dibi::query("INSERT INTO " . DB_TABLEPREFIX . "ARTICLES ",$this->data);
        $returnvalue = array();
        if ($temp == 0 || $temp == 1)  {
            $returnvalue = array('state'=>'highlight','data'=> $this->text('article_saved'));
        }else {
            $returnvalue = array('state'=>'error','data'=> $this->text('e3'));
        }

        //renaming temporary directory of the article
        $lastid = dibi::insertId();
        if(!file_exists(FRONTEND_ABSDIRPATH.ARTICLEIMAGESPATH.$lastid)){
            rename(FRONTEND_ABSDIRPATH.ARTICLEIMAGESPATH.$dirname,FRONTEND_ABSDIRPATH.ARTICLEIMAGESPATH.$lastid);
        }else{
            $returnvalue['data'] .= '<br />'.$this->text('e4');
        }

        //replacing article dirname in the article content with the new dirname obtained from insert
        $article_content = str_replace($dirname, $lastid, $article_content);

        //beacause of error - low memory////////////////////////
        /*if(DEVELOPMENT_STATUS == true){
            $link = mysql_connect(DB_DEV_HOST, DB_DEV_USER, DB_DEV_PASSWORD);
            if (!$link) $returnvalue .= 'Could not connect: ' . mysql_error();
            mysql_select_db(DB_DEV_NAME);
        }else{
            $link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
            if (!$link) $returnvalue .= 'Could not connect: ' . mysql_error();
            mysql_select_db(DB_NAME);
        }
        $temp = mysql_query("UPDATE " . DB_TABLEPREFIX . "ARTICLES SET article_content='" . mysql_real_escape_string($article_content). "' WHERE id=" . $lastid);
        mysql_close();*/
        //potom sa odstrani/////////////////////////////////////////////////////
        
        //for other hosting than websupport
        $temp = dibi::query("UPDATE " . DB_TABLEPREFIX . "ARTICLES SET article_content='" .$article_content. "' WHERE id=" . $lastid);
        
        unset($_SESSION['articles_dirname']);
        if($temp == false) $returnvalue['data'] .= '<br />'.$this->text('e5');

        return $returnvalue;
    }
    
    /*
     * updating item in db
     */
    public function updateItem(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['articles'][1] == '0' && $_SESSION[PAGE_SESSIONID]['privileges']['articles'][3] == '0') die($this->text('dont_have_permission'));

        //checking if data is valid
        $result = $this->checkData();
        if($result !== true) return $result;
        
        //checking if user is allowed to edit article category articles
        if(!$this->isPranetOfMenuitem($_SESSION[PAGE_SESSIONID]['privileges']['categoryaccess'],$this->data['id_menucategory'])) return array('state'=>'error','data'=> $this->text('t7'));
        
        //if user cannot publish articles set values
        if($_SESSION[PAGE_SESSIONID]['privileges']['articles'][2] == '0') {
            unset($this->data['published']);
            unset($this->data['publish_date']);
        }

        $this->datavalidator->addValidation('id','req',$this->text('e6'));
        $this->datavalidator->addValidation('id','numeric',$this->text('e7'));
        
        //if user can change article ownership set values
        if($_SESSION[PAGE_SESSIONID]['privileges']['articles'][4] == '0') {
            unset($this->data['added_by']);
        }else{
            $this->datavalidator->addValidation('added_by','req',$this->text('e1'));
            $this->datavalidator->addValidation('added_by','numeric',$this->text('e2'));
        }
        
        $result = $this->datavalidator->ValidateData($this->data);
        if(!$result){
            $errors = $this->datavalidator->GetErrors();
            return array('state'=>'error','data'=> reset($errors));
        }

        $id = $this->data['id'];
        unset($this->data['id']);

        if($this->data['keywords'] == ''){
            $keywordgenerator = new keywordgenerator($this->data['article_content'].' '.$this->data['article_prologue'].' '.$this->data['article_title']);
            $this->data['keywords'] = $keywordgenerator->get_keywords();
        }
        
        //pridal som koli chybe websupportu (low memory)////////////////////////
        //$article_content = preg_replace('/&/i','&amp;',html_entity_decode($this->data['article_content'],ENT_COMPAT,'UTF-8'));
        /*$article_content = $this->data['article_content'];
        unset($this->data['article_content']);*/
        
        if(strlen($this->data['keywords']) > 500) $this->data['keywords'] = substr($this->data['keywords'],0,500);
        $this->data['alias'] = $this->registry['textprocessors']->createClearAlphanumericString($this->data['article_title']);
        $this->data['updated_by'] = $_SESSION[PAGE_SESSIONID]['id'];
        $this->data['article_title'] = strtr($this->data['article_title'],$this->htmltranslation);
        $this->data['article_prologue'] = strtr($this->data['article_prologue'],$this->htmltranslation);
        $this->data['article_content'] = stripslashes($this->data['article_content']);
        $this->data['article_changeDate'] = date('Y-m-d H:i:s');
        $this->data['publish_date'] = date('Y-m-d H:i:s',strtotime($this->data['publish_date']));
        
        if($this->languager->getLangsCount() < 2){
            $this->data['lang'] = $this->languager->getDefaultLangId();
        }
        
        $temp = dibi::query("UPDATE " . DB_TABLEPREFIX . "ARTICLES SET ",$this->data,"WHERE id='" . $id ."'");
        $returnvalue = array();
        if ($temp == 0 || $temp == 1) {
            $returnvalue = array('state'=>'highlight','data'=> $this->text('article_saved'));
        }else {
            $returnvalue = array('state'=>'error','data'=> $this->text('e3'));
        }

        //pridal som koli chybe websupportu (low memory)////////////////////////
        /*if(DEVELOPMENT_STATUS == true){
            $link = mysql_connect(DB_DEV_HOST, DB_DEV_USER, DB_DEV_PASSWORD);
            if (!$link) $returnvalue .= 'Could not connect: ' . mysql_error();
            mysql_select_db(DB_DEV_NAME);
        }else{
            $link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
            if (!$link) $returnvalue .= 'Could not connect: ' . mysql_error();
            mysql_select_db(DB_NAME);
        }
        $temp = mysql_query("UPDATE " . DB_TABLEPREFIX . "ARTICLES SET article_content='" . mysql_real_escape_string($article_content). "' WHERE id=" . $id);
        mysql_close();
        if($temp == false) $returnvalue['data'] .= '<br />Nastala chyba pri ukladaní obsahu článku do databázy!';*/
        //potom sa odstrani/////////////////////////////////////////////////////

        unset($_SESSION['articles_dirname']);

        return $returnvalue;
    }
    
    /*
     * deleting item from db
     */
    public function deleteItem(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['articles'][1] == '0' && $_SESSION[PAGE_SESSIONID]['privileges']['articles'][3] == '0') die($this->text('dont_have_permission'));
        
        //checking if user is allowed to edit article category articles
        $temp = dibi::query('SELECT id_menucategory FROM ' . DB_TABLEPREFIX . 'ARTICLES WHERE id='.$this->data['id']);
        if(!$this->isPranetOfMenuitem($_SESSION[PAGE_SESSIONID]['privileges']['categoryaccess'],$temp->fetchSingle())) return array('state'=>'error','data'=> $this->text('t7'));
                
        $this->datavalidator->addValidation('id','req',$this->text('e6'));
        $this->datavalidator->addValidation('id','numeric',$this->text('e7'));
        
        $result = $this->datavalidator->ValidateData($this->data);
        if(!$result){
            $errors = $this->datavalidator->GetErrors();
            return array('state'=>'error','data'=> reset($errors));
        }
        
        $temp = dibi::query('DELETE FROM ' . DB_TABLEPREFIX . 'ARTICLES WHERE id='.$this->data['id']);
        $returnvalue = array();
        if ($temp) {
            $returnvalue = array('state'=>'highlight','data'=> $this->text('article_deleted'));
            if (file_exists(FRONTEND_ABSDIRPATH . ARTICLEIMAGESPATH . $this->data['id'])) { 
                if (!$this->destroyDir(FRONTEND_ABSDIRPATH . ARTICLEIMAGESPATH . $this->data['id'])) {
                    $returnvalue['data'] .= '<br />'.$this->text('e8');
                }
            }
        }else{
            $returnvalue = array('state'=>'error','data'=> $this->text('e9'));
        }
            
        return $returnvalue;
    }
    
    /*
     * Checks the inputted data
     */
    private function checkData(){
        $this->datavalidator->addValidation('article_title','req',$this->text('e10'));
        $this->datavalidator->addValidation('article_title','maxlen=250',$this->text('e11'));
        $this->datavalidator->addValidation('article_prologue','req',$this->text('e12'));
        $this->datavalidator->addValidation('article_prologue','maxlen=3000',$this->text('e13'));
        $this->datavalidator->addValidation('keywords','regexp=/^[^,\s]{3,}(,? ?[^,\s]{3,})*$/',$this->text('e32'));
        $this->datavalidator->addValidation('keywords','maxlen=500',$this->text('e33'));
        if($this->data['layout'] != 'g' && $this->data['layout'] != 'h'){ //if article is gallery dont need content
            $this->datavalidator->addValidation('article_content','req',$this->text('e14'));
        }
        $this->datavalidator->addValidation('id_menucategory','req',$this->text('e15'));
        $this->datavalidator->addValidation('id_menucategory','numeric',$this->text('e16'));
        $this->datavalidator->addValidation('layout','req',$this->text('e17'));
        $this->datavalidator->addValidation('layout','maxlen=1',$this->text('e18'));
        if($this->languager->getLangsCount() > 1){
            $this->datavalidator->addValidation('lang','req',$this->text('e19'));
            $this->datavalidator->addValidation('lang','numeric',$this->text('e20'));
        }
        //if user cannot publish articles check publish values
        if($_SESSION[PAGE_SESSIONID]['privileges']['articles'][2] == '1') {
            $this->datavalidator->addValidation('published','req',$this->text('e21'));
            $this->datavalidator->addValidation('published','numeric',$this->text('e22'));
            $this->datavalidator->addValidation('publish_date','req',$this->text('e23'));
        }
        $this->datavalidator->addValidation('topped','req',$this->text('e24'));
        $this->datavalidator->addValidation('topped','numeric',$this->text('e25'));
        $this->datavalidator->addValidation('homepage','req',$this->text('e30'));
        $this->datavalidator->addValidation('homepage','numeric',$this->text('e31'));
        $this->datavalidator->addValidation('showsocials','req',$this->text('e26'));
        $this->datavalidator->addValidation('showsocials','numeric',$this->text('e27'));
        
        $result = $this->datavalidator->ValidateData($this->data);
        if(!$result){
            $errors = $this->datavalidator->GetErrors();
            return array('state'=>'error','data'=> reset($errors));
        }else{
            return true;
        }
    }

    /*
     * returning form
     */
    private function getForm($formaction = 'add',$data = array()){
        $markup = '
            <button class="ui-icon-cancel button right" onclick="cancelAction();">'.$this->text('cancel').'</button>
            <button class="ui-icon-disk button right" onclick="'.$formaction.'Item(\''.((isset($data['dirname']))?$data['dirname']:'').'\');">'.$this->text('ok').'</button>
            <div class="clear"></div>
            <fieldset>
            <legend>'.$this->languager->getText('common','name').':</legend>
            <div class="help"><p>'.$this->text('t10').'</p></div>
            <input class="ui-autocomplete-input ui-widget-content ui-corner-all" type="text" name="title" id="title" value="'.((isset($data['article_title']))?$data['article_title']:'').'" />
            </fieldset>
            <fieldset>
            <legend>'.$this->text('prologue').':</legend>
            <div class="help"><p>'.$this->text('t11').'</p></div>
            <textarea name="prologue" id="prologue" rows="5" cols="80" class="ui-autocomplete-input ui-widget-content ui-corner-all">'.((isset($data['article_prologue']))?$data['article_prologue']:'').'</textarea>
            </fieldset>
            <fieldset>
            <legend>'.$this->text('keywords').':</legend>
            <div class="help"><p>'.$this->text('h2').'</p></div>
            <input class="ui-autocomplete-input ui-widget-content ui-corner-all" type="text" name="keywords" id="keywords" value="'.((isset($data['keywords']))?$data['keywords']:'').'" />
            </fieldset>
            <fieldset>
            <legend>'.$this->text('content').':</legend>
            <button class="ui-icon-folder-collapsed button right bottommargin" onclick="uploadImageFiles();">'.$this->text('t15').'</button>
            <div class="help clear_r"><p>
                '.$this->text('t12').'
            </p></div>
            <textarea id="content" name="content" class="tinymce" rows="15" cols="80">'.((isset($data['article_content']))?stripslashes($data['article_content']):'').'</textarea>
            </fieldset>
            <fieldset>
            <legend>'.$this->text('category').':</legend>
            '.$this->getMenuCombobox(((isset($data['id_menucategory']))?$data['id_menucategory']:''),$_SESSION[PAGE_SESSIONID]['privileges']['categoryaccess']).'
            </fieldset>
            <fieldset>
            <legend>'.$this->text('layout').':</legend>
            '.$this->getLayoutCombobox((isset($data['layout']))?$data['layout']:'').'
            </fieldset>';
            if($this->languager->getLangsCount() > 1){
                $markup .= '<fieldset>
                <legend>'.$this->text('language').':</legend>
                '.$this->getLanguagesCombobox((isset($data['lang']))?$data['lang']:'').'
                </fieldset>';
            }
            $markup .= '<fieldset>
            <legend>'.$this->text('author').':</legend>
            <input type="text" name="author" id="author" value="'.((isset($data['author']))?$data['author']:$_SESSION[PAGE_SESSIONID]['fullname']).'" class="ui-autocomplete-input ui-widget-content ui-corner-all" />
            </fieldset>
            <fieldset>
            <legend>'.$this->text('settings').':</legend>';
            if($_SESSION[PAGE_SESSIONID]['privileges']['articles'][2] == '1'){
                $markup .= $this->text('publish').' - <input type="checkbox" name="published" id="published" value="1"'.((isset($data['published']) && $data['published'] == '0')?'':' checked="checked"').' /> | 
                '.$this->text('publishdate').': <input type="text" id="publish_date" name="publish_date" class="date splittedright ui-autocomplete-input ui-widget-content ui-corner-all" value="'.((isset($data['publish_date']))?date('d.m.Y',strtotime($data['publish_date'])):date('d.m.Y')).'" /><br /><br />';
            }
            if($_SESSION[PAGE_SESSIONID]['privileges']['articles'][4] == '1'){
                $markup .= ''.$this->text('owner').': '.$this->getUsersCombobox(((isset($data['added_by']))?$data['added_by']:$_SESSION[PAGE_SESSIONID]['id'])).'<br /><br />';
            }
            $markup .= '<input type="checkbox" name="topped" id="topped" value="1"'.((isset($data['topped']) && $data['topped'] == '1')?' checked="checked"':'').' /> - '.$this->text('t13').'
            <br />
            <input type="checkbox" name="homepage" id="homepage" value="1"'.((isset($data['homepage']) && $data['homepage'] == '1')?' checked="checked"':'').' /> - '.$this->text('t16').'
            <br />
            <input type="checkbox" name="showsocials" id="showsocials" value="1"'.((isset($data['showsocials']) && $data['showsocials'] == '1')?' checked="checked"':'').' /> - '.$this->text('t14').'
            </fieldset>
            <button class="ui-icon-cancel button right" onclick="cancelAction();">'.$this->text('cancel').'</button>
            <button class="ui-icon-disk button right" onclick="'.$formaction.'Item(\''.((isset($data['dirname']))?$data['dirname']:'').'\');">'.$this->text('ok').'</button>
            <div class="clear"></div>
        ';
        return $markup;
    }

    /*
     * Returns the menucategories combobox
     */
    private function getMenuCombobox($active_category = 'x',$forparent = 0){
        $markup = '
        <select name="id_menucategory" id="id_menucategory" class="ui-autocomplete-input ui-widget-content ui-corner-all">';
        if($forparent == '0'){
            $markup .= '<option value="0">'.$this->text('without_category').'</option>';
        }
        $markup .= $this->getMenucategories($active_category,$forparent);
        $markup .= '</select>';
        return $markup;
    }
    
    //creates new temporary directory for new article
    private function createNewTempdir(){
        $tempdirname = '';
        do{
            $tempdirname = uniqid('temp_',true);
        }while(file_exists(FRONTEND_ABSDIRPATH.ARTICLEIMAGESPATH.$tempdirname));

        if(@mkdir(FRONTEND_ABSDIRPATH.ARTICLEIMAGESPATH.$tempdirname)){
            mkdir(FRONTEND_ABSDIRPATH.ARTICLEIMAGESPATH.$tempdirname.DIRECTORY_SEPARATOR.'gallery');
        }else{
            die(json_encode(array('state'=>'error','data'=> $this->text('e28').': '.FRONTEND_ABSDIRPATH.ARTICLEIMAGESPATH.'!')));
        }
        return $tempdirname;
    }
    
    //deleting odl temporary directories
    private function deleteOldTempdirs(){
        $dirname = FRONTEND_ABSDIRPATH.ARTICLEIMAGESPATH;
        $dircontents = scandir($dirname);
        foreach($dircontents as $file){
            if (    substr($file, 0,5) == 'temp_' &&
                    is_dir($dirname.$file) &&
                    filemtime( $dirname.$file ) < strtotime('now -2 days')) {
                    $this->destroyDir($dirname.$file);
            }
        }
    }
    
    //generates the layout combobox
    private function getLayoutCombobox($activelayout = ''){
        $markup = '';
        $markup .= '<select name="layout" id="layout" class="ui-autocomplete-input ui-widget-content ui-corner-all">';
        foreach($this->text('layouts') as $key => $value){
            $markup .= '<option value="'.$key.'"'.(($key == $activelayout)?' selected="selected"':'').'>'.$value.'</option>';
        }
        $markup .= '</select>';
        return $markup;
    }
    
    //generates the userselector combobox
    private function getUsersCombobox($activeuser = 'x'){
        $markup = '';
        $markup .= '<select name="added_by" id="added_by" class="splittedright ui-autocomplete-input ui-widget-content ui-corner-all">';

        $querystring = 'SELECT id,firstname,surename FROM '.DB_TABLEPREFIX.'USERS';
        //avoiding other users to see root user and assign article to him
        if($_SESSION[PAGE_SESSIONID]['id'] != 1) $querystring .= ' WHERE id>1';
        $querystring .= ' ORDER BY firstname ASC';

        $temp = dibi::query($querystring);
        $users = $temp->fetchAssoc('id');
        foreach($users as $user){
            $markup .= '<option value="'.$user['id'].'"'.(($user['id'] == $activeuser)?' selected="selected"':'').'>'.$user['firstname'].' '.$user['surename'].'</option>';
        }
        $markup .= '</select>';
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
    
    //checks it specified menuid is the parent of the other menuid
    private function isPranetOfMenuitem($parent,$children){
        if($parent == $children) return true;
        if($parent == 0) return true;
        
        $temp = dibi::query('SELECT id,parentid FROM ' . DB_TABLEPREFIX . 'MENU');
        $categories = $temp->fetchAssoc('id');
        
        while($children != 0){
            if($parent == $children) return true;
            $children = $categories[$children]['parentid'];
        }
        return false;
    }
    
    //function returns the given category all subcategories
    private function getAllSubcategories($categories, $parentid) {
        $new_ids = array();
        foreach ($categories as $category) {
            if ($category['parentid'] == $parentid) {
                $new_ids[sizeof($new_ids)] = $category['id'];
                $new_ids = array_merge($new_ids, $this->getAllSubcategories($categories, $category['id']));
            }
        }
        return $new_ids;
    }
}
