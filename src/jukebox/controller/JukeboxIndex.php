<?php
namespace jukebox\controller;

use jukebox\helpers\GetSongs;

/**
 * Jukebox experiment controller
 * @package jukebox_controllers
 */
class JukeboxIndex extends \ControllerInit {

    const SOURCE = "get-songs";

    /**
     * Main landing page
     * @Request("jukebox")
     * @Parameter(name="source", validator="\xframe\validation\RegEx('/(\Aget-songs\z|^$)/')", required=false)
     * @Parameter(name="q", validator="\xframe\validation\RegEx('/(\S{3,}|^$)/')", required=false)
     * @Template("jukebox/index")
     */
    public function index() {
        $this->checkPHP54();

        switch ($this->request->source) {
            case self::SOURCE:
                $this->getSongs();
                break;
            default:
                $this->getQueue();
                break;
        }
    }

    /**
     * Get queue
     */
    private function getQueue() {
        $helper = new GetSongs($this->dic->em);

        $this->view->addParameter("queue", $helper->getQueue());
    }

    /**
     * List of songs in json format
     */
    private function getSongs() {
        $this->view = new \xframe\view\JSONView();
        $query = urldecode($this->request->q);

        try {
            $helper = new GetSongs($this->dic->em);
            $this->view->addParameter("data", $helper->search($query));
        } catch (Exception $e) {
            $this->view->addParameter("error", $e->getMessage());
        }
    }
}
