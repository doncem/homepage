<?php
/**
 * Initializing controller
 * @package common
 */
class ControllerInit extends xframe\request\Controller {

    /**
     * Is cache enabled?
     * @var boolean
     */
    protected $cacheEnabled = false;

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

        $this->cacheEnabled = (bool)$this->dic->registry->get("CACHE_ENABLED");
    }

    protected function postDispatch() {
        $config = filter_input(INPUT_SERVER, "CONFIG") ? filter_input(INPUT_SERVER, "CONFIG") : "live";
        $this->view->isLive = ($config == "live" || isset($this->request->DEPLOY)) ? 1 : 0;
        $this->view->lastDeploy = isset($this->request->DEPLOY) ? date("Y-m-d H") . "h" : "";

        $arr = explode("/", $this->request->getRequestedResource());
        $this->view->title = (strlen($this->page_title) > 0 ? $this->page_title . " - " : "")
            . ucwords(implode(" - ", array_reverse($arr)));

        $html = $this->dic->plugin->htmlContent;
        $plugin = $this->dic->plugin->staticPreload;

        $this->view->html = $html->getPackage();
        $this->view->css = $plugin->getCSS($this->module, $this->controller, $this->view->isLive);
        $this->view->js = $plugin->getJS($this->module, $this->controller, $this->view->isLive);
        $this->view->requestedPage = $this->request->getRequestedResource();
        $this->view->accessedModule = $this->module;
    }

    /**
     * Get it and update on miss. Call this method directly without checking if cache is enabled - it's done here
     * @param string $namespace
     * @param string $identifier
     * @param Closure $code
     * @param int $expire_time [optional] Default CacheVars::EXPIRE_IN_30_DAYS
     * @return mixed
     */
    protected function getAndSetCache($namespace, $identifier, Closure $code, $expire_time = CacheVars::EXPIRE_IN_30_DAYS) {
        if ($this->cacheEnabled) {
            return CacheHandler::getHandler($this->dic)->get_and_set($namespace, $identifier, $code, $expire_time);
        } else {
            return $code();
        }
    }

    /**
     * Get it
     * @param string $namespace
     * @param string $identifier
     * @return mixed false on cache miss
     */
    protected function getFromCache($namespace, $identifier) {
        return CacheHandler::getHandler($this->dic)->get($namespace, $identifier);
    }

    /**
     * Set it
     * @param string $namespace
     * @param string $identifier
     * @param mixed $content
     * @param int $expire_time [optional] Default CacheVars::EXPIRE_IN_30_DAYS
     * @see CacheVars
     */
    protected function setToCache($namespace, $identifier, $content, $expire_time = CacheVars::EXPIRE_IN_30_DAYS) {
        CacheHandler::getHandler($this->dic)->set($namespace, $identifier, $content, $expire_time);
    }

    /**
     * Expire given namespace
     * @param string $namespace
     * @param string $identifier [optional] Default null
     */
    protected function expireCache($namespace, $identifier = null) {
        if ($this->cacheEnabled) {
            CacheHandler::getHandler($this->dic)->expire($namespace, $identifier);
        }
    }

    /**
     * Check if PHP is above or equel 5.4
     * @throws Exception
     */
    protected function checkPHP54() {
        if (PHP_VERSION_ID < 50400) {
            throw new \Exception("This page needs PHP 5.4 at least. Current: " . phpversion(), -1, null);
        }
    }
}
