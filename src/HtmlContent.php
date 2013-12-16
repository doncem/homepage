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
    public function getPackage() {
        if (self::doWeHaveGoogle()) {
            $js = "/js/jquery-" . self::JQUERY_VERSION . ".min.js";
        } else {
            $js = "//ajax.googleapis.com/ajax/libs/jquery/" . self::JQUERY_VERSION . "/jquery.min.js";
        }

        $html = json_decode(file_get_contents($this->dic->root . "config" . DIRECTORY_SEPARATOR . $this->dic->registry->get("HTML_PACKAGE") . ".json"), true);
        $html["jquery"] = $js;

        return $html;
    }

    /**
     * Set it
     * @param string $data
     */
    public function setPackage($data) {
        file_put_contents($this->dic->root . "config" . DIRECTORY_SEPARATOR . $this->dic->registry->get("HTML_PACKAGE") . ".json", json_encode($data));
    }

    /**
     * Checks whether apis for jQuery is available or not
     * @return boolean
     */
    public static function doWeHaveGoogle() {
        return (!$sock = @fsockopen(self::$JQUERY_HOSTNAME, 80, $num, $error, 5));
    }
}
