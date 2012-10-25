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
        $plugin = $this->dic->plugin->jsPreload;

        $this->view->html = $html->getDefaults();
        $this->view->requested_page = $this->request->getRequestedResource();
        $this->view->js = $plugin->getHomeJS(get_called_class());
    }
}
