<?php


namespace Source\Core;


use Source\Models\User;
use Toniette\Router\Request;

/**
 * Class Authentication
 * @package Source\Core
 */
class Authentication extends Controller
{

    /**
     * @param Request $request
     * @return bool
     */
    public static function login(Request $request): bool
    {
        $request->validate([
            "email" => FILTER_SANITIZE_EMAIL,
            "password" => FILTER_DEFAULT
        ]);
        $user = (new User())->find("email = :email", [
            "email" => $request->email
        ])->fetch();
        if (password_verify($request->password, $user->password)) {
            if (passwd_needs_rehash($user->password)) {
                $newUser = (new User())->findById($user->id);
                $newUser->password = passwd_hash($request->password);
                $newUser->save();
            }
            if (session()->has("attempts")) {
                session()->unset("attempts");
            }
            session()->set("user", [
                "id" => (int)$user->id,
                "name" => $user->name,
                "role" => (int)$user->role,
                "image" => $user->image,
                "maturity" => time()+CONF_SESSION_LIFE_TIME
            ]);
            return true;
        } else {
            session()->set("attempts", (session()->has("attempts") ? session()->attempts + 1 : 1));
            if (session()->attempts >= CONF_ATTEMPT_LIMIT) {
                session()->set("block", time() + CONF_SESSION_BLOCK_TIME);
            }
        }
        return false;
    }

    /**
     * @return User|null
     */
    public static function user(): ?User
    {
        if (session()->has("user")) {
            return ((new User())->findById(session()->user->id));
        }
        return null;
    }

    /**
     *
     */
    public static function logout(): void
    {
        if (session()->has("user")) {
            session()->destroy();
        }
    }

}