<?php

/**
 * Static page
 */
interface StaticPage {

    /**
     * Get the page
     * @param string $link
     */
    public function get($link = "");
    
    /**
     * Get page links so to loop threw them for next pages
     */
    public function getPageHrefs();
    
    /**
     * Save the page
     */
    public function save();
    
    /**
     * Check the static content is symlinked so the static content could be transfered to server
     */
    public function checkSymlinks();
}
