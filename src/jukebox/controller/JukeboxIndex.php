<?php
namespace jukebox\controller;

use jukebox\helpers\GetSongs;

/**
 * Jukebox experiment controller
 */
class JukeboxIndex extends \homepage\HomeController {

    const SOURCE = "get-songs";
    const SET_QUEUE = "set-queue";

    /**
     * Main landing page
     * @Request("jukebox")
     * @Parameter(name="source", validator="\xframe\validation\RegEx('/(\Aget-songs\z|\Aset-queue\z|^$)/')")
     * @Parameter(name="q", validator="\xframe\validation\RegEx('/(\S{3,}|^$)/')", required=false)
     * @Template("jukebox/index")
     */
    public function index() {
        switch ($this->request->source) {
            case self::SOURCE:
                $this->getSongs();
                break;
            case self::SET_QUEUE:
                $this->setQueue();
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

        $this->view->addParameter("history", $helper->getHistory());
    }

    /**
     * Save track into queue
     */
    private function setQueue() {
        //
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
