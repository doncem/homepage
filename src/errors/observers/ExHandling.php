<?php
namespace errors\observers;

use \SplObserver;
use \SplSubject;

/**
 * Uses the observer pattern to listen for exceptions.
 *
 * This code was largely inspired by the devzone article:
 *
 * http://devzone.zend.com/article/12229
 * @package errors_observers
 */
class ExHandling implements SplObserver {

    /**
     * Log the exception.
     *
     * @param SplSubject $subject
     */
    public function update(SplSubject $subject) {
        $registry = new \xframe\registry\Registry(array(
            'root' => ROOT_DIR,
            'tmp' => ROOT_DIR . "tmp" . DIRECTORY_SEPARATOR,
            'configFilename' => "config" . DIRECTORY_SEPARATOR . CONFIG . ".ini",
        ));
        $registry->load(ROOT_DIR . "config" . DIRECTORY_SEPARATOR . CONFIG . ".ini", ROOT_DIR);
        $view = new \xframe\view\TwigView($registry,
                                          ROOT_DIR,
                                          ROOT_DIR . "tmp" . DIRECTORY_SEPARATOR,
                                          ROOT_DIR . "view" . DIRECTORY_SEPARATOR);
        $html = new \HtmlInit($registry);
        $plugin = new \SetStatics(new \xframe\core\DependencyInjectionContainer(array("root" => ROOT_DIR)));
        $plugin->init();

        $view->isLive = CONFIG == "live" ? 1 : 0;
        $view->html = $html->getDefaults();
        $view->css = $plugin->getCSS("error", "", $view->isLive);
        $view->js = $plugin->getJS("error", "", $view->isLive);
        $view->title = "Oh Dear... Error";
        $view->setTemplate("errors");
        $view->errorMessage = $subject->getLastException()->getMessage();
        $view->errorStack = $subject->getLastException()->getTraceAsString();
        echo $view->execute();
    }
}
