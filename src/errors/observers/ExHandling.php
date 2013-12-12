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
        $config = filter_input(INPUT_SERVER, "CONFIG") ? filter_input(INPUT_SERVER, "CONFIG") : "live";
        $registry = new \xframe\registry\Registry(array(
            'root' => ROOT_DIR,
            'tmp' => ROOT_DIR . "tmp" . DIRECTORY_SEPARATOR,
            'configFilename' => "config" . DIRECTORY_SEPARATOR . $config . ".ini",
        ));
        $registry->load(ROOT_DIR . "config" . DIRECTORY_SEPARATOR . $config . ".ini", ROOT_DIR);
        $view = new \xframe\view\TwigView($registry,
                                          ROOT_DIR,
                                          ROOT_DIR . "tmp" . DIRECTORY_SEPARATOR,
                                          ROOT_DIR . "view" . DIRECTORY_SEPARATOR);
        $dic = new \xframe\core\DependencyInjectionContainer(array(
            "root" => ROOT_DIR,
            "registry" => $registry
        ));
        $html = new \HtmlContent($dic);
        $plugin = new \SetStatics($dic);
        $html->init();
        $plugin->init();

        $view->isLive = $config == "live" ? 1 : 0;
        $view->html = $html->getPackage($registry->get("HTML_PACKAGE"), "error");
        $view->css = $plugin->getCSS("error", "", $view->isLive);
        $view->js = $plugin->getJS("error", "", $view->isLive);
        $view->title = "Oh Dear... Error";
        $view->requestedPage = "index";
        $view->setTemplate("errors");
        $view->errorMessage = $subject->getLastException()->getMessage();
        $view->errorStack = $config == "live" ? "Sorry, trace is not available for public :p" : $subject->getLastException()->getTraceAsString();
        echo $view->execute();
    }
}
