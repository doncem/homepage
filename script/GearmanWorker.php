<?php
$include = array(realpath("../lib"), get_include_path());
set_include_path(implode(PATH_SEPARATOR, $include));

use \xframe\autoloader\Autoloader;

include __DIR__ . "/../config/app.php";
require_once __DIR__ . "/../lib/xframe/autoloader/Autoloader.php";

$autoloader = new Autoloader(__DIR__ . "/../");
$autoloader->register();

$config_class = "\\script\\gearman\\config\\" . ucfirst(CONFIG);
$config = new $config_class();

$daemon = new \script\daemon\PHPDaemon($config->gearman_worker);
$daemon->daemonize();
