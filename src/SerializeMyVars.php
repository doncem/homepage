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
        $vars = array_keys(get_object_vars($this));
        $array = array();

        foreach ($vars as $var) {
            // we can't serialize closures. also disabling system vars
            if (!($this->$var instanceof Closure) && substr($var, 0, 1) != "_") {
                if ($var == "id") {
                    // override ID variable
                    $array["_" . $var] = $this->$var;
                } else {
                    $array[$var] = $this->$var;
                }
            }
        }

        return $array;
    }
}
