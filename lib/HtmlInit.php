<?php
use xframe\registry\Registry;

/**
 * Iniate default html elements 
 */
class HtmlInit {
	
	/**
	 * @var string
	 */
	private $doctype;
	
	/**
	 * @var string html tag attribute
	 */
	private $lang;
	
	/**
	 * @var string Filename
	 */
	private $css;
	
	/**
	 * @var string Filename
	 */
	private $js;

	/**
	 * Set default html elements from Registry
	 * @param Registry $registry 
	 */
	public function __construct(Registry $registry) {
		$this->doctype = $registry->get("HTML_DOCTYPE");
		$this->lang = $registry->get("HTML_LANG");
		$this->css = $registry->get("HTML_CSS");
		$this->js = $registry->get("HTML_JS");
	}

	/**
	 * Get default html elements.<br />
	 * If default is not good enough - set other ones
	 * @param string $doctype [optional]
	 * @param string $lang [optional] html tag attribute
	 * @param string $css [optional] Filename
	 * @param string $js [optional] Filename
	 * @return array
	 */
	public function getDefaults($doctype = null,
								$lang = null,
								$css = null,
								$js = null) {
		return array("doctype" => $doctype ? $doctype : $this->doctype,
					 "lang" => $lang ? $lang : $this->lang,
					 "css" => $css ? $css : $this->css,
					 "js" => $js ? $js : $this->js);
	}
}
