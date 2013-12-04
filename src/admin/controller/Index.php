<?php

namespace admin\controller;

use admin\helpers;

/**
 * Description of Index
 * @package admin_controllers
 */
class Index extends \ControllerInit {

    private $package;

    /**
     * Current user
     * @var \admin\models\admUser
     */
    private $user;

    protected function init() {
        $this->checkPHP54();

        parent::init();

        $this->package = "admin" . DIRECTORY_SEPARATOR;

        $this->user = $this->getAndSetCache(
            \CacheVars::NAMESPACE_ADMIN,
            \CacheVars::KEY_ADMIN_USER,
            function() {
                $model = new helpers\db\User($this->dic->em);

                return $model->getBySessionID(filter_input(INPUT_COOKIE, "session_id"));
            },
            helpers\Auth::COOKIE_LIFETIME / 2
        );
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
                $this->expireCache(\CacheVars::NAMESPACE_ADMIN, \CacheVars::KEY_ADMIN_USER);
                break;
            default:
                break;
        }

        $this->runHelper($helper, $action);

        $this->view->is_admin = true;
        $this->view->is_admin_logged_in = helpers\Auth::isLoggedIn(
            $this->user instanceof \admin\models\admUser ? $this->user->last_session : ""
        );
    }

    /**
     * Run request processor aka helper
     * @param string $helper
     * @param string $action
     */
    private function runHelper($helper, $action) {
        if (strlen($helper) > 0) {
            $classname = "admin\\helpers\\{$helper}";
            /* @var $helper helpers\Base */
            $helper = new $classname($this->dic, $action);
            $helper->setRequest($this->request);
            $result = $helper->process();
            $this->view->setTemplate($this->package . $helper->getTemplateName());

            if (array_key_exists("error", $result)) {
                $this->view->errorMessage = $result["error"];
            }

            if (array_key_exists("data", $result)) {
                $this->view->data = $result["data"];
            }
        }
    }
}
