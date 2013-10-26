<?php
$loader = require(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR . "autoload.php");

/**
 * Welcome to xFrame. This file is the entry point for the front controller.
 * It registers the autoloader, boots the framework and dispatches the request.
 */
$root = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR;
define("ROOT_DIR", $root);
// Since I don"t have server to modify apache freely, have to do some hacks
include($root . "config/app.php");
// Here we go...

$autoloader = new \xframe\autoloader\Autoloader($root);
//$autoloader->addPath($root . "script");
$autoloader->register();

$system = new \xframe\core\System($root, $_SERVER["CONFIG"]);
$system->boot();

$request = new \xframe\request\Request($_SERVER["REQUEST_URI"], $_REQUEST);
$system->getFrontController()->dispatch($request);
