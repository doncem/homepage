<?php

use \xframe\request\Controller;

/**
 * Initializing controller
 */
class ControllerInit extends Controller {

    public function init() {
        $observers = $this->dic->exceptionHandler->getObservers();
        foreach ($observers as $observer) {
            if ($observer instanceof \xframe\exception\ExceptionOutputter) {
                $this->dic->exceptionHandler->detach($observer);
            }
        }
        
        $this->dic->exceptionHandler->attach(new ExHandling());
        $this->view->isLive = CONFIG == "live" ? 1 : 0;
    }
}
