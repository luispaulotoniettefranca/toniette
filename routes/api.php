<?php

/** @var Router $router */

use Toniette\Router\Router;

$router->group(null);
$router->namespace("Source\Controllers\Api");

/**
 * PUBLIC ROUTES
 */
$router->group('api');
$router->get('/', 'Api:index');

/**
 * PROTECTED ROUTES
 */