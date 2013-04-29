<?php
$root = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR;
include($root . "config/app.php");
require($root . 'lib/xframe/autoloader/Autoloader.php');

$autoloader = new xframe\autoloader\Autoloader($root);
$autoloader->addPath($root . "script");
$autoloader->register();
