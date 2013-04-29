<?php

/**
 * IP retrieval test
 */
class GetIPTest extends \PHPUnit_Framework_TestCase {

    /**
     * Test it
     */
    public function testConstruct() {
        $_SERVER["HTTP_CLIENT_IP"] = "bla";
        $this->assertEquals("bla", GetIP::ip());
        unset($_SERVER["HTTP_CLIENT_IP"]);
        
        $_SERVER["HTTP_X_FORWARDED_FOR"] = "bla";
        $this->assertEquals("bla", GetIP::ip());
        unset($_SERVER["HTTP_X_FORWARDED_FOR"]);
        
        $_SERVER["REMOTE_ADDR"] = "bla";
        $this->assertEquals("bla", GetIP::ip());
        unset($_SERVER["REMOTE_ADDR"]);
        
        $this->assertEquals("undefined", GetIP::ip());
    }
}
