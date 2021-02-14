<?php


namespace Source\Models\Authorization;


use JetBrains\PhpStorm\Pure;
use Toniette\DataLayer\DataLayer;

/**
 * Class RolePermission
 * @package Source\Models\Authorization
 */
class RolePermission extends DataLayer
{
    #[Pure] public function __construct()
    {
        parent::__construct("role_permissions", ["role", "permission"]);
    }
}