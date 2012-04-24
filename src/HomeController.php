<?php

use \xframe\request\Controller;

/**
 * Initializing controller
 */
class HomeController extends Controller {

    /**
     * <ul><li>Setting default html values</li><li>Show google scripts?</li></ul>
     */
    public function init() {
        $this->view->showGA = CONFIG == "live" ? 1 : 0;
    }
}
