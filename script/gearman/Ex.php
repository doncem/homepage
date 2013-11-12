<?php
namespace script\gearman;

/**
 * Exceptions for Gearman server
 * @package script_gearman
 */
class Ex extends \Exception {
    const CONNECTION_ERROR = 0;
    const NO_CONFIGURATION_FOUND = 1;

    /**
     * Create a connection exception
     * @param string $message
     * @param int $code
     */
    public function __construct($message, $code) {
        parent::__construct($message, $code, $this->getPrevious());
    }
}
