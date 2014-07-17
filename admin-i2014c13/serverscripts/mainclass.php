<?php

/*
 * the main class contains all required functions
 */

abstract class mainclass{   
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
     * The current children class name
     */
    protected $classname = null;
    
    /*
     * holds the actice class name
     */
    
    public function __construct($registry){
        require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'configuration.inc.php';
        $this->registry = $registry;
        $this->languager = $registry['languager'];
        $this->configuration = $registry['configuration'];
        $this->classname = get_class($this);
    }
    
    /*
     * returns the text from the languager
     */
    protected function text($text){
        return $this->languager->getText($this->classname,$text);
    }
}