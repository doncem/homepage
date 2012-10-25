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
class HomeIndex extends \homepage\HomeController {
    
    /**
     * @Request("index")
     * @Template("homepage/index")
     */
    public function index() {}
    
    /**
     * @Request("about")
     * @Template("homepage/about")
     */
    public function about() {}
    
    /**
     * @Request("hell-music")
     * @Template("homepage/hell-music")
     */
    public function hellMusic() {
        $base = $this->dic->root . $this->dic->registry->get("HTML_PUBLIC") . "/docs/hell-music/";
        $dirs = array(
            "news" => "news/",
            "reviews" => "articles/reviews/",
            "reports" => "articles/reports/"
        );
        $links = array();
        
        foreach ($dirs as $key => $dir) {
            $arr = array();
            $dirName = dir($base . $dir);
            while ($file = $dirName->read()) {
                if ($file != "." && $file != "..") {
                    $temp = substr($file, 9, strlen($file) - 13);
                    $arr[str_replace("+", "%20", urlencode($file))] = array("/docs/hell-music/" . $dir, $temp);
                }
            }
            $dirName->close();
            ksort($arr);
            $links[$key] = $arr;
        }
        
        $this->view->columns = 4;
        $this->view->amount = (count($links["news"]) > 90 ? 10 : 11);
        $this->view->cells = (ceil(count($links["news"]) / $this->view->amount) % $this->view->columns) * $this->view->columns;
        $this->view->links = $links;
    }
    
    /**
     * @Request("links")
     * @Template("homepage/links")
     */
    public function links() {}
}
