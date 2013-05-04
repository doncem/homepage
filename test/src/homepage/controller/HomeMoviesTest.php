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
        // dummy DependencyInjectionContainer
        $dic = new \xframe\core\DependencyInjectionContainer();
        $registry = parse_ini_file(__DIR__ . "/../../../../config/" . CONFIG . ".ini");

        \MemcacheHandler::getHandler($dic)->expire(
            \MemcacheNamespaces::NAMESPACE_PAGE,
            \MemcacheNamespaces::KEY_MOVIES_DATA
        );

        $this->setUpController("/movies/");
        $this->assertNotEmpty($this->response);

        $this->assertTrue(\MemcacheHandler::getHandler($dic)->isConnected());

        $this->setUpController("/movies");
        $this->assertNotEmpty($this->response);

        $status = \MemcacheHandler::getHandler($dic)->get_status();
        $this->assertArrayHasKey($registry["MEMCACHE_HOST"] . ":" . $registry["MEMCACHE_PORT"], $status);
    }
}
