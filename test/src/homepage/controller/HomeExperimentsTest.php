<?php
namespace homepage\controller;

/**
 * Experiments controller test
 */
class HomeExperimentsTest extends \ControllerTestCase {

    /**
     * Test first experiment
     */
    public function testValidExperiment() {
        $this->setUpController("/experiments/jquery-window-grid");
        $this->assertNotEmpty($this->response);
    }

    /**
     * Test invalid experiment - should do redirect and throw exception here
     * @expectedException \Exception
     */
    public function testInvalidExperiment() {
        $this->setUpController("/experiments/liquid-tension");
    }
}
