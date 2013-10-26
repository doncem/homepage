<?php
$root = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR;
define("CONFIG", session_name() == "test" ? "test" : "dev");
require($root . "vendor/autoload.php");

$autoloader = new xframe\autoloader\Autoloader($root);
$autoloader->addPath($root . "script");
$autoloader->register();
