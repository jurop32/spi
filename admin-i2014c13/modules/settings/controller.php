<?php
/*
 * Help
 */

class settings extends maincontroller{
    
    private $SLIDESHOW_TRANSITION = array(
        'horizontal-slide',
        'vertical-slide',
        'fade'
        );
    
    private $EASING = array(
        'linear',
        'swing',
        'easeInQuad',
        'easeOutQuad',
        'easeInOutQuad',
        'easeInCubic',
        'easeOutCubic',
        'easeInOutCubic',
        'easeInQuart',
        'easeOutQuart',
        'easeInOutQuart',
        'easeInQuint',
        'easeOutQuint',
        'easeInOutQuint',
        'easeInExpo',
        'easeOutExpo',
        'easeInOutExpo',
        'easeInSine',
        'easeOutSine',
        'easeInOutSine',
        'easeInCirc',
        'easeOutCirc',
        'easeInOutCirc',
        'easeInElastic',
        'easeOutElastic',
        'easeInOutElastic',
        'easeInBack',
        'easeOutBack',
        'easeInOutBack',
        'easeInBounce',
        'easeOutBounce',
        'easeInOutBounce'
        );
    
    public function __construct($registry){
        parent::__construct($registry);
    }
    
    /*
     * returning items
     */
    public function getItems(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['settings'][0] == '0') die($this->text('dont_have_permission'));
        
        $markup = '';
        
        $markup .= '<table class="infotable">
            <tr>
                <th class="ui-corner-all ui-state-hover">'.$this->text('setting').'</th>
                <th class="ui-corner-all ui-state-hover">'.$this->text('value').'</th>
            </tr>
            <tr>
                <td><b>'.$this->text('sytem_version').'</b></td>
                <td>'.SYSTEM_VERSION.':'.SYSTEM_CHANGEVERSION.'</td>
            </tr>
            <tr>
                <td><b>'.$this->text('PAGE_TITLEPREFIX').'</b></td>
                <td>'.$this->configuration->getSetting('PAGE_TITLEPREFIX').'</td>
            </tr>
            <tr>
                <td><b>'.$this->text('PAGE_DESCRIPTION').'</b></td>
                <td>'.$this->configuration->getSetting('PAGE_DESCRIPTION').'</td>
            </tr>
            <tr>
                <td><b>'.$this->text('PAGE_KEYWORDS').'</b></td>
                <td>'.$this->configuration->getSetting('PAGE_KEYWORDS').'</td>
            </tr>
            <tr>
                <td><b>'.$this->text('PAGE_COPYRIGHT').'</b></td>
                <td>'.$this->configuration->getSetting('PAGE_COPYRIGHT').'</td>
            </tr>
            <tr>
                <td><b>'.$this->text('page_cache').'</b></td>
                <td>'.(($this->configuration->getSetting('CACHING') === true)?$this->text('tyes'):$this->text('tno')).'</td>
            </tr>';
            if($this->configuration->getSetting('CACHING') === true){
                $markup .= '<tr>
                    <td><b>'.$this->text('page_cache_time').'</b></td>
                    <td>'.$this->configuration->getSetting('CACHEEXPIRE').' '.$this->text('second').'</td>
                </tr>';
            }
            $markup .= '<tr>
                <td><b>'.$this->text('production_version').'</b></td>
                <td>'.((DEVELOPMENT_STATUS === true)?$this->text('tno'):$this->text('tyes')).'</td>
            </tr>
            <tr>
                <td><b>'.$this->text('page_offline').'</b></td>
                <td>'.(($this->configuration->getSetting('PAGEOFFLINE') === true)?$this->text('tyes'):$this->text('tno')).'</td>
            </tr>
            <tr>
                <td><b>'.$this->text('pretty_urls').'</b></td>
                <td>'.(($this->configuration->getSetting('PRETTYURLS') === true)?$this->text('tyes'):$this->text('tno')).'</td>
            </tr>
            <tr>
                <td><b>'.$this->text('support_contact').'</b></td>
                <td><a href="mailto:'.ADMINMAIL.'">'.ADMINMAIL.'</a></td>
            </tr>
        </table>';
        
