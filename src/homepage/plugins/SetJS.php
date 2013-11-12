<?php

namespace homepage\plugins;
use xframe\plugin\Plugin;

/**
 * Deals with available JS files to load to page.<br />
 * So far only in homepage module
 * @package homepage_plugins
 */
class SetJS extends Plugin {

    /**
     * jQuery UI version in use
     */
    const JQUERY_UI_VERSION = "1.10.3";

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
        $this->available_js = array(
            "backbone"              => "/js/backbone.js",
            "backbone_min"          => "/js/backbone.min.js",
            "bootstrap_min"         => "/js/bootstrap.min.js",
            "general"               => "/js/general.js",
            "general_min"           => "/js/general.min.js",
            "home_experiments"      => "/js/homepage/experiments.js",
            "home_experiments_min"  => "/js/homepage/experiments.min.js",
            "home_movies"           => "/js/homepage/movies.js",
            "home_movies_min"       => "/js/homepage/movies.min.js",
            "jquery_color"          => "/js/jquery.color.js",
            "jquery_color_min"      => "/js/jquery.color.min.js",
            "jquery_flot"           => "/js/jquery.flot.js",
            "jquery_flot_min"       => "/js/jquery.flot.min.js",
            "jukebox_me"            => "/js/jukebox/me.js",
            "jukebox_me_min"        => "/js/jukebox/me.min.js",
            "modernizr"             => "/js/modernizr-2.6.2.js",
            "modernizr_min"         => "/js/modernizr-2.6.2-min.js",
            "underscore"            => "/js/underscore.js",
            "underscore_min"        => "/js/underscore.min.js"
        );

        return $this;
    }

    /**
     * Return specific js for the certain home pages
     * @param string $module Which module is running
     * @param string $controller Which controller is accessed
     * @param boolean $isLive Allow includes of minified js only if it's for live deployment
     * @return null|array
     */
    public function getHomeJS($module, $controller, $isLive) {
        if ($module == "jukebox") {
            $array = array(file_exists($this->dic->root . "www" . $this->available_js["underscore_min"]) && $isLive ? $this->available_js["underscore_min"] : $this->available_js["underscore"]);
            $array[] = file_exists($this->dic->root . "www" . $this->available_js["backbone_min"]) && $isLive ? $this->available_js["backbone_min"] : $this->available_js["backbone"];
            $array[] = file_exists($this->dic->root . "www" . $this->available_js["jukebox_me_min"]) && $isLive ? $this->available_js["jukebox_me_min"] : $this->available_js["jukebox_me"];
            $array[] = \HtmlInit::doWeHaveGoogle() ? $this->dic->root . "www/js/jquery-ui." . self::JQUERY_UI_VERSION . ".min.js" : "//ajax.googleapis.com/ajax/libs/jqueryui/" . self::JQUERY_UI_VERSION . "/jquery-ui.min.js";
            $array[] = file_exists($this->dic->root . "www" . $this->available_js["jquery_color_min"]) && $isLive ? $this->available_js["jquery_color_min"] : $this->available_js["jquery_color"];

            return $array;
        } else if ($module == "homepage") {
            $array = array(file_exists($this->dic->root . "www" . $this->available_js["general_min"]) && $isLive ? $this->available_js["general_min"] : $this->available_js["general"]);
            $array[] = file_exists($this->dic->root . "www" . $this->available_js["modernizr_min"]) && $isLive ? $this->available_js["modernizr_min"] : $this->available_js["modernizr"];

            switch ($controller) {
                case "HomeMovies":
                    $array[] = file_exists($this->dic->root . "www" . $this->available_js["underscore_min"]) && $isLive ? $this->available_js["underscore_min"] : $this->available_js["underscore"];
                    $array[] = file_exists($this->dic->root . "www" . $this->available_js["backbone_min"]) && $isLive ? $this->available_js["backbone_min"] : $this->available_js["backbone"];
                    $array[] = file_exists($this->dic->root . "www" . $this->available_js["jquery_flot_min"]) && $isLive ? $this->available_js["jquery_flot_min"] : $this->available_js["jquery_flot"];
                    $array[] = file_exists($this->dic->root . "www" . $this->available_js["home_movies_min"]) && $isLive ? $this->available_js["home_movies_min"] : $this->available_js["home_movies"];
                    break;
                case "HomeExperiments":
                    $array[] = file_exists($this->dic->root . "www" . $this->available_js["home_experiments_min"]) && $isLive ? $this->available_js["home_experiments_min"] : $this->available_js["home_experiments"];
                    $array[] = file_exists($this->dic->root . "www" . $this->available_js["jquery_color_min"]) && $isLive ? $this->available_js["jquery_color_min"] : $this->available_js["jquery_color"];
                    break;
                case "HomeIndex":
                default:
                    break;
            }

            return $array;
        } else {
            return null;
        }
    }
}
