<?php

/**
 * Object vars serialization test
 */
class SerializeMyVarsTest extends \PHPUnit_Framework_TestCase {

    /**
     * General object
     * @var stdClass
     */
    private $object;

    /**
     * Set up test object to serialize
     */
    protected function setUp() {
        $this->object = new ObjectMockup();
    }

    /**
     * Test it
     */
    public function testObject() {
        $json = json_encode($this->object);

        $this->assertJsonStringEqualsJsonString('{"var2":["test2"],"var3":{}}', $json);
    }
}

/**
 * Mock up object with SerializeMyVars class
 */
class ObjectMockup extends SerializeMyVars {

    /**
     * This should not be visible in the result
     * @var string
     */
    private $var1;

    /**
     * This should be visible
     * @var array
     */
    protected $var2;

    /**
     * This also should be visible
     * @var stdClass
     */
    public $var3;

    /**
     * Set up class variables
     */
    public function __construct() {
        $this->var1 = "test1";
        $this->var2 = array("test2");
        $this->var3 = new stdClass();
    }
}
