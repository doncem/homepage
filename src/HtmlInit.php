<?php
use xframe\registry\Registry;

/**
 * Iniate default html elements
 * @package common
 */
class HtmlInit {

    /**
     * jQuery version in use
     */
    const JQUERY_VERSION = "2.0.3";

    /**
     * Hostname to check internet is alive
     */
    public static $JQUERY_HOSTNAME = "www.google.com";

    /**
     * &lt;html&gt; tag attribute
     * @var string
     */
    private $lang;

    /**
     * Website general name included in every page
     * @var string
     */
    private $title;

    /**
     * Set default html elements from Registry
     * @param Registry $registry
     */
    public function __construct(Registry $registry) {
        $this->lang = $registry->get("HTML_LANG");
        $this->title = $registry->get("HTML_TITLE");
    }

    /**
     * Get default html elements.<br />
     * If default is not good enough - set other ones
     * @param string $lang [optional] html tag attribute
     * @param string $title [optional]
     * @param array $params [optional] Additional params to pass
     * @return array
     */
    public function getDefaults($lang = null,
                                $title = null,
                                array $params = array()) {
        if (HtmlInit::doWeHaveGoogle()) {
            $js = "/js/jquery-" . HtmlInit::JQUERY_VERSION . ".min.js";
        } else {
            $js = "//ajax.googleapis.com/ajax/libs/jquery/" . HtmlInit::JQUERY_VERSION . "/jquery.min.js";
        }

        return array_merge(array(
            "lang" => $lang ? $lang : $this->lang,
            "title" => $title ? $title : $this->title,
            "jquery" => $js),
            $params
        );
    }

    /**
     * Checks whether apis for jQuery is available or not
     * @return boolean
     */
    public static function doWeHaveGoogle() {
        return (!$sock = @fsockopen(HtmlInit::$JQUERY_HOSTNAME, 80, $num, $error, 5));
    }
}
