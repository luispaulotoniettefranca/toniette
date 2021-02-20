<?php


namespace Source\Controllers\Web;


use Toniette\Router\Router;
use Toniette\Uploader\Image;
use JetBrains\PhpStorm\NoReturn;
use Toniette\Router\Request;
use Source\Core\ResourceInterface;
use Source\Models\Authorization\Permission;
use Source\Models\Authorization\UserPermission;

class User extends \Source\Core\Controller implements ResourceInterface
{
    #[NoReturn] public function __construct(Router $router)
    {
        parent::__construct(true, $router);
    }

    public function index(Request $req): void
    {
        $users = (new \Source\Models\User())->find()->fetch(true);
        response()->view("user/index", ["users" => $users],
            seo("Users List", "Users List", ["users"]));
    }

    public function show(Request $req): void
    {
        $req->validate(["key" => FILTER_SANITIZE_NUMBER_INT]);
        $user = (new \Source\Models\User())->findById($req->key);
        if(!$user) {
            redirect("error/404/" . urlencode("Sorry, this user isn't found"));
        }
        response()->view("user/show", ["user" => $user],
            seo("User Details", "User details page", ["user", "details"]));
    }

    public function create(Request $req): void
    {
        $roles = (new \Source\Models\Authorization\Role())->find()->fetch(true);
        response()->view("user/create", ["roles" => $roles],
            seo("User Create", "User Create", ["users", "create", "form"]));
    }

    #[NoReturn] public function store(Request $req): void
    {
        $req->validate([
            "email" => FILTER_SANITIZE_EMAIL,
            "name" => FILTER_SANITIZE_STRING,
            "password" => FILTER_DEFAULT,
            "role" => FILTER_SANITIZE_NUMBER_INT
        ]);

        $user = new \Source\Models\User();

        if($_FILES["image"]["name"]) {
            $image = new Image(CONF_STORAGE_DIR, CONF_STORAGE_IMAGE_DIR);
            try {
                $user->image = $image->upload($_FILES["image"], uniqid($req->name), 400);
            } catch (\Exception $exception) {
                redirect("error/500/".urlencode($exception->getMessage()));
            }
        }

        $user->email = $req->email;
        $user->name = $req->name;
        $user->password = $req->password;
        $user->role = $req->role;

        if (!$user->save()) {
            redirect("error/400/" . urlencode($user->fail()->getMessage()));
        } else if ($user->id == session()->user->id) {
            session()->set("user", [
                "id" => (int)$user->id,
                "name" => $user->name,
                "role" => (int)$user->role,
                "image" => $user->image,
                "maturity" => time()+CONF_SESSION_LIFE_TIME
            ]);
            redirect("admin/user");
        }
    }

    public function edit(Request $req): void
    {
        $req->validate(["key" => FILTER_SANITIZE_NUMBER_INT]);
        $user = (new \Source\Models\User())->findById($req->key);

        $permissions = (new Permission())->find()->fetch(true);

        $authorized = array_map(function ($p) {
            return $p->permission;
        }, ((new UserPermission())->find("user = :u AND status = true",
                ["u" => (int)$user->id], "permission")->fetch(true)) ?? []);

        $unauthorized = array_map(function ($p) {
            return $p->permission;
        }, ((new UserPermission())->find("user = :u AND status = false",
                ["u" => (int)$user->id], "permission")->fetch(true) ?? []));

        $roles = (new \Source\Models\Authorization\Role())->find()->fetch(true);
        response()->view("user/edit", [
            "roles" => $roles,
            "user" => $user,
            "permissions" => $permissions,
            "authorized" => $authorized,
            "unauthorized" => $unauthorized
        ], seo("User Edit", "User Edit", ["users", "edit", "form"]));
    }

    #[NoReturn] public function update(Request $req): void
    {
        $authorized = (isset($req->authorized) ? $req->authorized : []);
        $unauthorized = (isset($req->unauthorized) ? $req->unauthorized : []);

        $req->validate([
            "key" => FILTER_SANITIZE_NUMBER_INT,
            "email" => FILTER_SANITIZE_EMAIL,
            "name" => FILTER_SANITIZE_STRING,
            "role" => FILTER_SANITIZE_NUMBER_INT
        ]);

        $user = (new \Source\Models\User())->findById($req->key);

        if($_FILES["image"]["name"]) {
            $image = new Image(CONF_STORAGE_DIR, CONF_STORAGE_IMAGE_DIR);
            try {
                $upload = $image->upload($_FILES["image"], uniqid($req->name), 400);
                if (is_file(CONF_BASE_DIR
                    . $user->image)) {
                    unlink(CONF_BASE_DIR . $user->image);
                }
                $user->image = $upload;
            } catch (\Exception $exception) {
                redirect("/error/500/".urlencode($exception->getMessage()));
            }
        }

        $user->email = $req->email;
        $user->name = $req->name;
        $user->role = $req->role;

        if (!$user->save()) {
            redirect("error/400/" . urlencode($user->fail()->getMessage()));
        } else if ($user->id == session()->user->id) {
            //RESET SESSION
            session()->set("user", [
                "id" => (int)$user->id,
                "name" => $user->name,
                "role" => (int)$user->role,
                "image" => $user->image,
                "maturity" => time()+CONF_SESSION_LIFE_TIME
            ]);
        }
        //RESET USER PERMISSIONS
        $userPermissions = (new UserPermission())->find("user = :u",
            ["u" => $user->id])->fetch(true);
        if ($userPermissions) {
            foreach ($userPermissions as $up) {
                $up->destroy();
            }
        }

        //CREATE ADDITIONAL ACCESSES
        if ($authorized) {
            foreach ($authorized as $a) {
                $userPermission = new UserPermission();
                $userPermission->user = (int)$user->id;
                $userPermission->permission = (int)$a;
                $userPermission->status = true;
                $userPermission->save();
            }
        }

        //CREATE ADDITIONAL RESTRICTIONS
        if ($unauthorized) {
            foreach ($unauthorized as $u) {
                $userPermission = new UserPermission();
                $userPermission->user = (int)$user->id;
                $userPermission->permission = (int)$u;
                $userPermission->save();
            }
        }

        redirect("admin/user");
    }

    #[NoReturn] public function destroy(Request $req): void
    {
        $req->validate([
            "key" => FILTER_SANITIZE_NUMBER_INT,
        ]);
        $user = (new \Source\Models\User())->findById($req->key);
        if ($user) {
            if (is_file(CONF_BASE_DIR . $user->image)) {
                unlink(CONF_BASE_DIR . $user->image);
            }
            $userPermissions = (new UserPermission())->find("user = :u",
                ["u" => $user->id])->fetch(true);
            foreach ($userPermissions as $userPermission) {
                $userPermission->destroy();
            }
        }
        $user->destroy();
        redirect("admin/user");
    }

    public function passwordEdit(Request $req): void
    {
        $req->validate(["key" => FILTER_SANITIZE_NUMBER_INT]);
        $user = (new \Source\Models\User())->findById($req->key);
        response()->view("user/password_edit", ["user" => $user], seo("Password Edit",
            "Edit User Password", ["new", "password"]));
    }

    #[NoReturn] public function passwordUpdate(Request $req): void
    {
        $req->validate([
            "key" => FILTER_SANITIZE_NUMBER_INT,
            "password" => FILTER_DEFAULT
        ]);
        $user = (new \Source\Models\User())->findById($req->key);
        $user->password = $req->password;
        if (!$user->save()) {
            redirect("error/400/" . urlencode($user->fail()->getMessage()));
        } else {
            redirect("admin/user");
        }
    }
}