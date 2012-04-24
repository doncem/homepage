<?php

namespace homepage\controller;

/**
 * Basic homepage elements:
 * <ul>
 * <li>index</li>
 * <li>about</li>
 * <li>hell-music</li>
 * <li>links</li>
 * </ul>
 */
class HomeIndex extends \HomeController {

    public function init() {
        parent::init();
        $html = new \HtmlInit($this->dic->registry);
        $this->view->html = $html->getDefaults();
    }
    
    /**
     * @Request("index")
     * @Template("homepage/index")
     */
    public function index() {
        $this->view->page = "main";
    }
    
    /**
     * @Request("about")
     * @Template("homepage/about")
     */
    public function about() {
        $this->view->page = "about";
    }
    
    /**
     * @Request("hell-music")
     * @Template("homepage/hell-music")
     */
    public function hellMusic() {
        $this->view->page = "hellmusic";
        
        $base = $this->dic->root . $this->dic->registry->get("HTML_PUBLIC") . "/docs/hell-music/";
        $dirs = array("news" => "naujienos/", "reviews" => "straipsniai/recenzijos", "reportings" => "straipsniai/reportazhai");
        
        $links = array();
        foreach ($dirs as $key => $dir) {
            $arr = array();
            $new_str = $dir;
            $dirName = dir($base . $new_str);
            while ($file = $dirName->read()) {
                if ($file != '.' && $file != '..') {
                    $temp = substr($file, 9, strlen($file) - 13);
                    $arr[str_replace("+", "%20", urlencode($file))] = array("/docs/hell-music/" . $new_str, $temp);
                }
            }
            $dirName->close();
            ksort($arr);
            $links[$key] = $arr;
        }
        
        $this->view->columns = 4;
        $this->view->amount = 11;
        $this->view->cells = (ceil(count($links["news"]) / $this->view->amount) % $this->view->columns) * $this->view->columns;
        $this->view->links = $links;
    }
    
    /**
     * @Request("links")
     * @Template("homepage/links")
     */
    public function links() {
        $this->view->page = "links";
    }
}
