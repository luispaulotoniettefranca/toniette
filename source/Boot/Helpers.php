<?php

/**
 * ####################
 * ###   VALIDATE   ###
 * ####################
 */

use Source\Core\Response;
use Source\Models\User;
use Toniette\Router\Router;
use JetBrains\PhpStorm\NoReturn;
use JetBrains\PhpStorm\Pure;
use Source\Core\Authorization;
use Toniette\Router\Request;
use Source\Core\Session;
use Source\Models\Authorization\Permission;
use Source\Support\Log;

/**
 * @param string $email
 * @return bool
 */
#[Pure] function is_email(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * ###############
 * ###   URL   ###
 * ###############
 */

/**
 * @param string|null $path
 * @return string
 */
function url(string $path = null): string
{
    if ($path) {
        return CONF_URL_BASE . "/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
    }
    return CONF_URL_BASE;
}

/**
 * @param string|null $path
 * @param string $theme
 * @return string
 */
function asset(string $path = null, string $theme = CONF_THEME): string
{
    return CONF_URL_BASE . "/theme/{$theme}" . "/assets/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
}

/**
 * @param string|null $path
 * @param string $theme
 * @return void
 */
function icon(string $path = null, string $theme = CONF_THEME)
{
    include CONF_BASE_DIR . "/theme/{$theme}" .
            "/assets/icons/" . ($path[0] == "/" ? mb_substr($path, 1) : $path) . ".svg";
}


/**
 * @param string|null $url
 */
#[NoReturn] function redirect(string|null $url = null): void
{
    header("HTTP/1.1 302 Redirect");
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        header("Location: {$url}");
        exit;
    }
    $location = url($url);
    header("Location: {$location}");
    exit;
}

/**
 * ################
 * ###   DATE   ###
 * ################
 */

/**
 * @param string $date
 * @param string $format
 * @return string
 * @throws Exception
 */
function date_fmt(string $date = "now", string $format = "d/m/Y H\hi"): string
{
    return (new DateTime($date))->format($format);
}

/**
 * @param string $date
 * @return string
 * @throws Exception
 */
function date_fmt_br(string $date = "now"): string
{
    return (new DateTime($date))->format(CONF_DATE_BR);
}

/**
 * @param string $date
 * @return string
 * @throws Exception
 */
function date_fmt_app(string $date = "now"): string
{
    return (new DateTime($date))->format(CONF_DATE_APP);
}


/**
 * ##################
 * ###   STRING   ###
 * ##################
 */

/**
 * @param string $string
 * @return string
 */
function str_slug(string $string): string
{
    $string = filter_var(mb_strtolower($string), FILTER_SANITIZE_STRIPPED);
    $formats = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]/?;:.,\\\'<>°ºª';
    $replace = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';

    return str_replace(["-----", "----", "---", "--"], "-",
        str_replace(" ", "-",
            trim(strtr(utf8_decode($string), utf8_decode($formats), $replace))
        )
    );
}

/**
 * @param string $string
 * @return string
 */
function str_studly_case(string $string): string
{
    $string = str_slug($string);
    return str_replace(" ", "",
        mb_convert_case(str_replace("-", " ", $string), MB_CASE_TITLE)
    );
}

/**
 * @param string $string
 * @return string
 */
function str_camel_case(string $string): string
{
    return lcfirst(str_studly_case($string));
}

/**
 * @param string $string
 * @return string
 */
function str_title(string $string): string
{
    return mb_convert_case(filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS), MB_CASE_TITLE);
}

/**
 * @param string $string
 * @param int $limit
 * @param string $pointer
 * @return string
 */
#[Pure] function str_limit_words(string $string, int $limit, string $pointer = "..."): string
{
    $string = trim(filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS));
    $arrWords = explode(" ", $string);
    $numWords = count($arrWords);

    if ($numWords < $limit) {
        return $string;
    }

    $words = implode(" ", array_slice($arrWords, 0, $limit));
    return "{$words}{$pointer}";
}

/**
 * @param string $string
 * @param int $limit
 * @param string $pointer
 * @return string
 */
function str_limit_chars(string $string, int $limit, string $pointer = "..."): string
{
    $string = trim(filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS));
    if (mb_strlen($string) <= $limit) {
        return $string;
    }

    $chars = mb_substr($string, 0, mb_strrpos(mb_substr($string, 0, $limit), " "));
    return "{$chars}{$pointer}";
}

/**
 * ################
 * ###   HTTP   ###
 * ################
 */

#[Pure] function response(): Response
{
    return new Response();
}

/**
 * @param array $body
 * @return Request
 */
function request(array $body): Request
{
    return new Request($body);
}

/**
 * #####################
 * ###   "FACADES"   ###
 * #####################
 * @param string $route
 * @param User $user
 * @return Authorization
 */

