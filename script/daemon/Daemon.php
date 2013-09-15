<?php
namespace script\daemon;

/**
 * 
 */
interface Daemon {

    /**
     * Location to save pid files
     * @return string
     */
    public function getPidPath();

    public function run();
}
