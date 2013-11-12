<?php
namespace script\gearman\config;

/**
 * Config for testing
 * @package script_gearman_config
 */
class Test extends \script\gearman\Config {

    /**
     * Attach Gearman worker and client closures
     */
    public function __construct() {
        parent::__construct("127.0.0.1");
    }
}
