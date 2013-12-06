<?php

namespace admin\helpers;

/**
 * Description of Auth
 * @package admin_helpers
 */
class Auth extends Base {

    const COOKIE_LIFETIME = 3600;

    private $do_redirect = true;
    private $error;

    public function getTemplateName() {
        if ($this->do_redirect) {
            throw new \Exception("You should not render anything directed to Auth helper", 0, null);
        } else {
            return "index";
        }
    }

    public function process() {
        if ($this->action == "login") {
            $this->login();
        } else {
            $this->logout();
        }

        if ($this->do_redirect) {
            header("location:/admin");
            exit();
        }

        return array("error" => $this->error);
    }

    private function login() {
        if (isset($this->request->username) && isset($this->request->password)) {
            $model = new db\User($this->dic->em);
            $user = $model->getByUsernameAndPassword($this->request->username, $this->request->password);

            if ($user instanceof \admin\models\admUser) {
                if ($user->getActive()) {
                    $session_id = date("ymd") . md5(mt_rand(100, 999)) . date("His");
                    self::updateCookie($session_id);
                    $user->last_session = $session_id;
                    $user->last_login = new \DateTime();
                    $model->save($user);
                } else {
                    $this->do_redirect = false;
                    $this->error = "User is not active. Check your email";
                }
            } else {
                $this->do_redirect = false;
                $this->error = "Wrong username and/or password";
            }
        }
    }

    private function logout() {
        self::destroyCookie();
    }

    /**
     * Check if current user is logged in
     * @param string $user_session
     * @return boolean
     */
    public static function isLoggedIn($user_session) {
        return filter_input(INPUT_COOKIE, "session_id") === $user_session && strlen($user_session) == 44;
    }

    /**
     * Updating for self::COOKIE_LIFETIME starting from now
     * @param string $session_id
     */
    public static function updateCookie($session_id) {
        setcookie("session_id", $session_id, time() + self::COOKIE_LIFETIME, "/", null, false, true);
    }

    /**
     * DESTROY!
     */
    public static function destroyCookie() {
        setcookie("session_id", "", time() - (60 * 60 * 24), "/", null, false, true);
    }
}
