<?php
use xframe\registry\Registry;

/**
 * Iniate default html elements
 */
class HtmlInit {
    
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
     * Default css file name for webpage module
     * @var string Filename
     */
    private $css;
    
    /**
     * Set default html elements from Registry
     * @param Registry $registry
     */
    public function __construct(Registry $registry) {
        $this->lang = $registry->get("HTML_LANG");
        $this->title = $registry->get("HTML_TITLE");
        $this->css = $registry->get("HTML_CSS");
    }

    /**
     * Get default html elements.<br />
     * If default is not good enough - set other ones
     * @param string $lang [optional] html tag attribute
     * @param string $title [optional]
     * @param string $css [optional] Filename
     * @param array $params [optional] Additional params to pass
     * @return array
     */
    public function getDefaults($lang = null,
                                $title = null,
                                $css = null,
                                array $params = array()) {
        if (!$sock = @fsockopen("www.google.com", 80, $num, $error, 5)) {
            $js = "/js/jquery-2.0.0.min";
        } else {
            $js = "//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min";
        }
        return array_merge(array(
            "lang" => $lang ? $lang : $this->lang,
            "title" => $title ? $title : $this->title,
            "css" => $css ? $css : $this->css,
            "js" => $js),
            $params
        );
    }
}
