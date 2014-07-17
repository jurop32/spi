<?php
/*
 * Statistics
 */

class stats extends maincontroller{
    
    /*
     * Holds all the stats
     */
    private $statsinfo = array();
    
    public function __construct($registry){
        parent::__construct($registry);
    }
    
    /*
     * returning items
     */
    public function getItems(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['stats'][0] == '0') die($this->text('dont_have_permission'));
        
        $this->initStats();
        
        $markup = '';
        
        $markup .= '<table class="infotable">
            <tr>
                <th class="ui-corner-all ui-state-hover">'.$this->text('statsdescr').'</th>
                <th class="ui-corner-all ui-state-hover">'.$this->text('statsdata').'</th>
            </tr>';
        foreach($this->statsinfo as $key => $value){
            $markup .= '<tr><td><b>'.ucfirst($this->text($key)).'</b></td><td>'.$value.'</td></tr>';
        }
        $markup .='</table>';
        
        return array('state'=>'ok','data'=> $markup);
    }
    
    private function initStats(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['articles'][0] == '1'){
            //getting all articles count
            $temp = dibi::query('SELECT COUNT(id) FROM ' . DB_TABLEPREFIX . 'ARTICLES');
            $this->statsinfo['all_articles_count'] = $temp->fetchSingle();

            //getting user articles count
            $temp = dibi::query('SELECT COUNT(id) FROM ' . DB_TABLEPREFIX . 'ARTICLES WHERE added_by='.$_SESSION[PAGE_SESSIONID]['id']);
            $this->statsinfo['user_all_articles_count'] = $temp->fetchSingle();

            //getting last article date
            $this->statsinfo['last_article_date'] = '-';
            $temp = dibi::query("SELECT DATE_FORMAT(article_createDate,'%Y-%m-%d') FROM " . DB_TABLEPREFIX . "ARTICLES ORDER BY article_createDate DESC LIMIT 1");
            $datestring = $this->statsinfo['last_article_date'] = $temp->fetchSingle();
            if(strlen($datestring) > 0){
                $this->statsinfo['last_article_date'] = date(DATE_FORMAT,strtotime($datestring));
            }
            
            //getting user last article date
            $this->statsinfo['user_last_article_date'] = '-';
            $temp = dibi::query("SELECT DATE_FORMAT(article_createDate,'%Y-%m-%d') FROM " . DB_TABLEPREFIX . "ARTICLES  WHERE added_by=".$_SESSION[PAGE_SESSIONID]['id'] ." ORDER BY article_createDate DESC LIMIT 1");
            $datestring = $temp->fetchSingle();
            if(strlen($datestring) > 0){
                $this->statsinfo['user_last_article_date'] = date(DATE_FORMAT,strtotime($datestring));
            }
        }
        
        if($_SESSION[PAGE_SESSIONID]['privileges']['menu'][0] == '1'){ 
            //getting categorycount
            $temp = dibi::query('SELECT COUNT(id) FROM ' . DB_TABLEPREFIX . 'MENU');
            $this->statsinfo['number_of_categories'] = $temp->fetchSingle();
        }
        
        if($_SESSION[PAGE_SESSIONID]['privileges']['videos'][0] == '1'){ 
            //getting videocount
            $temp = dibi::query('SELECT COUNT(id) FROM ' . DB_TABLEPREFIX . 'VIDEOS');
            $this->statsinfo['number_of_videos'] = $temp->fetchSingle();
        }
        
        if($_SESSION[PAGE_SESSIONID]['privileges']['banners'][0] == '1'){ 
            //getting bannercount
            $temp = dibi::query('SELECT COUNT(id) FROM ' . DB_TABLEPREFIX . 'BANNERS');
            $this->statsinfo['number_of_banners'] = $temp->fetchSingle();
        }
        
        if($_SESSION[PAGE_SESSIONID]['privileges']['slideshow'][0] == '1'){ 
            //getting slidecount
            $temp = dibi::query('SELECT COUNT(id) FROM ' . DB_TABLEPREFIX . 'SLIDESHOW');
            $this->statsinfo['number_of_slides'] = $temp->fetchSingle();
        }
        
        if($_SESSION[PAGE_SESSIONID]['privileges']['users'][0] == '1'){
            //getting usercount
            $temp = dibi::query('SELECT COUNT(id) FROM ' . DB_TABLEPREFIX . 'USERS');
            $usercount = $temp->fetchSingle();
            $this->statsinfo['number_of_users'] = (Int)$usercount - 1; //not showing root
            
            //getting loggedinusercount
            $temp = dibi::query('SELECT COUNT(id) FROM ' . DB_TABLEPREFIX . 'USERS WHERE loggedin=1');
            $usercount = $temp->fetchSingle();
            $this->statsinfo['number_of_loggedin_users'] = (Int)$usercount - 1; //not showing root
        }
        
        if($_SESSION[PAGE_SESSIONID]['privileges']['usergroups'][0] == '1'){ 
            //getting groupcount
            $temp = dibi::query('SELECT COUNT(id) FROM ' . DB_TABLEPREFIX . 'USERGROUPS');
            $groupcount = $temp->fetchSingle();
            $this->statsinfo['number_of_groups'] = (Int)$groupcount - 1; //hiding root group
        }
    }
}