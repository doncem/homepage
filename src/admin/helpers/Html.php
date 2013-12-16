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
                case "scan-content":
                    $data = $this->scanContents();
                    $this->checkForUpdates($data);
                    $this->redirect("content");
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

        if ($this->user["level"] == "admin") {
            $data["admin"]["google-site-verification"] = $this->request->{"google-site-verification"};
        }

        $data["editor"]["language"] = $this->request->language;
        $data["editor"]["title"] = $this->request->title;

        foreach ($this->request->descriptions as $key => $description) {
            $arr = explode("-", $key);
            $module = array_shift($arr);
            $data["editor"]["descriptions"][$module][implode("-", $arr)] = htmlentities(strip_tags($description), ENT_QUOTES, "UTF-8");
        }

        unset($data["jquery"]);

        $this->setContents($data);

        return array("data" => "success");
    }

    private function getContents() {
        $plugin = $this->dic->plugin->htmlContent;

        return $plugin->getPackage();
    }

    private function setContents($data) {
        $plugin = $this->dic->plugin->htmlContent;
        $plugin->setPackage($data);
    }

    /**
     * Scan tmp folder for new pages
     * @return array {<br />
     * &nbsp;&nbsp;(string) resource => (string) module<br />
     * }
     */
    private function scanContents() {
        if (!is_dir($this->dic->tmp) || false === ($dh = opendir($this->dic->tmp))) {
            return array();
        }

        $contents = array();

        while (($file = readdir($dh)) !== false) {
            $path = $this->dic->tmp . $file;

            if ($file == "." || $file == ".." || !ctype_alpha(substr($file, 0, 1))) {
                continue;
            } else if (substr($path, -4) == ".php") {
                $content = file_get_contents($path);

                if (strpos($content, "xframe\\view\\TwigView") !== false) {
                    $matches = array();
                    preg_match_all('/return new (.+)\\\\controller/', $content, $matches);
                    $contents[substr($file, 0, -4)] = isset($matches[1]) ? $matches[1][0] : $matches;
                }
            }
        }

        return $contents;
    }

    /**
     * Check if there are any update in descriptions
     * @param array $data
     */
    private function checkForUpdates(array $data) {
        $html = $this->getContents();

        foreach ($data as $resource => $module) {
            if (isset($html["editor"]["descriptions"][$module]) && isset($html["editor"]["descriptions"][$module][$resource])) {
                unset($data[$resource]);
            } else {
                if (!isset($html["editor"]["descriptions"][$module])) {
                    $html["editor"]["descriptions"][$module] = array();
                }

                $html["editor"]["descriptions"][$module][$resource] = "";
            }
        }

        if (count($data) > 0) {
            $this->setContents($html);
        }
    }
}
