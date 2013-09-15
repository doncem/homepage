<?php
namespace script\gearman\config;

class Dev extends \script\gearman\Config {

    public function __construct() {
        parent::__construct("127.0.0.1");
    }
}
