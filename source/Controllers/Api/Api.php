<?php


namespace Source\Controllers\Api;


use JetBrains\PhpStorm\NoReturn;
use Toniette\Router\Router;

class Api extends \Source\Core\Controller
{
    #[NoReturn] public function __construct(Router $router)
    {
        parent::__construct(false, $router);
    }

    public function index(): void
    {
        response()->json([
            "project" => [
                "name" => "Toniette Framework",
                "version" => "0.0.1",
                "release_date" => "2021-01-07"],
            "author" => [
                "name" => "Luís Paulo Toniette França",
                "email" => "luis_ti@outlook.com",
                "phone" => "+55 (43) 9 9672-3871",
                "role" => "Software Engineer"]
        ]);
    }
}