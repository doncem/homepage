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
            "bootstrap_min"         => "/js/bootstrap.min",
            "general"               => "/js/general",
            "general_min"           => "/js/general.min",
            "flot_min"              => "/js/jquery.flot.min",
            "home_movies"           => "/js/homepage/movies",
            "home_movies_min"       => "/js/homepage/movies.min",
            "home_experiments"      => "/js/homepage/experiments",
            "home_experiments_min"  => "/js/homepage/experiments.min"
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
        } else if ($namespace[0] != "homepage") {
            return null;
        } else {
            $array = array(file_exists($this->dic->root . "www" . $this->available_js["general_min"] . ".js") && $isLive ? $this->available_js["general_min"] : $this->available_js["general"]);
            switch ($namespace[2]) {
                case "HomeMovies":
                    $array[] = $this->available_js["flot_min"];
                    $array[] = file_exists($this->dic->root . "www" . $this->available_js["home_movies_min"] . ".js") && $isLive ? $this->available_js["home_movies_min"] : $this->available_js["home_movies"];
                    break;
                case "HomeExperiments":
                    $array[] = file_exists($this->dic->root . "www" . $this->available_js["home_experiments_min"] . ".js") && $isLive ? $this->available_js["home_experiments_min"] : $this->available_js["home_experiments"];
                    break;
                case "HomeIndex":
                default:
                    break;
            }
            return $array;
        }
    }
}
