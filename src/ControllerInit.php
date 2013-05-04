<?php
/**
 * Initializing controller
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
     * <ul>
     * <li>Is it live? Question answered buy checking whether CONFIG is 'live' or request has 'DEPLOY' parameter</li>
     * <li>Assigning 'lastDeploy' for live versions</li>
     * </ul>
     */
    public function init() {
        $this->view->isLive = (CONFIG == "live" || isset($this->request->DEPLOY)) ? 1 : 0;
        $this->view->lastDeploy = date("Y-m-d H") . "h";

        $namespace = explode("\\", get_called_class());

        if ((isset($namespace[1]) && $namespace[1] != "controller") || count($namespace) == 1) {
            $this->module = null;
            $this->controller = count($namespace) == 1 ? $namespace : null;
        } else {
            $this->module = $namespace[0];
            $this->controller = $namespace[2];
        }
    }
}
