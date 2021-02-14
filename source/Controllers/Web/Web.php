<?php


namespace Source\Controllers\Web;

use Toniette\Router\Router;
use JetBrains\PhpStorm\NoReturn;
use Source\Core\Authentication;
use Toniette\Router\Request;

/**
 * Class Web
 * @package Source\Controllers
 */
class Web extends \Source\Core\Controller
{

    /**
     * Web constructor.
     * @param Router $req
     */
    #[NoReturn] public function __construct(Router $req)
    {
        parent::__construct(false, $req);
    }

    /**
     * @param Request $req
     */
    public function home(Request $req): void
    {
        response()->view(seo: seo("Home", "Default home page", ["home", "public"]));
    }

    public function login(): void
    {
        if(session()->attempts >= CONF_ATTEMPT_LIMIT) {
            redirect("error/401/" . urlencode("Login attempts limit reached. Try again later"));
        }
        response()->view("login",
            seo: seo("Login", "login page", ["login", "security"]));
    }

    #[NoReturn] public function authenticate(Request $req): void
    {
        if (Authentication::login($req)) {
            redirect("admin");
        } else {
            redirect("error/401/" . urlencode("Wrong credentials. Please, try again"));
        }
    }

    #[NoReturn] public function logout(): void
    {
        Authentication::logout();
        redirect();
    }

    /**
     * @param Request $req
     */
    #[NoReturn] public function error(Request $req): void
    {
        $req->validate([
            "errcode" => FILTER_SANITIZE_NUMBER_INT,
            "message" => FILTER_SANITIZE_STRING
        ]);
        response()->view('error', ["errcode" => $req->errcode, "message" => ($req->message ?? null)],
            seo("ERROR | " . $req->errcode,
                "ERROR | " . $req->errcode, [], CONF_SEO_ERROR_IMAGE));
    }

}