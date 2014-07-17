<?php

/*
 * The object_registry class
 */

class registry implements ArrayAccess {

    /*
     * Data array
     */
    private $objects = null;

    public function __construct(){
        $this->objects = array();
    }

    public function offsetExists($offset) {
        return isset($this->objects[$offset]);
    }

    public function offsetGet($offset) {
        if(!isset($this->objects[$offset])){
            $this->offsetSet($offset, new $offset($this));
        }
        return $this->objects[$offset];
    }

    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->objects[] = $value;
        } else {
            $this->objects[$offset] = $value;
        }
    }

    public function offsetUnset($offset) {
        unset($this->objects[$offset]);
    }
}