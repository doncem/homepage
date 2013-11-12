<?php
$root = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR;
$loader = require($root . "vendor" . DIRECTORY_SEPARATOR . "autoload.php");

/**
 * Welcome to xFrame. This file is the entry point for the front controller.
 * It registers the autoloader, boots the framework and dispatches the request.
 */
define("ROOT_DIR", $root);
// Since I don"t have server to modify apache freely, have to do some hacks
include($root . "config/app.php");
// Here we go...

$autoloader = new \xframe\autoloader\Autoloader($root);
$autoloader->register();

$system = new \xframe\core\System($root, $_SERVER["CONFIG"]);
$system->boot();

$request = new \xframe\request\Request(filter_input(INPUT_SERVER, "REQUEST_URI"), $_REQUEST);
$system->getFrontController()->dispatch($request);
