<?php

namespace homepage\controller;

/**
 * Container of experiments
 * @package homepage_controllers
 */
class Experiments extends \ControllerInit {

    /**
     * Available experiments
     * @var array
     */
    private static $availableExperiments = array(
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
        $params = $this->request->getMappedParameters();

        if (!empty($params[0]) && array_key_exists($params[0], self::$availableExperiments)) {
            $this->view->enabledExperiment = $params[0];
            $this->page_title = self::$availableExperiments[$params[0]];

            if (method_exists(get_class(), $params[0])) {
                $this->{array_shift($params)}();
            }
        } else if (!empty($params[0])) {
            $this->redirect("/experiments");
        }

        $this->view->availableExperiments = self::$availableExperiments;
    }
}
