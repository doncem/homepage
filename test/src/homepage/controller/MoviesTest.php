<?php
namespace homepage\controller;

/**
 * Movies webpage test
 */
class MoviesTest extends \ControllerTestCase {

    /**
     * Test movies page.<br />
     * Not very good cache expiry, but if you are with Memcached - should be fine<br />
     * Not good to expire anything anyway. But hey-ho - website is not super serious
     */
    public function testMovies() {
        $this->setUpController("/movies/");
        $this->assertNotEmpty($this->response);
    }
}
