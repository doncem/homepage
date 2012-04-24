<?php

namespace homepage;
use \xframe\request\Controller;

/**
 * Initializing controller
 */
class HomeController extends Controller {

    /**
     * <ul><li>Setting default html values</li><li>Show google scripts?</li></ul>
     */
    public function init() {
        $html = new \HtmlInit($this->dic->registry);
        $this->view->html = $html->getDefaults();
        $this->view->showGA = CONFIG == "live" ? 1 : 0;
    }
}
