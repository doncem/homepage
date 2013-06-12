<?php

/**
 * Class for deployment to /static/
 */
class DeployToStatic {
    
    /**
     * Webpage
     * @var StaticPage
     */
    private $page;
    
    /**
     * Links to process
     * @var array
     */
    private $resources;

    /**
     * Initiate webpage. Save landing page
     */
    public function __construct() {
        $this->page = new Page("http://donatasmart.local/", __DIR__ . "/../static/");

        $this->resources = $this->storePage($this->page, "?DEPLOY");
    }

    /**
     * Run deployment of the rest of the website
     */
    public function run() {
        $processed = array();

        while (count($this->resources) > 0) {
            $resource = array_shift($this->resources);
            $processed[] = $resource;

            if (!in_array(trim($resource, "/"), $this->page->deployed)
                    && (end(explode(".", $resource)) != "pdf")) {
                $another_resources = $this->storePage($this->page, trim($resource, "/") . "/?DEPLOY");

                foreach ($another_resources as $another) {
                    if (!in_array($another, $processed)) {
                        $this->resources[] = $another;
                    }
                }
            }
        }
        
        $this->finalize();
    }
    
    /**
     * Store it
     * @param StaticPage $page
     * @param string $resource
     * @return array
     */
    private function storePage(StaticPage $page, $resource) {
        $page->get($resource);
        $page->save();

        return $page->getPageHrefs();
    }
    
    /**
     * Check for symlinks
     */
    private function finalize() {
        $this->page->checkSymlinks();
    }
}

$deployment = new DeployToStatic();
$deployment->run();
