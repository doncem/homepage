<?php

namespace homepage;

/**
 * Initializing controller
 */
class HomeController extends \ControllerInit {

    /**
     * <ul><li>Setting default html values</li><li>Show google scripts?</li></ul>
     */
    public function init() {
        parent::init();

        $html = new \HtmlInit($this->dic->registry);
        $this->view->html = $html->getDefaults();
    }
}
