<?php


namespace Source\Controllers\Web;


use Toniette\Router\Router;
use JetBrains\PhpStorm\NoReturn;
use Source\Core\Authentication;
use Toniette\Router\Request;

/**
 * Class Admin
 * @package Source\Controllers\Web
 */
class Admin extends \Source\Core\Controller
{
    /**
     * Admin constructor.
     * @param Router $req
     */
    #[NoReturn] public function __construct(Router $req)
    {
        if (!Authentication::user()) {
            redirect("/login");
        }
        parent::__construct(true, $req);
    }

    /**
     * @param Request $req
     */
    public function index(Request $req): void
    {
        response()->view("admin/home");
    }
}