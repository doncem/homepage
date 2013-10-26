<?php
use xframe\core\System;
use xframe\request\Request;

/**
 * Generic controller set up
 */
class ControllerTestCase extends \PHPUnit_Framework_TestCase {

    /**
     * Our test system
     * @var System
     */
    private $system;

    /**
     * Our test response
     * @var string
     */
    protected $response;

    /**
     * Initiate Registry object
     */
    protected function setUp() {
        $this->system = new System(__DIR__ . "/../", CONFIG);
        $this->system->boot();
    }

    /**
     * Request url with params
     * @param string $url
     * @param array $params [optional] Default empty array
     */
    protected function setUpController($url, array $params = array()) {
        $request = new Request($url, $params);
        ob_start();
        $this->system->getFrontController()->dispatch($request);
        $this->response = ob_get_contents();
        ob_end_clean();
    }

    /**
     * Get it
     * @return xframe\core\DependencyInjectionContainer
     */
    protected function getDIC() {
        return $this->system;
    }
}
