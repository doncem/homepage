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
        
        $this->assertCount(3, $defaults);
        $this->assertArrayHasKey("lang", $defaults);
        $this->assertArrayHasKey("title", $defaults);
        $this->assertArrayHasKey("jquery", $defaults);
        $this->assertEquals("en", $defaults["lang"]);
        $this->assertEquals("Donatas Martinkus", $defaults["title"]);

        $alters = $this->html->getDefaults("lt", "Mockup Test", array("life" => "time"));

        $this->assertCount(4, $alters);
        $this->assertArrayHasKey("lang", $alters);
        $this->assertArrayHasKey("title", $alters);
        $this->assertArrayHasKey("jquery", $alters);
        $this->assertArrayHasKey("life", $alters);
        $this->assertEquals("lt", $alters["lang"]);
        $this->assertEquals("Mockup Test", $alters["title"]);
        $this->assertEquals("time", $alters["life"]);
    }

    /**
     * Test both jquery places - local and googleapis
     */
    public function testNoInternet() {
        $this->html = new HtmlInit($this->registry);
        $yes = $this->html->getDefaults();

        $this->assertRegExp('/\/' . HtmlInit::JQUERY_VERSION . '\//', $yes["jquery"]);
        $this->assertRegExp('/ajax.googleapis.com/', $yes["jquery"]);

        $this->html = new HtmlInit($this->registry);
        HtmlInit::$JQUERY_HOSTNAME = "";
        $no = $this->html->getDefaults();

        $this->assertRegExp('/\/js\/jquery-' . HtmlInit::JQUERY_VERSION . '.min/', $no["jquery"]);
        $this->assertFileExists(__DIR__ . "/../../www" . $no["jquery"]);
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
                            "title" => "Donatas Martinkus"
                        )
                    )
                );

        $htmlInit->getDefaults();

        $htmlInit->expects($this->any())
                ->method("getDefaults")
                ->with(
                    "lt",
                    "Mockup Test",
                    array(
                        "life" => "time"
                    )
                )
                ->will($this->returnValueMap(
                    array(
                        "lang" => "lt",
                        "title" => "Mockup Test",
                        "life" => "time"
                    )
                ));

        $htmlInit->getDefaults("lt", "Mockup Test", array("life" => "time"));
    }
}
