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
    }

    public function setRequest(\xframe\request\Request $params) {
        $this->request = $params;
    }

    abstract public function getTemplateName();

    /**
     * Contains 'error' key with message if something went wrong, and 'data' key if anything must be passed
     * @return array
     */
    abstract public function process();
}
