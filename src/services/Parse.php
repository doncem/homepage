<?php

namespace services;

/**
 * Description of Parse
 * @package services
 */
class Parse extends \xframe\request\Controller {

    /**
     * Client to execute request
     * @var mixed
     */
    private $client;

    /**
     * Deal with action
     * @Request("serve-action")
     * @Parameter(name="action", validator="\xframe\validation\RegEx('/\A.+\z/')")
     * @Parameter(name="q", required=true)
     * @View("xframe\view\JSONView")
     */
    public function action() {
        if (class_exists("GearmanClient", false)) {
            $config = "\\script\\gearman\\config\\" . ucfirst(filter_input(INPUT_SERVER, "CONFIG"));
            $config = new $config();
            $this->client = $config->gearman_client;
        } else {
            $this->client = new \jukebox\helpers\GetSongs($this->dic->em);
        }

        switch ($this->request->action) {
            case "set-queue":
                $this->setQueue();
                break;
            default:
                $this->view->addParameter("result", "error");
                $this->view->addParameter("data", "No '{$this->request->action}' action found");
                break;
        }
    }

    /**
     * Set the queue via available client
     */
    private function setQueue() {
        if (count($this->request->q) > 0) {
            try {
                $this->client->setQueue(array($this->request->q));
                $this->view->addParameter("result", "success");
            } catch(Exception $e) {
                $this->view->addParameter("result", "failure");
                $this->view->addParameter("data", "Failed to execute '{$this->request->action}' action");
            }
        } else {
            $this->view->addParameter("result", "invalid");
            $this->view->addParameter("data", "Query array must not be empty");
        }
    }
}
