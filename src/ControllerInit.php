<?php
/**
 * Initializing controller
 */
class ControllerInit extends xframe\request\Controller {

    /**
     * <ul>
     * <li>Is it live? Question answered buy checking whether CONFIG is 'live' or request has 'DEPLOY' parameter</li>
     * <li>Assigning 'lastDeploy' for live versions</li>
     * </ul>
     */
    public function init() {
        $this->view->isLive = (CONFIG == "live" || isset($this->request->DEPLOY)) ? 1 : 0;
        $this->view->lastDeploy = date("Y-m-d H") . "h";
    }
}
