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
     * @param Request $req
     * @return bool
     */
    public static function login(Request $req): bool
    {
        $req->validate([
            "email" => FILTER_SANITIZE_EMAIL,
            "password" => FILTER_DEFAULT
        ]);
        $user = (new User())->find("email = :email", [
            "email" => $req->email
        ])->fetch();
        if($user) {
            if (password_verify($req->password, $user->password)) {
                if (passwd_needs_rehash($user->password)) {
                    $newUser = (new User())->findById($user->id);
                    $newUser->password = passwd_hash($req->password);
                    $newUser->save();
                }
                if (session()->has("attempts")) {
                    session()->unset("attempts");
                    session()->unset("invalid_credentials");
                }
                session()->set("user", [
                    "id" => (int)$user->id,
                    "name" => $user->name,
                    "role" => (int)$user->role,
                    "image" => $user->image,
                    "maturity" => time()+CONF_SESSION_LIFE_TIME
                ]);
                logger()->info("AN USER HAS BEEN SIGN IN", [
                    "USER" => session()->user,
                    "REQUEST" => $req(),
                ]);
                return true;
            } else {
                if (!session()->has("invalid_credentials")) {
                    session()->set("invalid_credentials", []);
                }
                session()->invalid_credentials[] = ["email" => $req->email, "password" => $req->password];
                session()->set("attempts", (session()->has("attempts") ? session()->attempts + 1 : 1));
                if (session()->attempts >= CONF_ATTEMPT_LIMIT) {
                    logger()->emergency("SUSPICIOUS LOGIN ATTEMPT BEHAVIOR", [
                        "REQUEST" => $req(),
                        "ATTEMPTS NUMBER" => session()->attempts,
                        "CREDENTIALS" => session()->invalid_credentials
                    ]);
                    session()->set("block", time() + CONF_SESSION_BLOCK_TIME);
                }
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