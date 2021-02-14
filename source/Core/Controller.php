<?php


namespace Source\Core;

use Toniette\Router\Router;
use JetBrains\PhpStorm\NoReturn;


/**
 * Class Controller
 * @package Source\Core
 */
abstract class Controller
{

    /**
     * Controller constructor.
     * @param bool $auth
     * @param Router $router
     */
    #[NoReturn] public function __construct(bool $auth, Router $router)
    {
        if ($auth && !Authentication::user() && !ApiAuthentication::user()) {
            redirect("/error/401");
        } elseif ($auth) {
            authorization($router->route["route"],
                (Authentication::user() ?? ApiAuthentication::user()));
        }
    }

}