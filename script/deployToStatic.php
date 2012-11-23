<?php
require "StaticPage.php";
require "Page.php";

$page = new Page("http://donatasmart.local/", array(
    "../www/css/" => "css",
    "../www/docs/" => "docs",
    "../www/img/" => "img",
    "../www/js/" => "js"
), "../static/");

$resources = store_page($page, "?DEPLOY");

foreach ($resources as &$resource) {
    if (!in_array(trim($resource, "/"), $page->deployed) && (end(explode(".", $resource)) != "pdf")) {
        $another_resources = store_page($page, trim($resource, "/") . "?DEPLOY");
        
        foreach ($another_resources as &$another) {
            if (!in_array($another, $resources)) {
                $resources[] = $another;
            }
        }
    }
}

$page->checkSymlinks();

/**
 * Store it
 * @param StaticPage $page
 * @param string $resource
 * @return array
 */
function store_page(StaticPage $page, $resource) {
    $page->get($resource);
    $page->save();
    
    return $page->getPageHrefs();
}
