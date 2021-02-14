<?php


namespace Source\Models;


use JetBrains\PhpStorm\Pure;
use Toniette\DataLayer\DataLayer;

/**
 * Class ApiToken
 * @package Source\Models
 */
class ApiToken extends DataLayer
{

    /**
     * ApiToken constructor.
     */
    #[Pure] public function __construct()
{
    parent::__construct("api_tokens", ["user", "token", "maturity"]);
}

}