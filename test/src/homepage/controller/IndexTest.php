<?php
namespace homepage\controller;

/**
 * Landing page test
 */
class IndexTest extends \ControllerTestCase {

    /**
     * Main page test
     */
    public function testIndex() {
        $this->setUpController("/index/");
        $this->assertNotEmpty($this->response);
    }

    /**
     * About page test
     */
    public function testAbout() {
        $this->setUpController("/about/");
        $this->assertNotEmpty($this->response);
    }

    /**
     * Hell-music page test
     */
    public function testHellMusic() {
        $this->setUpController("/hell-music/");
        $this->assertNotEmpty($this->response);
    }

    /**
     * Links page test
     */
    public function testLinks() {
        $this->setUpController("/links/");
        $this->assertNotEmpty($this->response);
    }
}
