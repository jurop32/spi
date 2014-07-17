<?php

/*
 * controls the modules
 */

class modules extends mainclass{
    
    /*
     * modules dirname
     */
    private $modulesdirname = 'modules';
    
    /*
     * modules configfilename
     */
    private $modulesconfigfilename = 'config.xml';
    
    /*
     * Holds the first parentmodule
     */
    private $firstparentmodule = 'site';
    
    /*
     * Holds the last parentmodule
     */
    private $lastparentmodule = 'information';
    
    /*
     * hold sthe modules info in hierarchy
     */
    protected $modules = null;
    
    /*
     * hold sthe modules info in list
     */
    protected $moduleslist = null;
    
    /*
     * constructor
     */
    public function __construct($registry){
        parent::__construct($registry);
    }
    
    /*
     * Returns the modules list in menu structure
     */
    public function getModules(){
        if($this->modules == null){
            $this->loadModules();
        }
        return $this->modules;
    }
    
    /*
     * Returns the modules list
     */
    public function getModulesList(){
        if($this->moduleslist == null){
            $this->loadModulesList();
        }
        return $this->moduleslist;
    }

    /*
     * return the module name array
     */
    public function getModuleIds(){
        if($this->moduleslist == null){
            $this->loadModulesList();
        }
        return array_keys($this->moduleslist);
    }
    
    /*
     * loads the modules list
     */
    private function loadModulesList(){
        if($this->modules == null) $this->loadModules();

        $this->moduleslist = array();

        foreach($this->modules as $moduleid => $module){
            if(isset($module['id'])){
                $this->moduleslist[$moduleid] = $module;
            }else{
                foreach($module as $submoduleid => $submodule){
                    $this->moduleslist[$submoduleid] = $submodule;
                }
            }
        }
    }
    
    /*
     * loads the modules
     */
    private function loadModules(){
        $modulesdir = ADMIN_ABSDIRPATH.$this->modulesdirname;
        $modules = scandir($modulesdir);
        foreach($modules as $module){
            if(substr($module,0,1) != '.'){
                
                $moduleconfig = simplexml_load_file($modulesdir.DIRECTORY_SEPARATOR.$module.DIRECTORY_SEPARATOR.$this->modulesconfigfilename);
                $attrs = $moduleconfig->attributes();
                $config = array(
                    'id' => (String)$attrs['id'],
                    'version' => (String)$attrs['version']
                );
                
                foreach($moduleconfig->children() as $property){
                    $propertyname = $property->getName();
                    if($propertyname == 'privileges'){
                        $config['privileges'] = array();
                        foreach($property->children() as $privilege){
                            $privattrs = $privilege->attributes();
                            $config['privileges'][(String)$privattrs['id']] = (String)$privilege;
                        }
                    }else if($propertyname == 'dependency'){
                        $config[$propertyname] = explode(',', (String)$property);
                    }else{
                        $config[$propertyname] = (String)$property;
                    }
                }
                
                if(isset($config['menugroup'])){
                    if(!isset($this->modules[$config['menugroup']])){
                        $this->modules[$config['menugroup']] = array();
                    }
                    $this->modules[$config['menugroup']][$config['id']] = $config;
                }else{
                    $this->modules[$config['id']] = $config;
                }
            }
        }
        
        //sorting modules alphabetically
        ksort($this->modules);
        
        //setting the page parentmodule as first
        if(isset($this->modules[$this->firstparentmodule])){
            $firstparentmodule = array(
                $this->firstparentmodule => $this->modules[$this->firstparentmodule]
            );
            unset($this->modules[$this->firstparentmodule]);
        }
        
        //setting the help parentmodule as first
        if(isset($this->modules[$this->lastparentmodule])){
            $lastparentmodule = array(
                $this->lastparentmodule => $this->modules[$this->lastparentmodule]
            );
            unset($this->modules[$this->lastparentmodule]);
        }
        
        $this->modules = array_merge($firstparentmodule,$this->modules,$lastparentmodule);
    }
    
    /*
     * Checks if the modules dependenci is ok
     */
    public function checkDependency($module){
        if($this->moduleslist == null) $this->loadModulesList();
        
        $result = '';
        
        if(isset($this->moduleslist[$module]['dependency']) && count($this->moduleslist[$module]['dependency']) > 0){
            foreach($this->moduleslist[$module]['dependency'] as $dependantmodulename){
                if($dependantmodulename != '' && !isset($this->moduleslist[$dependantmodulename])) $result .= $dependantmodulename.',';
            }
            if(strlen($result) > 0){
                $result = substr($result,0,-1);
            }else{
                $result = true;
            }
        }else{
            $result = true;
        }
        
        return $result;
    }
}