        return array('state'=>'ok','data'=> $markup);
    }
    
    /*
     * returning form for editing
     */
    public function getEditItemsForm(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['settings'][1] == '0') die($this->text('dont_have_permission'));
        
        $temp = dibi::query('SELECT * FROM ' . DB_TABLEPREFIX . 'SETTINGS');
        $temp = $temp->fetchPairs('id','value');
        if(count($temp) > 0){
            $markup = '<div><h4 class="left">'.$this->text('edit_settings').'</h4>';
            $markup .= $this->getForm($temp);
            $markup .= '</div>';
            return array('state'=>'ok','data'=> $markup);
        }else{
            return array('state'=>'error','data'=> $this->text('e1'));
        }
    }
    
    /*
     * updating item in db
     */
    public function updateItems(){
        if($_SESSION[PAGE_SESSIONID]['privileges']['settings'][1] == '0') die($this->text('dont_have_permission'));

        //checking if data is valid
        $result = $this->checkData();
        if($result !== true) return $result;
        
        foreach($this->data as $id => $value){
            $temp = dibi::query("UPDATE " . DB_TABLEPREFIX . "SETTINGS SET value='".$value."' WHERE id='".$id."'");
        }
        
        $returnvalue = array();
        if ($temp == 0 || $temp == 1) {
            $returnvalue = array('state'=>'highlight','data'=> $this->text('s1'));
        }else {
            $returnvalue = array('state'=>'error','data'=> $this->text('e2'));
        }

        return $returnvalue;
    }
    
    /*
     * Checks the inputted data
     */
    private function checkData(){
        
        $validationrules = array(
            'PAGEOFFLINE' => 'regexp=/^(tru|fals)e$/',
            'PAGEOFFLINE_TEXT' => 'regexp=/^.{1,500}$/',
            'PRETTYURLS' => 'regexp=/^(tru|fals)e$/',
            'PAGINATEDARTICLECOUNT' => array('regexp=/^\d{1,2}$/','gt=0','req'),
            'PAGINATEDSWITCHCOUNT' => array('regexp=/^\d{1,2}$/','gt=0','req'),
            'SUBCATNEWARTICLECOUNT' => array('regexp=/^\d{1,2}$/','gt=0','req'),
            'FEEDARTICLECOUNT' => array('regexp=/^\d{1,2}$/','gt=0','req'),
            'GA_TRACKINGCODE' => 'regexp=/^\w{2}-\d{8}-\d*$/',
            'AUTH_SINGLEPCSIGNIN' => 'regexp=/^(tru|fals)e$/',
            'ADMINLANGCODE' => 'regexp=/^\w{2}$/',
            'PAGE_TITLEPREFIX' => 'regexp=/^.{4,50}$/',
            'PAGETITLE_SUFFIX' => 'regexp=/^(tru|fals)e$/',
            'PAGE_DESCRIPTION' => 'regexp=/^.{10,160}$/',
            'PAGE_SLOGAN' => 'regexp=/^.{1,100}$/',
            'PAGE_KEYWORDS' => array('regexp=/^[^,\s]{3,}(,? ?[^,\s]{3,})*$/','maxlen=200'),
            'PAGE_COPYRIGHT' => 'regexp=/^.{1,100}$/',
            'CACHING' => 'regexp=/^(tru|fals)e$/',
            'CACHEEXPIRE' => array('regexp=/^\d{1,5}$/','gt=0','req'),
            'PRINTFBMETA' => 'regexp=/^(tru|fals)e$/',
            'FBADMINID' => 'regexp=/^.{1,100}$/',
            'SLIDESHOW_TRANSITION' => 'req',
            'SLIDESHOW_EASING' => 'req',
            'SLIDESHOW_TRANSITIONSPEED' => array('regexp=/^\d{1,5}$/','gt=0','req'),
            'SLIDESHOW_SLIDEDELAY' => array('regexp=/^\d{1,5}$/','gt=0','req'),
            'SLIDESHOW_USESLIDESWITCH' => 'regexp=/^(tru|fals)e$/',
            'SLIDESHOW_RANDOMIZE' => 'regexp=/^(tru|fals)e$/',
            'SLIDESHOW_USENEXTPREV' => 'regexp=/^(tru|fals)e$/',
            'MINISLIDER_RANDOMIZE' => 'regexp=/^(tru|fals)e$/',
            'MINISLIDER_SLIDEWIDTH' => array('regexp=/^\d{1,3}$/','gt=0','req'),
            'MINISLIDER_EASING' => 'req',
            'MINISLIDER_SLIDINGSPEED' => array('regexp=/^\d{1,4}$/','gt=0','req'),
            'MINISLIDER_SLIDINGSTEP' => array('regexp=/^\d{1,3}$/','gt=0','req'),
            'USE_SEARCH' => 'regexp=/^(tru|fals)e$/',
            'SEARCH_DEFAULTSTRING' => 'regexp=/^.{1,50}$/',
            'USE_FEEDPARSER' => 'regexp=/^(tru|fals)e$/',
            'FEEDPARSER_URLS' => 'regexp=/^.{1,1000}$/',
            'USE_BANNERS' => 'regexp=/^(tru|fals)e$/',
            'USE_VIDEOS' => 'regexp=/^(tru|fals)e$/',
            'USE_SLIDESHOW' => 'regexp=/^(tru|fals)e$/',
            'USE_MINISLIDER' => 'regexp=/^(tru|fals)e$/',
            'USE_EVENTSCALENDAR' => 'regexp=/^(tru|fals)e$/',
            'USE_OPENINGHOURS' => 'regexp=/^(tru|fals)e$/',
            'USE_DAYOFFER' => 'regexp=/^(tru|fals)e$/',
            'USE_NEWSLETTER' => 'regexp=/^(tru|fals)e$/',
            'PUBLISH_FEEDS' => 'regexp=/^(tru|fals)e$/',
            'EVENTSCALENDARWIDGET_COUNT' => array('regexp=/^\d{1,2}$/','gt=0','req'),
            'FEEDPARSER_COUNT' =>  array('regexp=/^\d{1,2}$/','gt=0','req'),
            'USE_NEWARTICLES' => 'regexp=/^(tru|fals)e$/',
            'NEWARTICLES_COUNT' => array('regexp=/^\d{1,2}$/','gt=0','req'),
            'USE_MOSTREADARTICLES' => 'regexp=/^(tru|fals)e$/',
            'MOSTREADARTICLES_COUNT' => array('regexp=/^\d{1,2}$/','gt=0','req'),
            'VIDEOSWIDGET_COUNT' => array('regexp=/^\d{1,2}$/','gt=0','req'),
            'USE_CONTACT' => 'regexp=/^(tru|fals)e$/',
            'CONTACT_TOMAIL' => array('regexp=/^[^\s]+@[^\s]+\.[^\s]+(, [^\s]+@[^\s]+\.[^\s]+)*$/','maxlen=1000'),
            'USE_DUBLINCORE' => 'regexp=/^(tru|fals)e$/',
            'LINK_FACEBOOK' => 'url',
            'LINK_GOOGLEP' => 'url',
            'LINK_TWITTER' => 'url',
            'LINK_YOUTUBE' => 'url',
            'USE_CONTACTVCARD' => 'regexp=/^(tru|fals)e$/',
            'CONTACT_NAME' => 'maxlen=50',
            'CONTACT_STREET' => 'maxlen=30',
            'CONTACT_PSC' => 'maxlen=10',
            'CONTACT_CITY' => 'maxlen=50',
            'CONTACT_STATE' => 'maxlen=50',
            'CONTACT_WORK' => 'maxlen=20',
            'CONTACT_CELL' => 'maxlen=20',
            'CONTACT_LATITUDE' => 'regexp=/^\d{2}\.\d{5}$/',
            'CONTACT_LONGITUDE' => 'regexp=/^\d{2}\.\d{5}$/',
            'MIN_PASSWORDLENGTH' => array('req','regexp=/^\d{1,2}$/','gt=0'),
            'ID_ORDERGROUP' => 'regexp=/^\d+(,\d+)*$/'
        );
    
        foreach($this->data as $id => $value){
            if(is_array($validationrules[$id])){
                foreach($validationrules[$id] as $ruleid => $validationrule){
                    $this->datavalidator->addValidation($id,$validationrule,$this->text('e_'.$id.'_'.$ruleid));
                }
            }else{
                $this->datavalidator->addValidation($id,$validationrules[$id],$this->text('e_'.$id));
            }
        }
        
        $result = $this->datavalidator->ValidateData($this->data);
        if(!$result){
            $errors = $this->datavalidator->GetErrors();
            return array('state'=>'error','data' => reset($errors),'field' => key($errors));
        }else{
            return true;
        }
    }
    
    /*
     * returning form
     */
    private function getForm($data = array()){
        $availablemodules = $this->registry['modules']->getModuleIds();
        $markup = '
            <button class="ui-icon-cancel button right" onclick="cancelAction();">'.$this->text('cancel').'</button>
            <button class="ui-icon-disk button right" onclick="updateItems();">'.$this->text('ok').'</button>
            <div class="clear"></div>
            <div class="tabs">
            <ul>
              <li><a href="#tabs-page">'.$this->text('t1').'</a></li>
              <li><a href="#tabs-technical">'.$this->text('t3').'</a></li>
              '.(($_SESSION[PAGE_SESSIONID]['privileges']['menu'][0] == '1')?'<li><a href="#tabs-menu">'.$this->text('t2').'</a></li>':'').'
              '.((in_array('slideshow',$availablemodules) && $this->configuration->getSetting('USE_SLIDESHOW') && $_SESSION[PAGE_SESSIONID]['privileges']['slideshow'][0] == '1')?'<li><a href="#tabs-slideshow">'.$this->text('t5').'</a></li>':'').'
              '.((in_array('minislider',$availablemodules) && $this->configuration->getSetting('USE_MINISLIDER') && $_SESSION[PAGE_SESSIONID]['privileges']['minislider'][0] == '1')?'<li><a href="#tabs-minislider">'.$this->text('t6').'</a></li>':'').'
              <li><a href="#tabs-modules">'.$this->text('t7').'</a></li>
              <li><a href="#tabs-communication">'.$this->text('t4').'</a></li>
              <li><a href="#tabs-contact">'.$this->text('t8').'</a></li>
            </ul>
            <div id="tabs-page">
                <table class="infotable">
                <tr>
                <td>'.$this->text('PAGE_TITLEPREFIX').':</td>
                <td><div class="help"><p>'.$this->text('h_PAGE_TITLEPREFIX').'</p></div>'.$this->generateInput('t','PAGE_TITLEPREFIX', $data['PAGE_TITLEPREFIX']).'</td>
                </tr>
                <tr>
                <td>'.$this->text('PAGETITLE_SUFFIX').':</td>
                <td><div class="help"><p>'.$this->text('h_PAGETITLE_SUFFIX').'</p></div>'.$this->generateInput('b','PAGETITLE_SUFFIX', $data['PAGETITLE_SUFFIX']).'</td>
                </tr>
                <tr>
                <td>'.$this->text('PAGE_DESCRIPTION').':</td>
                <td><div class="help"><p>'.$this->text('h_PAGE_DESCRIPTION').'</p></div>'.$this->generateInput('t','PAGE_DESCRIPTION', $data['PAGE_DESCRIPTION']).'</td>
                </tr>
                <tr>
                <td>'.$this->text('PAGE_SLOGAN').':</td>
                <td><div class="help"><p>'.$this->text('h_PAGE_SLOGAN').'</p></div>'.$this->generateInput('t','PAGE_SLOGAN', $data['PAGE_SLOGAN']).'</td>
                </tr>
                <tr>
                <td>'.$this->text('PAGE_KEYWORDS').':</td>
                <td><div class="help"><p>'.$this->text('h_PAGE_KEYWORDS').'</p></div>'.$this->generateInput('t','PAGE_KEYWORDS', $data['PAGE_KEYWORDS']).'</td>
                </tr>
                <tr>
                <td>'.$this->text('PAGE_COPYRIGHT').':</td>
                <td><div class="help"><p>'.$this->text('h_PAGE_COPYRIGHT').'</p></div>'.$this->generateInput('t','PAGE_COPYRIGHT', $data['PAGE_COPYRIGHT']).'</td>
                </tr>
                </table>
            </div>
            <div id="tabs-technical">
                <table class="infotable">
                <tr>
                <td>'.$this->text('PAGEOFFLINE').':</td>
                <td><div class="help"><p>'.$this->text('h_PAGEOFFLINE').'</p></div>'.$this->generateInput('b','PAGEOFFLINE', $data['PAGEOFFLINE']).'</td>
                </tr>
                <tr>
                <td>'.$this->text('PAGEOFFLINE_TEXT').':</td>
                <td><div class="help"><p>'.$this->text('h_PAGEOFFLINE_TEXT').'</p></div>'.$this->generateInput('t','PAGEOFFLINE_TEXT', $data['PAGEOFFLINE_TEXT']).'</td>
                </tr>
                <tr>
                <td>'.$this->text('PRETTYURLS').':</td>
                <td><div class="help"><p>'.$this->text('h_PRETTYURLS').'</p></div>'.$this->generateInput('b','PRETTYURLS', $data['PRETTYURLS']).'</td>
                </tr>
                <tr>
                <td>'.$this->text('AUTH_SINGLEPCSIGNIN').':</td>
                <td><div class="help"><p>'.$this->text('h_AUTH_SINGLEPCSIGNIN').'</p></div>'.$this->generateInput('b','AUTH_SINGLEPCSIGNIN', $data['AUTH_SINGLEPCSIGNIN']).'</td>
                </tr>
                <tr>
                <td>'.$this->text('CACHING').':</td>
                <td><div class="help"><p>'.$this->text('h_CACHING').'</p></div>'.$this->generateInput('b','CACHING', $data['CACHING']).'</td>
                </tr>
                <tr>
                <td>'.$this->text('CACHEEXPIRE').':</td>
                <td><div class="help"><p>'.$this->text('h_CACHEEXPIRE').'</p></div>'.$this->generateInput('s','CACHEEXPIRE', $data['CACHEEXPIRE']).'</td>
                </tr>
                <tr>
                <td>'.$this->text('ADMINLANGCODE').':</td>
                <td><div class="help"><p>'.$this->text('h_ADMINLANGCODE').'</p></div>'.$this->generateInput('o','ADMINLANGCODE', $data['ADMINLANGCODE']).'</td>
                </tr>
                <tr>
                <td>'.$this->text('MIN_PASSWORDLENGTH').':</td>
                <td><div class="help"><p>'.$this->text('h_MIN_PASSWORDLENGTH').'</p></div>'.$this->generateInput('s','MIN_PASSWORDLENGTH', $data['MIN_PASSWORDLENGTH']).'</td>
                </tr>
                </table>
            </div>';
            if(($_SESSION[PAGE_SESSIONID]['privileges']['menu'][0] == '1')){
                $markup .= '
                    <div id="tabs-menu">
                    <table class="infotable">
                    <tr>
                    <td>'.$this->text('PAGINATEDARTICLECOUNT').'</td>
                    <td><div class="help"><p>'.$this->text('h_PAGINATEDARTICLECOUNT').'</p></div>'.$this->generateInput('s','PAGINATEDARTICLECOUNT', $data['PAGINATEDARTICLECOUNT']).'</td>
                    </tr>
                    <tr>
                    <td>'.$this->text('PAGINATEDSWITCHCOUNT').'</td>
                    <td><div class="help"><p>'.$this->text('h_PAGINATEDSWITCHCOUNT').'</p></div>'.$this->generateInput('s','PAGINATEDSWITCHCOUNT', $data['PAGINATEDSWITCHCOUNT']).'</td>
                    </tr>
                    <tr>
                    <td>'.$this->text('SUBCATNEWARTICLECOUNT').'</td>
                    <td><div class="help"><p>'.$this->text('h_SUBCATNEWARTICLECOUNT').'</p></div>'.$this->generateInput('s','SUBCATNEWARTICLECOUNT', $data['SUBCATNEWARTICLECOUNT']).'</td>
                    </tr>
                    </table>
                    </div>
                ';
            }
            if(in_array('slideshow',$availablemodules) && $this->configuration->getSetting('USE_SLIDESHOW') && $_SESSION[PAGE_SESSIONID]['privileges']['slideshow'][0] == '1'){
                $markup .= '
                    <div id="tabs-slideshow">
                    <table class="infotable">
                    <tr>
                    <td>'.$this->text('SLIDESHOW_TRANSITION').'</td>
                    <td><div class="help"><p>'.$this->text('h_SLIDESHOW_TRANSITION').'</p></div>'.$this->generateInput('o','SLIDESHOW_TRANSITION', $data['SLIDESHOW_TRANSITION']).'</td>
                    </tr>
                    <tr>
                    <td>'.$this->text('SLIDESHOW_EASING').'</td>
                    <td><div class="help"><p>'.$this->text('h_SLIDESHOW_EASING').'</p></div>'.$this->generateInput('o','SLIDESHOW_EASING', $data['SLIDESHOW_EASING']).'</td>
                    </tr>
                    <tr>
                    <td>'.$this->text('SLIDESHOW_TRANSITIONSPEED').'</td>
                    <td><div class="help"><p>'.$this->text('h_SLIDESHOW_TRANSITIONSPEED').'</p></div>'.$this->generateInput('s','SLIDESHOW_TRANSITIONSPEED', $data['SLIDESHOW_TRANSITIONSPEED']).'</td>
                    </tr>
                    <tr>
                    <td>'.$this->text('SLIDESHOW_SLIDEDELAY').'</td>
                    <td><div class="help"><p>'.$this->text('h_SLIDESHOW_SLIDEDELAY').'</p></div>'.$this->generateInput('s','SLIDESHOW_SLIDEDELAY', $data['SLIDESHOW_SLIDEDELAY']).'</td>
                    </tr>
                    <tr>
                    <td>'.$this->text('SLIDESHOW_USESLIDESWITCH').'</td>
                    <td><div class="help"><p>'.$this->text('h_SLIDESHOW_USESLIDESWITCH').'</p></div>'.$this->generateInput('b','SLIDESHOW_USESLIDESWITCH', $data['SLIDESHOW_USESLIDESWITCH']).'</td>
                    </tr>
                    <tr>
                    <td>'.$this->text('SLIDESHOW_USENEXTPREV').'</td>
                    <td><div class="help"><p>'.$this->text('h_SLIDESHOW_USENEXTPREV').'</p></div>'.$this->generateInput('b','SLIDESHOW_USENEXTPREV', $data['SLIDESHOW_USENEXTPREV']).'</td>
                    </tr>
                    <tr>
                    <td>'.$this->text('SLIDESHOW_RANDOMIZE').'</td>
                    <td><div class="help"><p>'.$this->text('h_SLIDESHOW_RANDOMIZE').'</p></div>'.$this->generateInput('b','SLIDESHOW_RANDOMIZE', $data['SLIDESHOW_RANDOMIZE']).'</td>
                    </tr>
                    </table>
                    </div>
                ';
            }
            if(in_array('minislider',$availablemodules) && $this->configuration->getSetting('USE_MINISLIDER') && $_SESSION[PAGE_SESSIONID]['privileges']['minislider'][0] == '1'){
                $markup .= '
                    <div id="tabs-minislider">
                    <table class="infotable">
                    <tr>
                    <td>'.$this->text('MINISLIDER_EASING').'</td>
                    <td><div class="help"><p>'.$this->text('h_MINISLIDER_EASING').'</p></div>'.$this->generateInput('o','MINISLIDER_EASING', $data['MINISLIDER_EASING']).'</td>
                    </tr>
                    <tr>
                    <td>'.$this->text('MINISLIDER_SLIDINGSPEED').'</td>
                    <td><div class="help"><p>'.$this->text('h_MINISLIDER_SLIDINGSPEED').'</p></div>'.$this->generateInput('s','MINISLIDER_SLIDINGSPEED', $data['MINISLIDER_SLIDINGSPEED']).'</td>
                    </tr>
                    <tr>
                    <td>'.$this->text('MINISLIDER_SLIDINGSTEP').'</td>
                    <td><div class="help"><p>'.$this->text('h_MINISLIDER_SLIDINGSTEP').'</p></div>'.$this->generateInput('s','MINISLIDER_SLIDINGSTEP', $data['MINISLIDER_SLIDINGSTEP']).'</td>
                    </tr>
                    <tr>
                    <td>'.$this->text('MINISLIDER_SLIDEWIDTH').'</td>
                    <td><div class="help"><p>'.$this->text('h_MINISLIDER_SLIDEWIDTH').'</p></div>'.$this->generateInput('s','MINISLIDER_SLIDEWIDTH', $data['MINISLIDER_SLIDEWIDTH']).'</td>
                    </tr>
                    </table>
                    </div>
                ';
            }
            $markup .= '
            <div id="tabs-modules">
                <table class="infotable">';
                /*$markup .= '<tr>
                <td>'.$this->text('USE_SEARCH').':</td>
                <td><div class="help"><p>'.$this->text('h_USE_SEARCH').'</p></div>'.$this->generateInput('b','USE_SEARCH', $data['USE_SEARCH']).'</td>
                </tr>
                <tr>
                <td>'.$this->text('SEARCH_DEFAULTSTRING').':</td>
                <td><div class="help"><p>'.$this->text('h_SEARCH_DEFAULTSTRING').'</p></div>'.$this->generateInput('t','SEARCH_DEFAULTSTRING', $data['SEARCH_DEFAULTSTRING']).'</td>
                </tr>
                <tr>
                <td>'.$this->text('USE_FEEDPARSER').':</td>
                <td><div class="help"><p>'.$this->text('h_USE_FEEDPARSER').'</p></div>'.$this->generateInput('b','USE_FEEDPARSER', $data['USE_FEEDPARSER']).'</td>
                </tr>
                <tr>
                <td>'.$this->text('FEEDPARSER_URLS').':</td>
                <td><div class="help"><p>'.$this->text('h_FEEDPARSER_URLS').'</p></div>'.$this->generateInput('t','FEEDPARSER_URLS', $data['FEEDPARSER_URLS']).'</td>
                </tr>
                <tr>
                <td>'.$this->text('FEEDPARSER_COUNT').':</td>
                <td><div class="help"><p>'.$this->text('h_FEEDPARSER_COUNT').'</p></div>'.$this->generateInput('s','FEEDPARSER_COUNT', $data['FEEDPARSER_COUNT']).'</td>
                </tr>';*/
                $markup .= '<tr>
                <td>'.$this->text('USE_NEWARTICLES').':</td>
                <td><div class="help"><p>'.$this->text('h_USE_NEWARTICLES').'</p></div>'.$this->generateInput('b','USE_NEWARTICLES', $data['USE_NEWARTICLES']).'</td>
                </tr>
                <tr>
                <td>'.$this->text('NEWARTICLES_COUNT').':</td>
                <td><div class="help"><p>'.$this->text('h_NEWARTICLES_COUNT').'</p></div>'.$this->generateInput('s','NEWARTICLES_COUNT', $data['NEWARTICLES_COUNT']).'</td>
                </tr>';
                /*$markup .= '<tr>
                <td>'.$this->text('USE_MOSTREADARTICLES').':</td>
                <td><div class="help"><p>'.$this->text('h_USE_MOSTREADARTICLES').'</p></div>'.$this->generateInput('b','USE_MOSTREADARTICLES', $data['USE_MOSTREADARTICLES']).'</td>
                </tr>
                <tr>
                <td>'.$this->text('MOSTREADARTICLES_COUNT').':</td>
                <td><div class="help"><p>'.$this->text('h_MOSTREADARTICLES_COUNT').'</p></div>'.$this->generateInput('s','MOSTREADARTICLES_COUNT', $data['MOSTREADARTICLES_COUNT']).'</td>
                </tr>';*/
                if(in_array('videos',$availablemodules) && $_SESSION[PAGE_SESSIONID]['privileges']['videos'][0] == '1'){
                $markup .= '<tr>
                <td>'.$this->text('USE_VIDEOS').':</td>
                <td><div class="help"><p>'.$this->text('h_USE_VIDEOS').'</p></div>'.$this->generateInput('b','USE_VIDEOS', $data['USE_VIDEOS']).'</td>
                </tr>
                <tr>
                <td>'.$this->text('VIDEOSWIDGET_COUNT').':</td>
                <td><div class="help"><p>'.$this->text('h_VIDEOSWIDGET_COUNT').'</p></div>'.$this->generateInput('s','VIDEOSWIDGET_COUNT', $data['VIDEOSWIDGET_COUNT']).'</td>
                </tr>';
                }
                if( in_array('eventscalendar',$availablemodules) && $_SESSION[PAGE_SESSIONID]['privileges']['eventscalendar'][0] == '1'){
                $markup .= '<tr>
                <td>'.$this->text('USE_EVENTSCALENDAR').':</td>
                <td><div class="help"><p>'.$this->text('h_USE_EVENTSCALENDAR').'</p></div>'.$this->generateInput('b','USE_EVENTSCALENDAR', $data['USE_EVENTSCALENDAR']).'</td>
                </tr>
                <tr>
                <td>'.$this->text('EVENTSCALENDARWIDGET_COUNT').':</td>
                <td><div class="help"><p>'.$this->text('h_EVENTSCALENDARWIDGET_COUNT').'</p></div>'.$this->generateInput('s','EVENTSCALENDARWIDGET_COUNT', $data['EVENTSCALENDARWIDGET_COUNT']).'</td>
                </tr>';
                }
                if(in_array('banners',$availablemodules) && $_SESSION[PAGE_SESSIONID]['privileges']['banners'][0] == '1'){
                $markup .= '<tr>
                <td>'.$this->text('USE_BANNERS').':</td>
                <td><div class="help"><p>'.$this->text('h_USE_BANNERS').'</p></div>'.$this->generateInput('b','USE_BANNERS', $data['USE_BANNERS']).'</td>
                </tr>';
                }
                if(in_array('slideshow',$availablemodules) && $_SESSION[PAGE_SESSIONID]['privileges']['slideshow'][0] == '1'){
                $markup .= '<tr>
                <td>'.$this->text('USE_SLIDESHOW').':</td>
                <td><div class="help"><p>'.$this->text('h_USE_SLIDESHOW').'</p></div>'.$this->generateInput('b','USE_SLIDESHOW', $data['USE_SLIDESHOW']).'</td>
                </tr>';
                }
                if(in_array('minislider',$availablemodules) && $_SESSION[PAGE_SESSIONID]['privileges']['minislider'][0] == '1'){
                $markup .= '<tr>
                <td>'.$this->text('USE_MINISLIDER').':</td>
                <td><div class="help"><p>'.$this->text('h_USE_MINISLIDER').'</p></div>'.$this->generateInput('b','USE_MINISLIDER', $data['USE_MINISLIDER']).'</td>
                </tr>';
                }
                if(in_array('openinghours',$availablemodules) && $_SESSION[PAGE_SESSIONID]['privileges']['openinghours'][0] == '1'){
                $markup .= '<tr>
                <td>'.$this->text('USE_OPENINGHOURS').':</td>
                <td><div class="help"><p>'.$this->text('h_USE_OPENINGHOURS').'</p></div>'.$this->generateInput('b','USE_OPENINGHOURS', $data['USE_OPENINGHOURS']).'</td>
                </tr>';
                }
                if(in_array('dayoffer',$availablemodules) && $_SESSION[PAGE_SESSIONID]['privileges']['dayoffer'][0] == '1'){
                $markup .= '<tr>
                <td>'.$this->text('USE_DAYOFFER').':</td>
                <td><div class="help"><p>'.$this->text('h_USE_DAYOFFER').'</p></div>'.$this->generateInput('b','USE_DAYOFFER', $data['USE_DAYOFFER']).'</td>
                </tr>';
                }
                if(in_array('newsletter',$availablemodules) && $_SESSION[PAGE_SESSIONID]['privileges']['newsletter'][0] == '1'){
                $markup .= '<tr>
                <td>'.$this->text('USE_NEWSLETTER').':</td>
                <td><div class="help"><p>'.$this->text('h_USE_NEWSLETTER').'</p></div>'.$this->generateInput('b','USE_NEWSLETTER', $data['USE_NEWSLETTER']).'</td>
                </tr>';
                }
                if(in_array('orders',$availablemodules) && $_SESSION[PAGE_SESSIONID]['privileges']['orders'][0] == '1'){
                $markup .= '<tr>
                <td>'.$this->text('ID_ORDERGROUP').':</td>
                <td><div class="help"><p>'.$this->text('h_ID_ORDERGROUP').'</p></div>'.$this->generateInput('t','ID_ORDERGROUP', $data['ID_ORDERGROUP']).'</td>
                </tr>';
                }
                $markup .= '</table>
            </div>
            <div id="tabs-communication">
                <table class="infotable">
                <tr>
                <td>'.$this->text('PUBLISH_FEEDS').':</td>
                <td><div class="help"><p>'.$this->text('h_PUBLISH_FEEDS').'</p></div>'.$this->generateInput('b','PUBLISH_FEEDS', $data['PUBLISH_FEEDS']).'</td>
                </tr>
                <tr>
                <td>'.$this->text('FEEDARTICLECOUNT').':</td>
                <td><div class="help"><p>'.$this->text('h_FEEDARTICLECOUNT').'</p></div>'.$this->generateInput('s','FEEDARTICLECOUNT', $data['FEEDARTICLECOUNT']).'</td>
                </tr>
                <tr>
                <td>'.$this->text('GA_TRACKINGCODE').':</td>
                <td><div class="help"><p>'.$this->text('h_GA_TRACKINGCODE').'</p></div>'.$this->generateInput('t','GA_TRACKINGCODE', $data['GA_TRACKINGCODE']).'</td>
                </tr>
                <tr>
                <td>'.$this->text('PRINTFBMETA').':</td>
                <td><div class="help"><p>'.$this->text('h_PRINTFBMETA').'</p></div>'.$this->generateInput('b','PRINTFBMETA', $data['PRINTFBMETA']).'</td>
                </tr>
                <tr>
                <td>'.$this->text('FBADMINID').':</td>
                <td><div class="help"><p>'.$this->text('h_FBADMINID').'</p></div>'.$this->generateInput('t','FBADMINID', $data['FBADMINID']).'</td>
                </tr>
                <tr>
                <td>'.$this->text('USE_DUBLINCORE').':</td>
                <td><div class="help"><p>'.$this->text('h_USE_DUBLINCORE').'</p></div>'.$this->generateInput('b','USE_DUBLINCORE', $data['USE_DUBLINCORE']).'</td>
                </tr>
                <tr>
                <td>'.$this->text('LINK_FACEBOOK').':</td>
                <td><div class="help"><p>'.$this->text('h_LINK_FACEBOOK').'</p></div>'.$this->generateInput('t','LINK_FACEBOOK', $data['LINK_FACEBOOK']).'</td>
                </tr>
                <tr>
                <td>'.$this->text('LINK_GOOGLEP').':</td>
                <td><div class="help"><p>'.$this->text('h_LINK_GOOGLEP').'</p></div>'.$this->generateInput('t','LINK_GOOGLEP', $data['LINK_GOOGLEP']).'</td>
                </tr>
                <tr>
                <td>'.$this->text('LINK_TWITTER').':</td>
                <td><div class="help"><p>'.$this->text('h_LINK_TWITTER').'</p></div>'.$this->generateInput('t','LINK_TWITTER', $data['LINK_TWITTER']).'</td>
                </tr>
                <tr>
                <td>'.$this->text('LINK_YOUTUBE').':</td>
                <td><div class="help"><p>'.$this->text('h_LINK_YOUTUBE').'</p></div>'.$this->generateInput('t','LINK_YOUTUBE', $data['LINK_YOUTUBE']).'</td>
                </tr>
                </table>
            </div>
            <div id="tabs-contact">
                <table class="infotable">
                <tr>
                <td>'.$this->text('USE_CONTACT').':</td>
                <td><div class="help"><p>'.$this->text('h_USE_CONTACT').'</p></div>'.$this->generateInput('b','USE_CONTACT', $data['USE_CONTACT']).'</td>
                </tr>
                <tr>
                <td>'.$this->text('USE_CONTACTVCARD').':</td>
                <td><div class="help"><p>'.$this->text('h_USE_CONTACTVCARD').'</p></div>'.$this->generateInput('b','USE_CONTACTVCARD', $data['USE_CONTACTVCARD']).'</td>
                </tr>
                <tr>
                <td>'.$this->text('CONTACT_NAME').':</td>
                <td><div class="help"><p>'.$this->text('h_CONTACT_NAME').'</p></div>'.$this->generateInput('t','CONTACT_NAME', $data['CONTACT_NAME']).'</td>
                </tr>
                <tr>
                <td>'.$this->text('CONTACT_STREET').':</td>
                <td><div class="help"><p>'.$this->text('h_CONTACT_STREET').'</p></div>'.$this->generateInput('t','CONTACT_STREET', $data['CONTACT_STREET']).'</td>
                </tr>
                <tr>
                <td>'.$this->text('CONTACT_PSC').':</td>
                <td><div class="help"><p>'.$this->text('h_CONTACT_PSC').'</p></div>'.$this->generateInput('t','CONTACT_PSC', $data['CONTACT_PSC']).'</td>
                </tr>
                <tr>
                <td>'.$this->text('CONTACT_CITY').':</td>
                <td><div class="help"><p>'.$this->text('h_CONTACT_CITY').'</p></div>'.$this->generateInput('t','CONTACT_CITY', $data['CONTACT_CITY']).'</td>
                </tr>
                <tr>
                <td>'.$this->text('CONTACT_STATE').':</td>
                <td><div class="help"><p>'.$this->text('h_CONTACT_STATE').'</p></div>'.$this->generateInput('t','CONTACT_STATE', $data['CONTACT_STATE']).'</td>
                </tr>
                <tr>
                <td>'.$this->text('CONTACT_TOMAIL').':</td>
                <td><div class="help"><p>'.$this->text('h_CONTACT_TOMAIL').'</p></div>'.$this->generateInput('t','CONTACT_TOMAIL', $data['CONTACT_TOMAIL']).'</td>
                </tr>
                <tr>
                <td>'.$this->text('CONTACT_WORK').':</td>
                <td><div class="help"><p>'.$this->text('h_CONTACT_WORK').'</p></div>'.$this->generateInput('t','CONTACT_WORK', $data['CONTACT_WORK']).'</td>
                </tr>
                <tr>
                <td>'.$this->text('CONTACT_CELL').':</td>
                <td><div class="help"><p>'.$this->text('h_CONTACT_CELL').'</p></div>'.$this->generateInput('t','CONTACT_CELL', $data['CONTACT_CELL']).'</td>
                </tr>
                <tr>
                <td>'.$this->text('CONTACT_LATITUDE').':</td>
                <td><div class="help"><p>'.$this->text('h_CONTACT_LATITUDE').'</p></div>'.$this->generateInput('t','CONTACT_LATITUDE', $data['CONTACT_LATITUDE']).'</td>
                </tr>
                <tr>
                <td>'.$this->text('CONTACT_LONGITUDE').':</td>
                <td><div class="help"><p>'.$this->text('h_CONTACT_LONGITUDE').'</p></div>'.$this->generateInput('t','CONTACT_LONGITUDE', $data['CONTACT_LONGITUDE']).'</td>
                </tr>
                </table>
            </div>
            </div>
            <input type="hidden" name="settingskeys" id="settingskeys" value="'.implode(',',array_keys($data)).'" />
            <button class="ui-icon-cancel button right" onclick="cancelAction();">'.$this->text('cancel').'</button>
            <button class="ui-icon-disk button right" onclick="updateItems();">'.$this->text('ok').'</button>
            <div class="clear"></div>';
        
        return $markup;
    }
    
    /*
     * Generates the specific input for specific setting
     */
    private function generateInput($type,$id,$value){
        //b - boolean, s - spinner, o - selectbox
        
        $markup = '';
        
        switch($type){
            case 'b':
                $markup .= '<div class="buttonset">
                    <input type="radio" id="'.$id.'_true" name="'.$id.'" value="true"'.(($value == 'true')?' checked="checked"':'').' /><label for="'.$id.'_true">'.$this->text('enabled').'</label>
                    <input type="radio" id="'.$id.'_false" name="'.$id.'" value="false"'.(($value == 'false')?' checked="checked"':'').' /><label for="'.$id.'_false">'.$this->text('disabled').'</label>
                </div>';
                break;
            case 's':
                $markup .= '<input class="ui-autocomplete-input ui-corner-all spinner splittedright" type="text" name="'.$id.'" id="'.$id.'" value="'.$value.'" />';
                break;
            case 't':
                $markup .= '<input class="ui-autocomplete-input ui-widget-content ui-corner-all" type="text" name="'.$id.'" id="'.$id.'" value="'.$value.'" />';
                break;
            case 'o':
                $markup .= '<select name="'.$id.'" id="'.$id.'" class="ui-autocomplete-input ui-widget-content ui-corner-all">';
                if($id == 'ADMINLANGCODE'){
                    foreach($this->languager->getAdminLangs() as $langid => $lang){
                        $markup .= '<option value="'.$lang['shortcode'].'"'.(($value == $lang['shortcode'])?' selected="selected"':'').'>'.$lang['name'].'</option>';
                    }
                }else if($id == 'SLIDESHOW_TRANSITION'){
                    foreach($this->SLIDESHOW_TRANSITION as $transition){
                        $markup .= '<option value="'.$transition.'"'.(($value == $transition)?' selected="selected"':'').'>'.$transition.'</option>';
                    }
                }else if($id == 'SLIDESHOW_EASING' || $id == 'MINISLIDER_EASING'){
                    foreach($this->EASING as $easing){
                        $markup .= '<option value="'.$easing.'"'.(($value == $easing)?' selected="selected"':'').'>'.str_replace('ease','',$easing).'</option>';
                    }
                }
                $markup .= '</select>';
                break;
            default:
                $markup .= $this->text('e0');
        }
        
        return $markup;
    }
}