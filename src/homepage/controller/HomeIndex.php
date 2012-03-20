<?php

namespace homepage\controller;
//use \xframe\request\Controller;

class HomeIndex extends \homepage\HomeController {

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
        
        $this->view->links = $links;
    }
    
    /**
     * @Request("links")
     * @Template("homepage/links")
     */
    public function links() {
        $this->view->page = "links";
        try {
            //@$simple = new SimpleXMLElement("http://www.die2nite.com/xml/?k=02ebc76bebd7daf01051b9b4c17c46ab", 0, true);
            //$this->view->simple = $simple;
        } catch(Exception $e) {}
    }
}
