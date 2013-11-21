<?php
/**
 * Initializing controller
 * @package common
 */
class ControllerInit extends xframe\request\Controller {

    /**
     * Which module is executed
     * @var string|null
     */
    protected $module;

    /**
     * Which controller is accessed
     * @var string|null
     */
    protected $controller;

    /**
     * Additional page title appendix
     * @var string
     */
    protected $page_title;

    /**
     * <ul>
     * <li>Is it live? Question answered buy checking whether CONFIG is 'live' or request has 'DEPLOY' parameter</li>
     * <li>Assigning 'lastDeploy' for live versions</li>
     * </ul>
     */
    protected function init() {
        $namespace = explode("\\", get_called_class());

        if ((isset($namespace[1]) && $namespace[1] != "controller") || count($namespace) == 1) {
            $this->module = null;
            $this->controller = count($namespace) == 1 ? $namespace : null;
        } else {
            $this->module = $namespace[0];
            $this->controller = $namespace[2];
        }
    }

    protected function postDispatch() {
        $this->view->isLive = (CONFIG == "live" || isset($this->request->DEPLOY)) ? 1 : 0;
        $this->view->lastDeploy = isset($this->request->DEPLOY) ? date("Y-m-d H") . "h" : "";

        $arr = explode("/", $this->request->getRequestedResource());
        $this->view->title = (strlen($this->page_title) > 0 ? $this->page_title . " - " : "")
            . ucwords(implode(" - ", array_reverse($arr)));

        $html = new \HtmlInit($this->dic->registry);
        $plugin = $this->dic->plugin->staticPreload;

        $this->view->html = $html->getDefaults();
        $this->view->css = $plugin->getCSS($this->module, $this->controller, $this->view->isLive);
        $this->view->js = $plugin->getJS($this->module, $this->controller, $this->view->isLive);
        $this->view->requestedPage = $this->request->getRequestedResource();
    }
}
