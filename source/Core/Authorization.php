<?php


namespace Source\Core;


use JetBrains\PhpStorm\NoReturn;
use Source\Models\Authorization\Permission;
use Source\Models\Authorization\RolePermission;
use Source\Models\Authorization\UserPermission;
use Source\Models\User;

/**
 * Class Authorization
 * @package Source\Core
 */
class Authorization
{

    /**
     * @var array
     */
    private $role_permissions;
    /**
     * @var array
     */
    private $user_permissions;

    /**
     * Authorization constructor.
     * @param string $route
     * @param User $user
     */
    #[NoReturn] public function __construct(string $route, User $user)
    {
        $permission = (new Permission())->find("route = :r", [
            "r" => $route
        ])->fetch()->id;

        $this->role_permissions = (new RolePermission())->find("role = :role", [
            "role" => $user->role
        ])->fetch(true);

        $this->user_permissions = (new UserPermission())->find("user = :user", [
            "user" => $user->id
        ])->fetch(true);

        if (!is_null($this->user_permissions)) {
            foreach ($this->user_permissions as $user_permission) {
                if ($user_permission->permission == $permission) {
                    if ($user_permission->status) {
                        return true;
                    } else {
                        redirect("/error/403");
                    }
                }
            }
        }

        if (!is_null($this->role_permissions)) {
            foreach ($this->role_permissions as $role_permission) {
                if ($role_permission->permission == $permission) {
                    return true;
                }
            }
            redirect("/error/403");
        }
        return false;
    }
}