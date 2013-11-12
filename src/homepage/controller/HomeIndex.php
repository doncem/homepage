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
 * @package homepage_controllers
 */
class HomeIndex extends \homepage\HomeController {
    
    /**
     * Homepage landing pagee
     * @Request("index")
     * @Template("homepage/index")
     */
    public function index() {}
    
    /**
     * About homepage project
     * @Request("about")
     * @Template("homepage/about")
     */
    public function about() {}
    
    /**
     * List of works I&#39;ve done for hell-music website
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
                    $arr[str_replace("+", "%20", urlencode($file))] = array("/hell-music/" . $dir, $temp);
                }
            }
            $dirName->close();
            ksort($arr);
            $links[$key] = $arr;
        }
        
        $this->view->columns = 4;
        $this->view->amount = 12;
        $this->view->cells = (ceil(count($links["news"]) / $this->view->amount) % $this->view->columns) * $this->view->columns;
        $this->view->links = $links;
    }
    
    /**
     * List of links
     * @Request("links")
     * @Template("homepage/links")
     */
    public function links() {}
}
