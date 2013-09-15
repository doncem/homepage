<?php
// Ensure library/ is on include_path
$include = array(realpath("../lib"), get_include_path());
set_include_path(implode(PATH_SEPARATOR, $include));

use \xframe\autoloader\Autoloader;
use \xframe\core\System;
use \xframe\request\Request;

/**
 * Welcome to xFrame. This file is the entry point for the front controller.
 * It registers the autoloader, boots the framework and dispatches the request.
 */

$root = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR;
define("ROOT_DIR", $root);
// Since I don"t have server to modify apache freely, have to do some hacks
include($root . "config/app.php");
// Here we go...
require("xframe/autoloader/Autoloader.php");

$autoloader = new Autoloader($root);
//$autoloader->addPath($root . "script");
$autoloader->register();

$system = new System($root, $_SERVER["CONFIG"]);
$system->boot();

$request = new Request($_SERVER["REQUEST_URI"], $_REQUEST);
$system->getFrontController()->dispatch($request);
