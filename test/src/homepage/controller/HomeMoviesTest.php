<?php
namespace homepage\controller;

/**
 * Movies webpage test
 */
class HomeMoviesTest extends \ControllerTestCase {

    /**
     * Test movies page.<br />
     * Not very good cache expiry, but if you are with Memcached - should be fine<br />
     * Not good to expire anything anyway. But hey-ho - website is not super serious
     */
    public function testMovies() {
        \MemcacheHandler::getHandler($this->getDIC())->expire(
            \MemcacheNamespaces::NAMESPACE_PAGE,
            \MemcacheNamespaces::KEY_MOVIES_DATA
        );

        $this->setUpController("/movies/");
        $this->assertNotEmpty($this->response);

        $this->assertTrue(\MemcacheHandler::getHandler($this->getDIC())->isConnected());

        $this->setUpController("/movies");
        $this->assertNotEmpty($this->response);

        $status = \MemcacheHandler::getHandler($this->getDIC())->get_status();
        $this->assertArrayHasKey(
            $this->getDIC()->registry->get("MEMCACHE_HOST") . ":" . $this->getDIC()->registry->get("MEMCACHE_PORT"),
            $status
        );
    }
}
