<?php

namespace homepage;

/**
 * Initializing controller
 * @package homepage
 */
class HomeController extends \ControllerInit {
    
    /**
     * Instance of MemcacheHandler object
     * @var \MemcacheHandler
     */
    public $cacheHandler;

    /**
     * <ul>
     * <li>Setting default html values</li>
     * <li>Assigning requested resource to view template</li>
     * <li>Getting additional javascripts using plugin
     * <li>Initiating MemcacheHandler</li>
     * </ul>
     * @see \HtmlInit
     * @see homepage\plugins\SetJS
     * @see \MemcacheHandler
     */
    public function init() {
        parent::init();

        $html = new \HtmlInit($this->dic->registry);
        $plugin = $this->dic->plugin->jsPreload;

        $this->view->html = $html->getDefaults();
        $this->view->requested_page = $this->request->getRequestedResource();
        $this->view->js = $plugin->getHomeJS($this->module, $this->controller, $this->view->isLive);
        $this->cacheHandler = \MemcacheHandler::getHandler($this->dic);
        $this->cacheHandler->set_prefix($this->module);
    }
}
