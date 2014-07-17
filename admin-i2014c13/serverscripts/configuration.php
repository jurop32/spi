<?php

/*
 * controls the modules
 */

class configuration{
    
    /*
     * Holds the configuration data
     */
    private $configuration = array();
           
    /*
     * Registry
     */
    private $registry = null;
    
    /*
     * constructor
     */
    public function __construct($registry){
        $this->registry = $registry;
        
        $this->loadConfiguration();
    }
    
    /*
     * Loads the settings from database
     */
    private function loadConfiguration(){
        $temp = dibi::query('SELECT * FROM ' . DB_TABLEPREFIX . 'SETTINGS');
        $this->configuration = $temp->fetchPairs('id','value');
        foreach($this->configuration as $id => $value){
            if($this->configuration[$id] === 'false'){
                $this->configuration[$id] = false;
            }else if($this->configuration[$id] === 'true'){
                $this->configuration[$id] = true;
            }
        }
    }
    
    /*
     * Returns the setting value
     */
    public function getSetting($id){
        if(isset($this->configuration[$id])){
            return $this->configuration[$id];
        }else{
            return null;
        }
    }
}