<?php

/** @var Router $router */

use Toniette\Router\Router;

$router->group(null);
$router->namespace("Source\Controllers\Web");

/**
 * PUBLIC ROUTES
 */
$router->get("/", "Web:home");
$router->get("/login", "Web:login");
$router->post("/authenticate", "Web:authenticate");
$router->get("/logout", "Web:logout");


/**
 * PROTECTED ROUTES
 */
$router->group("admin");
    $router->get("/", "Admin:index");

    $router->resource("User");
        $router->get("/user/{key}/password/edit", "User:passwordEdit");
        $router->post("/user/{key}/password/update", "User:passwordUpdate");

    $router->resource("Role");


$router->group(null);