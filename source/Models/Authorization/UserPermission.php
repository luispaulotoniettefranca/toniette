<?php


namespace Source\Models\Authorization;


use JetBrains\PhpStorm\Pure;
use Toniette\DataLayer\DataLayer;

/**
 * Class UserPermission
 * @package Source\Models\Authorization
 */
class UserPermission extends DataLayer
{
    /**
     * UserPermission constructor.
     */
    #[Pure] public function __construct()
    {
        parent::__construct("user_permissions", ["user", "permission"]);
    }
}