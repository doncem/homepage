<?php

namespace admin\controller;

use admin\helpers;

/**
 * Description of Index
 * @package admin_controllers
 */
class Index extends \ControllerInit {

    private $package;

    protected function init() {
        parent::init();

        $this->package = "admin" . DIRECTORY_SEPARATOR;
    }

    /**
     * Homepage landing pagee
     * @Request("admin")
     * @Parameter(name="action", validator="\xframe\validation\RegEx('/(\S+|^$)/')", required=false)
     * @Template("admin/index")
     */
    public function admin() {
        $action = $this->request->action;
        $helper = "";

        switch ($action) {
            case "logout":
            case "login":
                $helper = "Auth";
                break;
            default:
                break;
        }

        if (strlen($helper) > 0) {
            $classname = "admin\\helpers\\{$helper}";
            /* @var $helper helpers\Base */
            $helper = new $classname($this->dic, $action);
            $helper->process();
            $this->view->setTemplate($this->package . $helper->getTemplateName());
        }

        $this->view->is_admin = true;
        $this->view->is_admin_logged_in = helpers\Auth::isLoggedIn();
    }
}
