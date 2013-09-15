<?php
namespace jukebox\controller;

/**
 * Description of JukeboxGearman
 */
class JukeboxGearman extends \xframe\request\Controller {

    /**
     * Gearman configuration
     * @var \_gearman\Config
     */
    private $config;

    /**
     * Request params
     * @var array
     */
    private $params;

    /**
     * Deal with action
     * @Request("jukebox-gearman")
     * @Parameter(name="action", validator="\xframe\validation\RegEx('/\b/')")
     * @Parameter(name="q")
     * @View("xframe\view\JSONView")
     */
    public function action() {
        $this->params = $this->request->getMappedParameters();
        $config = "\\script\\gearman\\config\\" . ucfirst(CONFIG);
        $this->config = new $config();

        switch ($this->params["action"]) {
            case "set-queue":
                $this->setQueue();
                break;
            default:
                $this->view->addParameter("result", "error");
                $this->view->addParameter("data", "No '{$this->params["action"]}' action found");
                break;
        }
    }

    private function setQueue() {
        if (count($this->params["q"]) > 0) {
            try {
                $this->config->gearman_client->setQueue($this->params["q"]);
                $this->view->addParameter("result", "success");
            } catch(Exception $e) {
                $this->view->addParameter("result", "failure");
                $this->view->addParameter("data", "Failed to execute '{$this->params["action"]}' action");
            }
        } else {
            $this->view->addParameter("result", "invalid");
            $this->view->addParameter("data", "Query array must not be empty");
        }
    }
}
