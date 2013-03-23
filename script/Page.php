<?php

/**
 * Static page
 */
class Page implements StaticPage {
    
    /**
     * What SERVER_NAME are we accessing
     * @var string
     */
    private $url;
    
    /**
     * Used to make symlinks after deployment
     * @var array
     */
    private $public_resources;
    
    /**
     * Where to export static pages
     * @var string
     */
    private $static_dir;
    
    /**
     * Current accessed resource
     * @var string
     */
    private $current_resource;
    
    /**
     * Page contents
     * @var string
     */
    private $contents;
    
    /**
     * Store already deployed pages - resources
     * @var array
     */
    public $deployed = array();
    
    /**
     * Init
     * @param string $url
     * @param array $public_resources 'source' => 'link_name'
     * @param string $static_url
     */
    public function __construct($url, array $public_resources, $static_url) {
        $this->url = $url;
        $this->public_resources = $public_resources;
        $this->static_dir = $static_url;
    }
    
    /**
     * Get it
     * @param string $link
     */
    public function get($link = "") {
        $this->current_resource = (substr($link, 0, 1) == "?") ?  "" : substr($link, 0, strpos($link, "?"));
        $this->contents = file_get_contents($this->url . $link);
    }
    
    /**
     * Save it
     */
    public function save() {
        if (!file_exists($this->static_dir . $this->current_resource)) {
            $resource = explode("/", $this->current_resource);
            $current = "/";
            
            foreach ($resource as $folder) {
                if (!file_exists($this->static_dir .$current . $folder)) {
                    mkdir($this->static_dir . $current . $folder);
                }
                
                $current .= $folder . "/";
            }
        }
        
        $index = fopen($this->static_dir . $this->current_resource . "/index.html", "w");
        fwrite($index, $this->contents, strlen($this->contents));
        fclose($index);
        $this->deployed[] = $this->current_resource;
    }
    
    /**
     * Create them if don't exist
     */
    public function checkSymlinks() {
        foreach ($this->public_resources as $source => $link) {
            if (!dir($this->static_dir . $link)) {
                exec("ln -s " . $source . " " . $this->static_dir . $link);
            }
        }
    }
    
    /**
     * Get them
     * @return array
     */
    public function getPageHrefs() {
        $matches = array();
        $found = preg_match_all("#\<a href=\"(.*?)\"#si", $this->contents, $found_mathces);
        
        if ($found > 0) {
            foreach ($found_mathces[1] as $match) {
                if ((substr($match, 0, 4) != "http") && ($match != "javascript:void(0);")) {
                    $matches[] = $match;
                }
            }
        }

        return $matches;
    }
}
