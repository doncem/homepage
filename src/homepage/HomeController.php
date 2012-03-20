<?php

namespace homepage;
use \xframe\request\Controller;
use \HtmlInit;

class HomeController extends Controller {

    public function init() {
        $html = new \HtmlInit($this->dic->registry);
        $this->view->html = $html->getDefaults();
        $this->view->showGA = CONFIG == "live" ? 1 : 0;
    }
}
