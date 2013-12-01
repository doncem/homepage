<?php

use xframe\plugin\Plugin;

/**
 * Deals with available JS files to load to page.<br />
 * So far only in homepage module
 * @package common
 */
class SetStatics extends Plugin {

    /**
     * jQuery UI version in use
     */
    const JQUERY_UI_VERSION = "1.10.3";

    /**
     * Identifier for main layout CSS
     * @var string
     */

    private static $css_main = "main";

    /**
     * Identifier for backbone JS library
     * @var string
     */
    private static $js_backbone = "backbone";

    /**
     * Identifier for homepage experiments JS
     * @var string
     */
    private static $js_experiments = "home_experiments";

    /**
     * Identifier for overall general JS
     * @var string
     */
    private static $js_general = "general";

    /**
     * Identifier for jquery color JS library
     * @var string
     */
    private static $js_jquery_colour = "jquery_color";

    /**
     * Identifier for jquery flot JS library
     * @var string
     */
    private static $js_jquery_flot = "jquery_flot";

    /**
     * Identifier for jukebox experiment JS
     * @var string
     */
    private static $js_jukebox = "jukebox_me";

    /**
     * Identifier for modernizr JS library
     * @var string
     */
    private static $js_modernizr = "modernizr";

    /**
     * Identifier for homepage movies JS
     * @var string
     */
    private static $js_movies = "home_movies";

    /**
     * Identifier for raphael JS library
     * @var string
     */
    private static $js_raphael = "raphael";

    /**
     * Identifier for underscore JS library
     * @var string
     */
    private static $js_underscore = "underscore";

    /**
     * Mapped available CSS files
     * @var array
     */
    private $available_css;

    /**
     * Mapped available JS files
     * @var array
     */
    private $available_js;

    /**
     * Set available JS files
     * @return SetJS
     */
    public function init() {
        $this->available_css = array(
            self::$css_main => "/css/main.css"
        );
        $this->available_js = array(
            self::$js_backbone                  => "/js/backbone.js",
            self::$js_backbone . "_min"         => "/js/backbone.min.js",
            self::$js_experiments               => "/js/homepage/experiments.js",
            self::$js_experiments . "_min"      => "/js/homepage/experiments.min.js",
            self::$js_general                   => "/js/general.js",
            self::$js_general . "_min"          => "/js/general.min.js",
            self::$js_jquery_colour             => "/js/jquery.color.js",
            self::$js_jquery_colour . "_min"    => "/js/jquery.color.min.js",
            self::$js_jquery_flot               => "/js/jquery.flot.js",
            self::$js_jquery_flot . "_min"      => "/js/jquery.flot.min.js",
            self::$js_jukebox                   => "/js/jukebox/me.js",
            self::$js_jukebox . "_min"          => "/js/jukebox/me.min.js",
            self::$js_modernizr                 => "/js/modernizr-2.6.2.js",
            self::$js_modernizr . "_min"        => "/js/modernizr-2.6.2-min.js",
            self::$js_movies                    => "/js/homepage/movies.js",
            self::$js_movies . "_min"           => "/js/homepage/movies.min.js",
            self::$js_raphael                   => "/js/raphael.js",
            self::$js_raphael . "_min"          => "/js/raphael.min.js",
            self::$js_underscore                => "/js/underscore.js",
            self::$js_underscore . "_min"       => "/js/underscore.min.js"
        );

        return $this;
    }

    /**
     * Return specific css for the certain pages
     * @param string $module Which module is running
     * @param string $controller Which controller is accessed
     * @param boolean $isLive Allow includes of minified js only if it's for live deployment
     * @return null|array
     */
    public function getCSS($module, $controller, $isLive) {
        return array($this->getCSSfilename(self::$css_main));
    }

    /**
     * Return specific js for the certain pages
     * @param string $module Which module is running
     * @param string $controller Which controller is accessed
     * @param boolean $isLive Allow includes of minified js only if it's for live deployment
     * @return null|array
     */
    public function getJS($module, $controller, $isLive) {
        if ($module == "jukebox") {
            $array = array($this->getJSfilename(self::$js_underscore, $isLive));
            $array[] = $this->getJSfilename(self::$js_backbone, $isLive);
            $array[] = $this->getJSfilename(self::$js_jukebox, $isLive);
            $array[] = \HtmlInit::doWeHaveGoogle() ? $this->dic->root . "www/js/jquery-ui." . self::JQUERY_UI_VERSION . ".min.js" : "//ajax.googleapis.com/ajax/libs/jqueryui/" . self::JQUERY_UI_VERSION . "/jquery-ui.min.js";
            $array[] = $this->getJSfilename(self::$js_jquery_colour, $isLive);

            return $array;
        } else if ($module == "homepage") {
            $array = array($this->getJSfilename(self::$js_general, $isLive));
//            $array[] = $this->getJSfilename(self::$js_modernizr, $isLive);

            switch ($controller) {
                case "Movies":
                    $array[] = $this->getJSfilename(self::$js_underscore, $isLive);
                    $array[] = $this->getJSfilename(self::$js_backbone, $isLive);
                    $array[] = $this->getJSfilename(self::$js_jquery_flot, $isLive);
                    $array[] = $this->getJSfilename(self::$js_raphael, $isLive);
                    $array[] = $this->getJSfilename(self::$js_movies, $isLive);
                    break;
                case "Experiments":
                    $array[] = $this->getJSfilename(self::$js_experiments, $isLive);
                    $array[] = $this->getJSfilename(self::$js_jquery_colour, $isLive);
                    break;
                default:
                    break;
            }

            return $array;
        } else {
            return null;
        }
    }

    /**
     * Get particular CSS filename
     * @param string $identifier
     * @return string
     * @throws Exception
     */
    private function getCSSfilename($identifier) {
        if (file_exists($this->dic->root . "www" . $this->available_css[$identifier])) {
            return $this->available_css[$identifier];
        } else {
            throw new Exception("Could not find '{$identifier}' styles. Aren't you forgetting something? :p'", 0, null);
        }
    }

    /**
     * Get particular JS filename
     * @param string $identifier
     * @param boolean $isLive
     * @return string
     */
    private function getJSfilename($identifier, $isLive) {
        if (file_exists($this->dic->root . "www" . $this->available_js[$identifier . "_min"]) && $isLive) {
            return $this->available_js[$identifier . "_min"];
        } else {
            return $this->available_js[$identifier];
        }
    }
}
