<?php

namespace admin\helpers;

/**
 * Description of Html
 * @package admin_helpers
 */
class Html extends Base {

    public function getTemplateName() {
        return "html";
    }

    public function process() {
        $params = $this->request->getMappedParameters();

        if (count($params) > 1) {
            switch ($params[1]) {
                case "content":
                    $data = $this->getContents();
                    break;
                default:
                    return array("error" => "Functionality not found");
            }
        } else {
            return array("error" => "Wrong request");
        }

        return array("data" => $data);
    }

    private function getContents() {
        $plugin = $this->dic->plugin->htmlContent;

        return $plugin->getPackage($this->dic->registry->get("HTML_PACKAGE"));
    }
}
