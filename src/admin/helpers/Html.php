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

    protected function processRegular() {
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

    protected function processAJAX() {
        $data = $this->getContents();
        // need a user level here. need to pass it through helper constructor
        die(var_dump($this->request->{"google-site-verification"}));
    }

    private function getContents() {
        $plugin = $this->dic->plugin->htmlContent;

        return $plugin->getPackage($this->dic->registry->get("HTML_PACKAGE"));
    }

    private function setContents($data) {
        $plugin = $this->dic->plugin->htmlContent;
        $plugin->setPackage($this->dic->registry->get("HTML_PACKAGE"), $data);
    }
}
