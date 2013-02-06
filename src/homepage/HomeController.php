<?php

namespace homepage;

/**
 * Initializing controller
 */
class HomeController extends \ControllerInit {
    
    /**
     * Instance of MemcacheSingleton object
     * @var \MemcacheSingleton
     */
    public $memcache_instance;
    
    /**
     * <ul>
     * <li>Setting default html values</li>
     * <li>Assigning requested resource to view template</li>
     * <li>Getting additional javascripts using plugin
     * <li>Initiating MemcacheSingleton</li>
     * </ul>
     * @see \HtmlInit
     * @see homepage\plugins\SetJS
     * @see \MemcacheSingleton
     */
    public function init() {
        parent::init();

        $html = new \HtmlInit($this->dic->registry);
        $plugin = $this->dic->plugin->jsPreload;

        $this->view->html = $html->getDefaults();
        $this->view->requested_page = $this->request->getRequestedResource();
        $this->view->js = $plugin->getHomeJS(get_called_class(), $this->view->isLive);
        $this->memcache_instance = \MemcacheSingleton::instance($this->dic);
    }
}
