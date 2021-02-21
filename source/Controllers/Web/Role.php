<?php

namespace Source\Controllers\Web;


use Source\Core\ResourceInterface;
use Toniette\Router\Router;
use JetBrains\PhpStorm\NoReturn;
use Toniette\Router\Request;
use Source\Models\Authorization\Permission;
use Source\Models\Authorization\RolePermission;

class Role extends \Source\Core\Controller implements ResourceInterface
{
    #[NoReturn] public function __construct(Router $router)
    {
        parent::__construct(true, $router);
    }

    /**
     * @inheritDoc
     */
    public function index(Request $req): void
    {
        $roles = (new \Source\Models\Authorization\Role())->find()->fetch(true);
        response()->view("role/index", ["roles" => $roles],
            seo("Roles List", "Roles list page", ["roles", "list", "index"]));
    }

    /**
     * @inheritDoc
     */
    public function show(Request $req): void
    {
        $req->validate(["key" => FILTER_SANITIZE_NUMBER_INT]);
        $role = (new \Source\Models\Authorization\Role())->findById($req->key);
        if (!$role) {
            logger()->error("ROLE NOT FOUND", ["\$_SERVER" => $_SERVER, "Request" => $req]);
            redirect("error/404/" . urlencode("Role not found"));
        }
        $rolePermissions = (new RolePermission())->find("role = :r",
            ["r" => $role->id], "permission")->fetch(true);
        $permissions = [];
        foreach ($rolePermissions as $rp) {
            $permissions[] = (new Permission())->findById($rp->permission);
        }
        response()->view("role/show",
            ["role" => $role, "permissions" => $permissions],
            seo("Role Details", "Role details page", ["role", "details"]));
    }

    /**
     * @inheritDoc
     */
    public function create(Request $req): void
    {
        $permissions = (new Permission())->find()->fetch(true);
        response()->view("role/create", ["permissions" => $permissions],
            seo("Create Role", "Role creation page", ["role", "create"]));
    }

    /**
     * @inheritDoc
     */
    #[NoReturn] public function store(Request $req): void
    {
        $perm = $req->permissions;
        $req->validate([
            "name" => FILTER_SANITIZE_STRING
        ]);
        $role = new \Source\Models\Authorization\Role();
        $role->name = $req->name;
        if (!$perm) {
            logger()->error("ATTEMPT TO STORE ROLE WITHOUT PERMISSIONS", [
                "USER" => (array)session()->user,
                "REQUEST" => $req(),
                "ERROR" => "Permissions field is required"
            ]);
            redirect("error/400/" . urlencode("Permissions field is required"));
        }
        if (!$role->save()) {
            logger()->error("ERROR SAVING ROLE", [
                "USER" => (array)session()->user,
                "REQUEST" => $req(),
                "ERROR" => $role->fail()
            ]);
            redirect("error/400/" . urlencode($role->fail()->getMessage()));
        } else {
            foreach ($perm as $p) {
                $rolePermission = new RolePermission();
                $rolePermission->role = $role->id;
                $rolePermission->permission = (int)$p;
                $rolePermission->save();
            }
        }
        logger()->debug("NEW ROLE STORED", [
            "USER" => (array)session()->user,
            "REQUEST" => $req(),
            "ROLE" => (array)$role->data(),
        ]);
        redirect("admin/role");
    }

    /**
     * @inheritDoc
     */
    public function edit(Request $req): void
    {
        $req->validate(["key" => FILTER_SANITIZE_NUMBER_INT]);
        $role = (new \Source\Models\Authorization\Role())->findById($req->key);
        if (!$role) {
            logger()->error("ROLE NOT FOUND FOR EDITING", []);
            redirect("error/404/" . urlencode("Role not found"));
        }
        $rolePermissions = (new RolePermission())->find("role = :r",
            ["r" => (int)$role->id], "permission")->fetch(true);
        $permissions = (new Permission())->find()->fetch(true);
        $authorized = [];
        foreach ($rolePermissions as $rp) {
            $authorized[] = (new Permission())->findById($rp->permission);
        }
        response()->view("role/edit",
            ["role" => $role, "authorized" => $authorized, "permissions" => $permissions],
            seo("Role Edit", "Role edit page", ["role", "edit"]));
    }

    /**
     * @inheritDoc
     */
    #[NoReturn] public function update(Request $req): void
    {
        $perm = $req->permissions;
        $req->validate([
            "key" => FILTER_SANITIZE_NUMBER_INT,
            "name" => FILTER_SANITIZE_STRING
        ]);

        $role = (new \Source\Models\Authorization\Role())->findById($req->key);

        $role->name = $req->name;
        $role->save();

        $oldPerm = (new RolePermission())->find("role = :r", ["r" => $req->key])->fetch(true);
        foreach ($oldPerm as $old) {
            $old->destroy();
        }

        foreach ($perm as $p) {
            $rolePermission = new RolePermission();
            $rolePermission->role = $role->id;
            $rolePermission->permission = (int)$p;
            $rolePermission->save();
        }

        redirect("admin/role");

    }

    /**
     * @inheritDoc
     */
    #[NoReturn] public function destroy(Request $req): void
    {
        $req->validate(["key" => FILTER_SANITIZE_NUMBER_INT]);

        // DESTROY role/permissions relation
        $rp = new RolePermission();
        $rolePermissions = $rp->find("role = :r", ["r" => $req->key])->fetch(true);
        if ($rolePermissions) {
            foreach ($rolePermissions as $rope) {
                $rope->destroy();
            }
        }

        // DESTROY users
        $user = new \Source\Models\User();
        $users = $user->find("role = :r", ["r" => $req->key])->fetch(true);
        if ($users) {
            foreach ($users as $u) {
                $u->destroy();
            }
        }

        // DESTROY role
        $role = (new \Source\Models\Authorization\Role())->findById($req->key);
        $role->destroy();

        redirect("admin/role");

    }
}