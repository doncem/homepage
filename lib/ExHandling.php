<?php

use \SplObserver;
use \SplSubject;

/**
 * Uses the observer pattern to listen for exceptions.
 *
 * This code was largely inspired by the devzone article:
 *
 * http://devzone.zend.com/article/12229
 */
class ExHandling implements SplObserver {

    /**
     * Log the exception.
     *
     * @param SplSubject $subject
     */
    public function update(SplSubject $subject) {
        $root = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR;
        $registry = new \xframe\registry\Registry(array(
            'root' => $root,
            'tmp' => $root . "tmp" . DIRECTORY_SEPARATOR,
            'configFilename' => "config" . DIRECTORY_SEPARATOR . CONFIG . ".ini",
        ));
        $registry->load($root . "config" . DIRECTORY_SEPARATOR . CONFIG . ".ini", $root);
        $view = new \xframe\view\TwigView($registry, $root, $root . "tmp" . DIRECTORY_SEPARATOR, $root . "view" . DIRECTORY_SEPARATOR);
        $html = new \HtmlInit($registry);

        $view->html = $html->getDefaults(null, "Error");
        $view->showGA = CONFIG == "live" ? 1 : 0;
        $view->setTemplate("errors");
        $view->errorMessage = $subject->getLastException()->getMessage();
        $view->errorStack = $subject->getLastException()->getTraceAsString();
        echo $view->execute();
    }
}
