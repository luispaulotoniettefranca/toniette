<?php

namespace Source\Controllers\Web;


use mysql_xdevapi\Exception;
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
        $roles = (new \Source\Models\Authorization\Role())->find("name != :r", [
            "r" => "root"
        ])->fetch(true);
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
            logger()->error("ROLE NOT FOUND", [
                "AGENT" => session()->user,
                "REQUEST" => $req(),
            ]);
            redirect("error/404/" . urlencode("Can't show role details. Role not found"));
        }
        $rolePermissions = (new RolePermission())->find("role = :r",
            ["r" => $role->id], "permission")->fetch(true);
        if (!$rolePermissions) {
            logger()->error("ROLE PERMISSIONS NOT FOUND", [
                "AGENT" => session()->user,
                "REQUEST" => $req(),
            ]);
            redirect("error/404/" . urlencode("Can't show role details. Role permission(s) not found"));
        }
        $permissions = [];
        foreach ($rolePermissions as $rp) {
            $permission = (new Permission())->findById($rp->permission);
            if (!$permission) {
                logger()->error("ROLE PERMISSION(S) NOT FOUND", [
                    "AGENT" => session()->user,
                    "REQUEST" => $req(),
                ]);
                redirect("error/404/" . urlencode("Can't show role details. Role permission(s) not found"));
            }
            $permissions[] = $permission;
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
        if (!$permissions) {
            logger()->error("PERMISSIONS NOT FOUND", [
                "AGENT" => session()->user,
                "REQUEST" => $req(),
            ]);
            redirect("error/404/" . urlencode("Can't create role. Permissions not found"));
        }
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
                "AGENT" => session()->user,
                "REQUEST" => $req(),
            ]);
            redirect("error/400/" . urlencode("Can't store. Permissions field is required"));
        }
        if (!$role->save()) {
            logger()->error("CAN'T STORE ROLE", [
                "AGENT" => session()->user,
                "REQUEST" => $req(),
                "ERROR" => $role->fail()
            ]);
            redirect("error/400/" . urlencode($role->fail()->getMessage()));
        } else {
            foreach ($perm as $p) {
                $rolePermission = new RolePermission();
                $rolePermission->role = $role->id;
                $rolePermission->permission = (int)$p;
                if (!$rolePermission->save()) {
                    logger()->error("CAN'T STORE ROLE PERMISSION(S)", [
                        "AGENT" => session()->user,
                        "REQUEST" => $req(),
                        "ERROR" => $rolePermission->fail()
                    ]);
                    redirect("error/404/" . urlencode($rolePermission->fail()->getMessage()));
                }
            }
        }
        logger()->info("AN NEW ROLE HAS BEEN STORED", [
            "AGENT" => session()->user,
            "REQUEST" => $req(),
            "ROLE" => $role->data(),
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
            logger()->error("ROLE NOT FOUND", [
                "AGENT" => session()->user,
                "REQUEST" => $req(),
            ]);
            redirect("error/404/" . urlencode("Can't edit. Role not found"));
        }
        $rolePermissions = (new RolePermission())->find("role = :r",
            ["r" => (int)$role->id], "permission")->fetch(true);
        if (!$rolePermissions) {
            logger()->error("ROLE PERMISSIONS NOT FOUND", [
                "AGENT" => session()->user,
                "REQUEST" => $req(),
            ]);
            redirect("error/404/" . urlencode("Can't edit role. Role permissions not found"));
        }
        $permissions = (new Permission())->find()->fetch(true);
        if(!$permissions) {
            logger()->error("PERMISSIONS NOT FOUND", [
                "AGENT" => session()->user,
                "REQUEST" => $req(),
            ]);
            redirect("error/404/" . urlencode("Can't edit role. Permissions not found"));
        }
        $authorized = [];
        foreach ($rolePermissions as $rp) {
            $auth = (new Permission())->findById($rp->permission);
            if (!$auth) {
                logger()->error("ROLE PERMISSION(S) NOT FOUND", [
                    "AGENT" => session()->user,
                    "REQUEST" => $req(),
                ]);
                redirect("error/404/" . urlencode("Can't edit role. Permission(s) not found"));
            }
            $authorized[] = $auth;
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
        if (!$role) {
            logger()->error("ROLE NOT FOUND", [
                "AGENT" => session()->user,
                "REQUEST" => $req(),
            ]);
            redirect("error/404/" . urlencode("Can't update. Role not found"));
        }
        $oldRole = $role;
        $role->name = $req->name;
        if (!$role->save()) {
            logger()->error("ROLE NOT FOUND", [
                "AGENT" => session()->user,
                "REQUEST" => $req(),
            ]);
            redirect("error/404/" . urlencode("Can't update. Role not found"));
        } else {
            logger()->info("AN ROLE HAS BEEN UPDATED", [
                "AGENT" => session()->user,
                "REQUEST" => $req(),
                "OLD_ROLE" => $oldRole->data(),
                "NEW_ROLE" => $role->data(),
            ]);
        }
        try {
            pdo()->exec("DELETE FROM `role_permissions` WHERE `role` = {$req->key}");
        } catch (Exception $exception) {
            logger()->error("CAN'T STORE ROLE PERMISSION ON UPDATE", [
                "AGENT" => session()->user,
                "REQUEST" => $req(),
                "ERROR" => $exception
            ]);
            redirect("error/400/" . urlencode($exception->getMessage()));
        }

        if ($perm) {
            foreach ($perm as $p) {
                $rolePermission = new RolePermission();
                $rolePermission->role = $role->id;
                $rolePermission->permission = (int)$p;
                if(!$rolePermission->save()) {
                    logger()->error("CAN'T STORE ROLE PERMISSION ON UPDATE", [
                        "AGENT" => session()->user,
                        "REQUEST" => $req(),
                        "ERROR" => $rolePermission->fail()
                    ]);
                    redirect("error/400/" . urlencode($rolePermission->fail()->getMessage()));
                }
                logger()->info("AN ROLE PERMISSION HAS BEEN UPDATED", [
                    "AGENT" => session()->user,
                    "REQUEST" => $req(),
                    "PERMISSION" => $rolePermission->data()
                ]);
            }
        } else {
            logger()->error("ATTEMPT TO UPDATE ROLE WITHOUT PERMISSIONS", [
                "AGENT" => session()->user,
                "REQUEST" => $req(),
            ]);
            redirect("error/400/" . urlencode("Can't store. Permissions field is required"));
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
        try {
            pdo()->exec("DELETE FROM `role_permissions` WHERE `role` = {$req->key}");
        } catch (\Exception $exception) {
            logger()->error("CAN'T DELETE ROLE PERMISSIONS", [
                "AGENT" => session()->user,
                "REQUEST" => $req(),
                "ERROR" => $exception
            ]);
            redirect("error/400/" . urlencode($exception->getMessage()));
        }


        // DESTROY users
        try {
            pdo()->exec("DELETE FROM `users` WHERE `role` = {$req->key}");
        } catch (\Exception $exception) {
            logger()->error("CAN'T DELETE USERS BY ROLE", [
                "AGENT" => session()->user,
                "REQUEST" => $req(),
                "ERROR" => $exception
            ]);
            redirect("error/400/" . urlencode($exception->getMessage()));
        }


        // DESTROY role
        $role = (new \Source\Models\Authorization\Role())->findById($req->key);
        if (!$role) {
            logger()->error("ROLE NOT FOUND FOR DELETE", [
                "AGENT" => (array)session()->user,
                "REQUEST" => $req(),
            ]);
            redirect("error/404/" . urlencode("Can't Delete. Role not found"));
        }
        $oldRole = $role;
        if (!$role->destroy()) {
            logger()->error("CAN'T UPDATE USER", [
                "AGENT" => (array)session()->user,
                "REQUEST" => $req(),
                "ERROR" => $role->fail()
            ]);
            redirect("error/400/" . urlencode($role->fail()->getMessage()));
        }
        logger()->info("AN ROLE HAS BEEN DELETED", [
            "AGENT" => (array)session()->user,
            "REQUEST" => $req(),
            "USER" => $oldRole->data()
        ]);
        redirect("admin/role");
    }
}