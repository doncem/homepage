<?php

namespace admin\helpers;

/**
 * Description of Auth
 * @package admin_helpers
 */
class Auth extends Base {

    const USERNAME = "admin";
    const PASSWORD = "1b60f5f4521e40796008a10f4670a34b";//let_me_in

    public function getTemplateName() {
        throw new Exception("You should not render anything directed to Auth helper", 0, null);
    }

    public function process() {
        if ($this->action == "login") {
            $this->login();
        } else {
            $this->logout();
        }

        header("location:/admin/");
    }

    private function login() {
        if (isset($this->params["username"]) && $this->params["username"] == self::USERNAME &&
                isset($this->params["password"]) && md5($this->params["password"]) == self::PASSWORD) {
            setcookie(
                "session_id",
                date("ymd") . md5(mt_rand(100)) . date("His"),
                time() + (60 * 60),
                "/",
                null,
                false,
                true
            );
        }
    }

    private function logout() {
        setcookie("session_id", "", time() - (60 * 60 * 24), "/", null, false, true);
    }

    public static function isLoggedIn() {
        return (bool)filter_input(INPUT_COOKIE, "session_id");
    }
}
