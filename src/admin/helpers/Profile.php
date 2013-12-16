<?php

namespace admin\helpers;

/**
 * Description of Profile
 * @package admin_helpers
 */
class Profile extends Base {

    public function getTemplateName() {
        return "profile";
    }

    protected function processRegular() {
        return array();
    }

    protected function processAJAX() {}
}
