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
     * @var array
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
                $user = $model->getBySessionID(filter_input(INPUT_COOKIE, "session_id"));

                if ($user instanceof \admin\models\admUser) {
                    $model->updateUserLastLogin($user);
                    helpers\Auth::updateCookie($user->last_session);

                    return $user->jsonSerialize();
                }

                return null;
            },
            helpers\Auth::COOKIE_LIFETIME / 2
        );
    }

    protected function postDispatch() {
        parent::postDispatch();

        $this->view->is_admin = true;

        if (is_array($this->user)) {
            $this->view->is_admin_logged_in = helpers\Auth::isLoggedIn(
                array_key_exists("last_session", $this->user) ? $this->user["last_session"] : ""
            );
            $this->view->user = $this->user;
        } else {
            $this->view->is_admin_logged_in = false;
        }
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
            case "html":
                $helper = "Html";
                break;
            case "my-profile":
                $helper = "Profile";
                break;
            default:
                break;
        }

        $this->runHelper($helper, $action);
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
            $helper = new $classname($this->dic, $action, $this->user);
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
