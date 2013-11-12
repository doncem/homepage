<?php
namespace script\daemon;

/**
 * 
 * @package script_daemon
 */
interface Daemon {

    /**
     * Location to save pid files
     * @return string
     */
    public function getPidPath();

    /**
     * Every daemon must be running ]:)
     */
    public function run();
}
