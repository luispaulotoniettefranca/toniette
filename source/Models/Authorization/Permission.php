<?php


namespace Source\Models\Authorization;


use JetBrains\PhpStorm\Pure;
use Toniette\DataLayer\DataLayer;

/**
 * Class Permission
 * @package Source\Models\Authorization
 */
class Permission extends DataLayer
{
    /**
     * Permission constructor.
     */
    #[Pure] public function __construct()
    {
        parent::__construct("permissions", ["route", "method", "handler", "action"]);
    }
}