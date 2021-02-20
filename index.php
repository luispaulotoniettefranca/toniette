<?php

use Source\Models\Authorization\Permission;

require "vendor/autoload.php";

/**
 * CONFIG
 */
$router = new \Toniette\Router\Router(CONF_URL_BASE);

require "routes/api.php";
require "routes/web.php";

/**
 * ERROR ROUTES
 */
$router->group("error");
$router->get("/{errcode}", "Web:error");
$router->get("/{errcode}/{message}", "Web:error");

/**
 * ROUTER DISPATCH
 */
dispatch($router);

/**
 * ERROR ROUTES REDIRECT
 */
if ($router->error()) {
    $router->redirect("error/{$router->error()}");
}