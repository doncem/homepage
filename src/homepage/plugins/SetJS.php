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
            "bootstrap_min" => "/js/bootstrap.min",
            "general"       => "/js/general",
            "flot_min"      => "/js/jquery.flot.min",
            "home_movies"   => "/js/homepage/movies"
        );
        
        return $this;
    }
    
    /**
     * Return specific js for the certain home pages
     * @param string $classname Class name with leading namespaces
     * @return null|array
     */
    public function getHomeJS($classname) {
        $namespace = explode("\\", $classname);
        
        if ($namespace[1] != "controller") {
            return null;
        } else if ($namespace[0] != "homepage") {
            return null;
        } else {
            switch ($namespace[2]) {
                case "HomeMovies":
                    return array(
                        $this->available_js["general"],
                        $this->available_js["flot_min"],
                        $this->available_js["home_movies"]);
                    break;
                case "HomeIndex":
                default:
                    return array($this->available_js["general"]);
                    break;
            }
        }
    }
}
