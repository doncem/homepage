<?php

namespace homepage\controller;

/**
 * Container of experiments
 * @package homepage_controllers
 */
class HomeExperiments extends \homepage\HomeController {
    
    /**
     * Left request params after /experiments/&lt;request-param&gt;/
     * @var array
     */
    private $params = array();
    
    /**
     * Available experiments
     * @var array
     */
    static private $availableExperiments = array(
        "jquery-window-grid"    => "jQuery Window Grid",
        "jukebox"               => "Jukebox"
    );
    
    /**
     * Find out which experiment of available ones is enabled.<br />
     * Run additional code if method is available
     * @Request("experiments")
     * @Template("homepage/experiments")
     */
    public function experiments() {
        $this->params = $this->request->getMappedParameters();
        
        if (!empty($this->params[0])) {
            if (array_key_exists($this->params[0], self::$availableExperiments)) {
                $this->view->enabledExperiment = array(
                    "link"  => $this->params[0],
                    "title" => self::$availableExperiments[$this->params[0]]
                );
                
                if (method_exists(get_class(), $this->params[0])) {
                    $this->{array_shift($this->params)}();
                }
            } else {
                $this->redirect("/experiments/");
            }
        }
        
        $this->view->availableExperiments = self::$availableExperiments;
    }
}
