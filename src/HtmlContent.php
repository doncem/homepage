<?php

use xframe\plugin\Plugin;

/**
 * Iniate default html elements
 * @package common
 */
class HtmlContent extends Plugin {

    /**
     * jQuery version in use
     */
    const JQUERY_VERSION = "2.0.3";

    /**
     * Hostname to check internet is alive
     */
    public static $JQUERY_HOSTNAME = "www.google.com";

    public function init() {
        return $this;
    }

    /**
     * Get default html elements
     * @param string $package
     * @param string $module [optional] Which module descriptions to use. Default null - load them all
     * @return \stdClass
     */
    public function getPackage($package, $module = null) {
        if (self::doWeHaveGoogle()) {
            $js = "/js/jquery-" . self::JQUERY_VERSION . ".min.js";
        } else {
            $js = "//ajax.googleapis.com/ajax/libs/jquery/" . self::JQUERY_VERSION . "/jquery.min.js";
        }

        $html = json_decode(file_get_contents($this->dic->root . "config" . DIRECTORY_SEPARATOR . $package . ".json"));
        $descriptions = array();

        foreach ($html->editor->descriptions as $list) {
            $list = get_object_vars($list);

            foreach ($list as $key => $description) {
                $arr = explode("-", $key);
                $group = array_shift($arr);

                if ($group != $module && strlen($module) > 0) {
                    continue;
                }

                if (!array_key_exists($group, $descriptions)) {
                    $descriptions[$group] = array();
                }

                $descriptions[$group][implode("-", $arr)] = $description;
            }
        }

        $html->editor->descriptions = $descriptions;
        $html->jquery = $js;

        return $html;
    }

    /**
     * Set it
     * @param string $package
     * @param string $data
     */
    public function setPackage($package, $data) {
        $html = json_decode(file_get_contents($this->dic->root . "config" . DIRECTORY_SEPARATOR . $package . ".json"));
    }

    /**
     * Checks whether apis for jQuery is available or not
     * @return boolean
     */
    public static function doWeHaveGoogle() {
        return (!$sock = @fsockopen(self::$JQUERY_HOSTNAME, 80, $num, $error, 5));
    }
}
