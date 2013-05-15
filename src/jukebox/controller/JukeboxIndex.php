<?php
namespace jukebox\controller;

use jukebox\helpers\GetSongs;

/**
 * Jukebox experiment controller
 */
class JukeboxIndex extends \homepage\HomeController {

    const SOURCE = "get-songs";
    /**
     * Main landing page
     * @Request("jukebox")
     * @Parameter(name="source", validator="\xframe\validation\RegEx('/(\Aget-songs\z|^$)/')")
     * @Parameter(name="q", validator="\xframe\validation\RegEx('/(\S{3,}|^$)/')", required=false)
     * @Template("jukebox/index")
     */
    public function index() {
        // we want list of songs
        if ($this->request->source == self::SOURCE) {
            //$this->getSongs();
        } else {
            //$this->view->js[] = "/jukebox/" . self::SOURCE . "/";
        }
        //so far doctrine fails to create proxy object by calling static method jsonSerialize
        //whereas it is just a public function implemented from JsonSerializable interface
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
