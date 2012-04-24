<?php

namespace cdcollection\controller;

class CdIndex extends \HomeController {

    public function init() {
        parent::init();
        $html = new \HtmlInit($this->dic->registry);
        $this->view->html = $html->getDefaults(null, "CD Collection", null, array("subtitle" => "ASD"));
    }

    /**
     * @Request("cdcollection")
     * @Parameter(name="artist")
     * @Parameter(name="album")
     * @Template("cdcollection/index")
     */
    public function index() {
        $this->view->page = "cdcollection";
    }
}
