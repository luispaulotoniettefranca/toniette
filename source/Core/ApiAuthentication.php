<?php


namespace Source\Core;


use Source\Models\ApiToken;
use Source\Models\User;
use Toniette\Router\Request;

/**
 * Class ApiAuthentication
 * @package Source\Core
 */
class ApiAuthentication extends Controller
{
    /**
     * @param Request $request
     * @return string|bool
     */
    public static function getToken(Request $request): string|bool
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
            $token = (new ApiToken())->find("user = :user",
                ["user" => $user->id])->fetch() ?? new ApiToken();
            if (is_null($token->data())) {
                $token->user = $user->id;
                $token->token = "T0n13tt3-".base64_encode(passwd_hash((uniqid()."{$user->id}".time())));
                $token->maturity = (new \DateTime("now"))
                    ->add((new \DateInterval("P1D")))->format(CONF_DATE_APP);
                $token->save();
                return $token->token;
            }
            if (strtotime($token->maturity) < strtotime("now")) {
                $token->token = "T0n13tt3-".base64_encode(passwd_hash((uniqid()."{$user->id}".time())));
                $token->maturity = (new \DateTime("now"))
                    ->add((new \DateInterval("P1D")))->format(CONF_DATE_APP);
                $token->save();
                return $token->token;
            }
            return $token->token;
        }
        return false;
    }

    /**
     * @return User|null
     */
    public static function user(): User|null
    {
        $user = (new User())->findById((((new ApiToken())->find("token = :token", [
            "token" => str_replace("Bearer ", "", (new Request())->header("Authorization"))
            ])->fetch() ?? 0)));
        if (!$user || strtotime($user->maturity) < time()) {
            return null;
        }
        return $user;
    }

}