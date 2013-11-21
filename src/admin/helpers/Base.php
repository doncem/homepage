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
     * @var array
     */
    protected $params = array();

    public function __construct(DIC $dic, $action) {
        $this->dic = $dic;
        $this->action = $action;
    }

    public function setParams(array $params) {
        $this->params = $params;
    }

    abstract public function getTemplateName();

    abstract public function process();
}
