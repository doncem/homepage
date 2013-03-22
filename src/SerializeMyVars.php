<?php
/**
 * Converting parameters to array
 */
abstract class SerializeMyVars implements JsonSerializable {
    
    public function jsonSerialize() {
        $vars = array_keys(get_class_vars(get_class($this)));
        $array = array();
        
        foreach ($vars as $var) {
            $array[$var] = $this->$var;
        }
        
        return $array;
    }
}
