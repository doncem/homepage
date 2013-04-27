<?php

namespace jukebox\controller;

/**
 * Jukebox experiment controller
 */
class JukeboxIndex extends \homepage\HomeController {

    /**
     * Main landing page
     * @Request("jukebox")
     * @Template("jukebox/index")
     */
    public function index() {
    }
    
    /**
     * List of songs in json format
     * @Request("jukebox/get-songs")
     * @View("xframe\view\JSONView")
     */
    public function getSongs() {
        //$this->addParameter("asd", "asdasd");
    }
}
