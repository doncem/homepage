<?php

namespace homepage\plugins;
use xframe\plugin\Plugin;

/**
 * Deals with available JS files to load to page.<br />
 * So far only in homepage module
 */
class SetJS extends Plugin {
    
    /**
     * Mapped available JSs
     * @var array
     */
    private $available_js;
    
    public function init() {
        $this->available_js = array(
            "backbone"              => "/js/backbone",
            "backbone_min"          => "/js/backbone.min",
            "bootstrap_min"         => "/js/bootstrap.min",
            "general"               => "/js/general",
            "general_min"           => "/js/general.min",
            "home_experiments"      => "/js/homepage/experiments",
            "home_experiments_min"  => "/js/homepage/experiments.min",
            "home_movies"           => "/js/homepage/movies",
            "home_movies_min"       => "/js/homepage/movies.min",
            "jquery_color"          => "/js/jquery.color",
            "jquery_color_min"      => "/js/jquery.color.min",
            "jquery_flot"           => "/js/jquery.flot",
            "jquery_flot_min"       => "/js/jquery.flot.min",
            "jukebox_me"            => "/js/jukebox/me",
            "jukebox_me_min"        => "/js/jukebox/me.min",
            "modernizr"             => "/js/modernizr-2.6.2",
            "modernizr_min"         => "/js/modernizr-2.6.2-min",
            "underscore"            => "/js/underscore",
            "underscore_min"        => "/js/underscore.min"
        );
        
        return $this;
    }
    
    /**
     * Return specific js for the certain home pages
     * @param string $classname Class name with leading namespaces
     * @param boolean $isLive Allow includes of minified js only if it's for live deployment
     * @return null|array
     */
    public function getHomeJS($classname, $isLive) {
        $namespace = explode("\\", $classname);
        
        if ($namespace[1] != "controller") {
            return null;
        } else if ($namespace[0] == "jukebox") {
            $array = array(file_exists($this->dic->root . "www" . $this->available_js["underscore_min"] . ".js") && $isLive ? $this->available_js["underscore_min"] : $this->available_js["underscore"]);
            $array[] = file_exists($this->dic->root . "www" . $this->available_js["backbone_min"] . ".js") && $isLive ? $this->available_js["backbone_min"] : $this->available_js["backbone"];
            $array[] = file_exists($this->dic->root . "www" . $this->available_js["jukebox_me_min"] . ".js") && $isLive ? $this->available_js["jukebox_me_min"] : $this->available_js["jukebox_me"];
            
            return $array;
        } else if ($namespace[0] == "homepage") {
            $array = array(file_exists($this->dic->root . "www" . $this->available_js["general_min"] . ".js") && $isLive ? $this->available_js["general_min"] : $this->available_js["general"]);
            $array[] = file_exists($this->dic->root . "www" . $this->available_js["modernizr_min"] . ".js") && $isLive ? $this->available_js["modernizr_min"] : $this->available_js["modernizr"];
            
            switch ($namespace[2]) {
                case "HomeMovies":
                    $array[] = file_exists($this->dic->root . "www" . $this->available_js["underscore_min"] . ".js") && $isLive ? $this->available_js["underscore_min"] : $this->available_js["underscore"];
                    $array[] = file_exists($this->dic->root . "www" . $this->available_js["backbone_min"] . ".js") && $isLive ? $this->available_js["backbone_min"] : $this->available_js["backbone"];
                    $array[] = file_exists($this->dic->root . "www" . $this->available_js["jquery_flot_min"] . ".js") && $isLive ? $this->available_js["jquery_flot_min"] : $this->available_js["jquery_flot"];
                    $array[] = file_exists($this->dic->root . "www" . $this->available_js["home_movies_min"] . ".js") && $isLive ? $this->available_js["home_movies_min"] : $this->available_js["home_movies"];
                    break;
                case "HomeExperiments":
                    $array[] = file_exists($this->dic->root . "www" . $this->available_js["home_experiments_min"] . ".js") && $isLive ? $this->available_js["home_experiments_min"] : $this->available_js["home_experiments"];
                    $array[] = file_exists($this->dic->root . "www" . $this->available_js["jquery_color_min"] . ".js") && $isLive ? $this->available_js["jquery_color_min"] : $this->available_js["jquery_color"];
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
