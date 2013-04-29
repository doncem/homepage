<?php

/**
 * Webpage to deploy
 */
class Page implements StaticPage {
    
    /**
     * What SERVER_NAME are we accessing
     * @var string
     */
    private $url;
    
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
     * @param string $static_url
     */
    public function __construct($url, $static_url) {
        $this->url = $url;
        $this->static_dir = $static_url;
    }

    /**
     * Stores public resources linked by &#39;source&#39; => &#39;link_name&#39;
     * @return array
     */
    public static function getSymlinks() {
        return array(
            __DIR__ . "/../www/css/" => "css",
            __DIR__ . "/../www/docs/" => "docs",
            __DIR__ . "/../www/img/" => "img",
            __DIR__ . "/../www/js/" => "js"
        );
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
        foreach (Page::getSymlinks() as $source => $link) {
            if (!is_link($this->static_dir . $link)) {
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
