<?php
/**
 * Converting parameters to array
 * @package common
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

    /**
     * Magical getter
     * @param string $name
     * @return mixed
     */
    public function __get($name) {
        $name_parts = explode("_", $name);
        $tmp = implode(" ", $name_parts);
        $method = str_replace(" ", "", ucwords($tmp));

        if (method_exists($this, "get{$method}")) {
            return $this->{"get{$method}"}();
        }

        return $this->{$name};
    }

    /**
     * Magical setter
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value) {
        $name_parts = explode("_", $name);
        $tmp = implode(" ", $name_parts);
        $method = str_replace(" ", "", ucwords($tmp));

        if (method_exists($this, "set{$method}")) {
            $this->{"set{$method}"}($value);
        } else {
            $this->{$name} = $value;
        }
    }
}
