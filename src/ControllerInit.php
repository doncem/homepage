<?php
/**
 * Initializing controller
 */
class ControllerInit extends xframe\request\Controller {

    public function init() {
        $observers = $this->dic->exceptionHandler->getObservers();
        foreach ($observers as $observer) {
            if ($observer instanceof \xframe\exception\ExceptionOutputter) {
                $this->dic->exceptionHandler->detach($observer);
            }
        }
        
        $this->dic->exceptionHandler->attach(new errors\observers\ExHandling());
        $this->view->isLive = (CONFIG == "live" || isset($this->request->DEPLOY)) ? 1 : 0;
        $this->view->lastDeploy = date("Y-m-d H") . "h";
    }
}
