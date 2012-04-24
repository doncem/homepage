<?php

namespace cdcollection\controller;

class CdIndex extends \cdcollection\CdController {

    /**
     * @Request("cdcollection")
     * @Template("cdcollection/index")
     */
    public function index() {
        $this->view->page = "cdcollection";
    }
    
    /**
     * @Request("cdcollection/band)
     * @Parameter(name="artist")
     * @Template("cdcollection/band")
     */
    public function band() {
        //
    }
    
    /**
     * @Request("cdcollection/band)
     * @Parameter(name="album")
     * @Template("cdcollection/album")
     */
    public function album() {
        //
    }
}
