<?php

/**
 * Deployment test
 */
class DeployToStaticTest extends \PHPUnit_Framework_TestCase {

    /**
     * Available live menu links
     * @var array
     */
    private $live_menu = array(
        "/",
        "/about",
        "/hell-music",
        "/experiments",
        "/movies"
    );

    /**
     * Clear the /static/ folder - run deployment script
     */
    protected function setUp() {
        $this->removeDir(__DIR__ . "/../../static/");

        $deployment = new DeployToStatic();
        $deployment->run();
    }

    /**
     * Path clear up. except it's origin (because we are leaving .gitignore file)
     * @param string $dir
     */
    private function removeDir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);

            foreach ($objects as $object) {
                if ($object != "." && $object != ".." && $object != ".gitignore") {
                    if (filetype($dir . "/" . $object) == "dir") {
                        $this->removeDir($dir."/".$object);
                    } else if (!is_link($dir . "/" . $object)) {
                        unlink($dir . "/" . $object);
                    }
                }
            }

            reset($objects);
        }
    }

    /**
     * Test how did it go
     */
    public function testDeployment() {
        foreach ($this->live_menu as $link) {
            $this->assertFileExists(__DIR__ . "/../../static" . $link . "/index.html");
        }

        foreach (Page::getSymlinks() as $link) {
            $this->assertTrue(is_link(__DIR__ . "/../../static/" . $link));
        }
    }
}
