<?php

/**
 * Html initialization test
 */
class HtmlInitTest extends \PHPUnit_Framework_TestCase {

    /**
     * This class needs framework registry to construct it
     * @var xframe\registry\Registry
     */
    private $registry;

    /**
     * Object to test
     * @var HtmlInit
     */
    private $html;

    /**
     * Set up registry
     */
    protected function setUp() {
        $this->registry = new xframe\registry\Registry(parse_ini_file(__DIR__ . "/../../config/dev.ini"));
    }

    /**
     * Test html defaults set up
     */
    public function testGetDefaults() {
        $this->html = new HtmlInit($this->registry);
        $defaults = $this->html->getDefaults();
        
        $this->assertCount(4, $defaults);
        $this->assertArrayHasKey("lang", $defaults);
        $this->assertArrayHasKey("title", $defaults);
        $this->assertArrayHasKey("css", $defaults);
        $this->assertArrayHasKey("js", $defaults);
        $this->assertEquals("en", $defaults["lang"]);
        $this->assertEquals("Donatas Martinkus", $defaults["title"]);
        $this->assertEquals("main", $defaults["css"]);

        $alters = $this->html->getDefaults("lt", "Mockup Test", "test", array("life" => "time"));

        $this->assertCount(5, $alters);
        $this->assertArrayHasKey("lang", $alters);
        $this->assertArrayHasKey("title", $alters);
        $this->assertArrayHasKey("css", $alters);
        $this->assertArrayHasKey("js", $alters);
        $this->assertArrayHasKey("life", $alters);
        $this->assertEquals("lt", $alters["lang"]);
        $this->assertEquals("Mockup Test", $alters["title"]);
        $this->assertEquals("test", $alters["css"]);
        $this->assertEquals("time", $alters["life"]);
    }

    /**
     * Test both jquery places - local and googleapis
     */
    public function testNoInternet() {
        $this->html = new HtmlInit($this->registry);
        $yes = $this->html->getDefaults();

        $this->assertRegExp('/\/' . HtmlInit::JQUERY_VERSION . '\//', $yes["js"]);
        $this->assertRegExp('/ajax.googleapis.com/', $yes["js"]);

        $this->html = new HtmlInit($this->registry);
        HtmlInit::$JQUERY_HOSTNAME = "";
        $no = $this->html->getDefaults();

        $this->assertRegExp('/\/js\/jquery-' . HtmlInit::JQUERY_VERSION . '.min/', $no["js"]);
        $this->assertFileExists(__DIR__ . "/../../www" . $no["js"] . ".js");
    }

    /**
     * This is example of getMock usage. But code coverage does not cover desired method :(
     */
    public function pvzTestGetDefaults() {
        $htmlInit = $this->getMock("HtmlInit", array("getDefaults"), array($this->registry));
        $htmlInit->expects($this->any())
                ->method("getDefaults")
                ->will($this->returnValueMap(
                        array(
                            "lang" => "en",
                            "title" => "Donatas Martinkus",
                            "css" => "main"
                        )
                    )
                );

        $htmlInit->getDefaults();

        $htmlInit->expects($this->any())
                ->method("getDefaults")
                ->with(
                    "lt",
                    "Mockup Test",
                    "test",
                    array(
                        "life" => "time"
                    )
                )
                ->will($this->returnValueMap(
                    array(
                        "lang" => "lt",
                        "title" => "Mockup Test",
                        "css" => "test",
                        "life" => "time"
                    )
                ));

        $htmlInit->getDefaults("lt", "Mockup Test", "test", array("life" => "time"));
    }
}