function authorization(string $route, User $user): Authorization
{
    return new Authorization($route, $user);
}

/**
 * @return Session
 */
function session(): Session
{
    return new Session();
}

/**
 * @return PDO|null
 */
function pdo(): PDO|null
{
    return new PDO(
        DATA_LAYER_CONFIG["driver"] .
        ":host=" . DATA_LAYER_CONFIG["host"] .
        ";dbname=" . DATA_LAYER_CONFIG["dbname"] .
        ";port=" . DATA_LAYER_CONFIG["port"],
        DATA_LAYER_CONFIG["username"],
        DATA_LAYER_CONFIG["passwd"],
        DATA_LAYER_CONFIG["options"]
    );
}


/**
 * @param string $title
 * @param string $description
 * @param array $keywords
 * @param string $image
 * @param string $url
 * @param bool $follow
 * @return string
 */
function seo(string $title, string $description, array $keywords, string $image = CONF_SEO_IMAGE,
             string $url = CONF_URL_BASE, bool $follow = true): string
{
    return (
        new Toniette\Optimizer\Optimizer()
        )->publisher(
            CONF_SEO_FB_PAGE,
            CONF_SEO_FB_AUTHOR
        )->facebook(
            CONF_SEO_FB_APP
        )->openGraph(
            CONF_URL_BASE
        )->twitterCard(
            CONF_SEO_TW_CREATOR,
            CONF_SEO_TW_SITE,
            CONF_URL_BASE
        )->keywords(
            $keywords
        )
        ->optimize(
            $title,
            $description,
            $url,
            $image,
            $follow
        )->render();
}

/**
 * #####################
 * ###   PASSWORDS   ###
 * #####################
 * @param string $pass
 * @return string
 */

function passwd_hash(string $pass): string
{
    return password_hash($pass, CONF_PASSWD_ALGO, CONF_PASSWD_OPTIONS);
}

/**
 * @param $pass
 * @return bool
 */
function passwd_needs_rehash($pass): bool
{
    return password_needs_rehash($pass, CONF_PASSWD_ALGO, CONF_PASSWD_OPTIONS);
}

/**
 * @param string $pass
 * @return bool
 */
function is_passwd(string $pass): bool
{
    if (password_get_info($pass)['algo']) {
        return true;
    }
    return preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*([^a-zA-Z\d\s])).{" . CONF_PASSWD_MIN_LEN . "," . CONF_PASSWD_MAX_LEN . "}$/", $pass);
}

/**
 * #######################
 * ###   PERMISSIONS   ###
 * #######################
 * @param Router $router
 * @var PDO $PDO
 * update permissions and role_permissions tables
 */
function dispatch(Router $router): void
{
    if (CONF_ENVIRONMENT != 'production') {
        $routes = [];
        $root = [];
        foreach ($router->routes as $method) {
            foreach ($method as $key => $value) {
                //UPDATE ROUTES
                $permission = (new Permission())->find("route = :r", [
                        "r" =>  $value["route"]
                    ])->fetch() ?? new Permission();
                $permission->route = $value["route"];
                $permission->method = $value["method"];
                $permission->handler = $value["handler"];
                $permission->action = $value["action"];
                $permission->save();
                $permissions[] = $permission->id;

                //UPDATE ROOT PERMISSIONS
                $rootRole = (new \Source\Models\Authorization\Role())->find("name = :r", [
                    "r" => "root"
                ])->fetch()->id;
                $rootPermission = (new \Source\Models\Authorization\RolePermission())->find(
                    "role = :r AND permission = :p", ["r" => $rootRole, "p" => $permission->id]
                )->fetch() ?? (new \Source\Models\Authorization\RolePermission());
                $rootPermission->role = $rootRole;
                $rootPermission->permission = $permission->id;
                $rootPermission->save();
            }
        }
        // CLEAN OLD REGISTERS
        $permissions = implode(", ", array_filter($permissions, function ($p) {
            return !is_null($p);
        }));
        pdo()->exec("DELETE FROM `role_permissions` WHERE `role` = {$rootRole} AND permission NOT IN ({$permissions})");
        pdo()->exec("DELETE FROM `permissions` WHERE `id` NOT IN ({$permissions})");

    }
    $router->dispatch();
}

/**
 * ##################
 * ###   LOGGER   ###
 * ##################
 * @return Log
 */
function logger(): Log
{
    return new Log();
}

/**
 * #################
 * ###   CACHE   ###
 * #################
 */
function cache(): \Doctrine\Common\Cache\PhpFileCache
{
    return new \Doctrine\Common\Cache\PhpFileCache(CONF_CACHE_PATH);
}

/**
 * ################
 * ###   ENV   ###
 * ###############
 */
function dotenv(): \Dotenv\Dotenv
{
    return Dotenv\Dotenv::createImmutable(CONF_BASE_DIR . "/.env");
}