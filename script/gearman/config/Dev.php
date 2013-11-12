<?php
namespace script\gearman\config;

/**
 * Config for development
 * @package script_gearman_config
 */
class Dev extends \script\gearman\Config {

    /**
     * Attach Gearman worker and client closures
     */
    public function __construct() {
        parent::__construct("127.0.0.1");
    }
}
