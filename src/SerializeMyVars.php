<?php
/**
 * Converting parameters to array
 */
abstract class SerializeMyVars implements JsonSerializable {

    /**
     * (PHP 5 &gt;= 5.4.0)<br/>
     * Map class variables into an array
     * @return array
     */
    public function jsonSerialize() {
        $vars = array_keys(get_class_vars(get_class($this)));
        $array = array();

        foreach ($vars as $var) {
            $array[$var] = $this->$var;
        }

        return $array;
    }
}
