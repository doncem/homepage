<?php
namespace script\gearman\config;

/**
 * Config for live environment
 * @package script_gearman_config
 */
class Live extends \script\gearman\Config {

    /**
     * Attach Gearman worker and client closures
     */
    public function __construct() {
        parent::__construct("");
    }
}
