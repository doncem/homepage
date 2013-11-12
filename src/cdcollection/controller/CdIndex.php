<?php

namespace cdcollection\controller;

/**
 * CD collection module
 * @package cdcollection_controllers
 */
class CdIndex extends \cdcollection\CdController {

    /**
     * Landing page
     * @Request("cdcollection")
     * @Template("cdcollection/index")
     */
    public function index() {
        //
    }
    
    /**
     * Band page. Has parameter &#39;artist&#39; which must be a valid word.<br />
     * Has an optional parameter &#39;album&#39; which if present must be a valid word
     * @Request("cdcollection/band)
     * @Parameter(name="artist", validator="\xframe\validation\RegEx('/\D+/')")
     * @Parameter(name="album", validator="\xframe\validation\RegEx('/\D+/')", required=false)
     * @Template("cdcollection/band")
     */
    public function band() {
        //
    }
}
