<?php
$root = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR;
$loader = require($root . "vendor" . DIRECTORY_SEPARATOR . "autoload.php");
$config = filter_input(INPUT_SERVER, "CONFIG") ? filter_input(INPUT_SERVER, "CONFIG") : "live";

/**
 * Welcome to xFrame. This file is the entry point for the front controller.
 * It registers the autoloader, boots the framework and dispatches the request.
 */
define("ROOT_DIR", $root);

$autoloader = new \xframe\autoloader\Autoloader($root);
$autoloader->register();

$system = new \xframe\core\System(
    $root,
    (strpos(filter_input(INPUT_SERVER, "REQUEST_URI"), "?DEPLOY") !== false ? "test" : $config)
);
$system->boot();

$request = new \xframe\request\Request(filter_input(INPUT_SERVER, "REQUEST_URI"), $_REQUEST);
$system->getFrontController()->dispatch($request);
