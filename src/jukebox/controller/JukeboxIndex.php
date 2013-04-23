<?php

namespace jukebox\controller;

class JukeboxIndex extends \homepage\HomeController {

    /**
     * @Request("jukebox")
     * @Template("jukebox/index")
     */
    public function index() {
    }
    
    /**
     * @Request("jukebox/get-songs")
     * @View("xframe\view\JSONView")
     */
    public function getSongs() {
        //$this->addParameter("asd", "asdasd");
    }
}
