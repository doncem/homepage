<?php

namespace homepage\controller;

/**
 * Basic homepage elements:
 * @package homepage_controllers
 */
class Index extends \ControllerInit {

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

        $this->view->amount = 10;
        $this->view->links = $links;
    }

    /**
     * List of links
     * @Request("links")
     * @Template("homepage/links")
     */
    public function links() {}
}
