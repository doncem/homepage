<?php

namespace admin\helpers;

use xframe\core\DependencyInjectionContainer as DIC;

/**
 * Description of AdminAuth
 * @package admin_helpers
 */
abstract class Base {

    /**
     * Dependency injection container
     * @var DIC
     */
    protected $dic;

    /**
     * Requested action
     * @var string
     */
    protected $action;

    /**
     * Requested params
     * @var \xframe\request\Request
     */
    protected $request = array();

    /**
     * Current user
     * @var array
     */
    protected $user = array();

    /**
     * Initiate helper. Check if user is present
     * @param DIC $dic
     * @param string $action
     * @param array $user
     */
    public function __construct(DIC $dic, $action, array $user = null) {
        if (is_null($user) && get_called_class() != "admin\\helpers\\Auth") {
            header("location:/admin");
        }

        $this->dic = $dic;
        $this->action = $action;
        $this->user = $user;
    }

    public function setRequest(\xframe\request\Request $request) {
        $this->request = $request;
    }

    public function process() {
        if (strpos($this->request->getRequestedResource(), "ajax-") === 0) {
            return $this->processAJAX();
        } else {
            return $this->processRegular();
        }
    }

    /**
     * General redirector. Goes to current action and extra parameters if any defined
     * @param string $parameter
     */
    protected function redirect($parameter = null) {
        header("location:/admin/{$this->action}" . (strlen($parameter) > 0 ? "/{$parameter}" : ""));
    }

    abstract public function getTemplateName();

    /**
     * Contains 'error' key with message if something went wrong, and 'data' key if anything must be passed
     * @return array
     */
    abstract protected function processRegular();

    /**
     * On any AJAX call prefixed with 'ajax-' - execute this method
     */
    abstract protected function processAJAX();
}
