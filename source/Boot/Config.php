<?php

use Toniette\Router\Request;


/**
 * DATABASE
 */
define("DATA_LAYER_CONFIG", [
    "driver" => "mysql",
    "host" => "localhost",
    "port" => "3306",
    "dbname" => "development_db",
    "username" => "root",
    "passwd" => "",
    "options" => [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_CASE => PDO::CASE_NATURAL
    ]
]);


/**
 * PROJECT URLs
 */
define("CONF_URL_BASE", "http://localhost/toniette");
define("CONF_BASE_DIR", realpath(""));
define("CONF_THEME", "toniette");


/**
 * ENVIRONMENT
 */
define("CONF_ENVIRONMENT", "development"); // development || production
define("CONF_DEBUG", true);


/**
 * DATES
 */
define("CONF_DATETIME_BR", "d/m/Y H:i:s");
define("CONF_DATE_BR", "d/m/Y");
define("CONF_TIME_BR", "H:i:s");
define("CONF_DATETIME_APP", "Y-m-d H:i:s");
define("CONF_DATE_APP", "Y-m-d");
define("CONF_TIME_APP", "H:i:s");


/**
 * PASSWORD
 */
define("CONF_PASSWD_MIN_LEN", 8);
define("CONF_PASSWD_MAX_LEN", 40);
define("CONF_PASSWD_ALGO", PASSWORD_DEFAULT);
define("CONF_PASSWD_OPTIONS", ["cost" => 10]);
define("AUTHORIZATION", (new Request())->header("Authorization"));
define("CONF_ATTEMPT_LIMIT", 5);


/**
 * CONTACTS
 */
define("CONF_WEBMASTER_CONTACT", "foo@bar.com");


/**
 * MAIL
 */
define("CONF_MAIL_HOST", "");
define("CONF_MAIL_PORT", "");
define("CONF_MAIL_USER", "");
define("CONF_MAIL_PASS", "");
define("CONF_MAIL_SENDER", ["name" => "", "address" => ""]);
define("CONF_MAIL_OPTION_LANG", "en");
define("CONF_MAIL_OPTION_HTML", true);
define("CONF_MAIL_OPTION_AUTH", true);
define("CONF_MAIL_OPTION_SECURE", "tls");
define("CONF_MAIL_OPTION_CHARSET", "utf-8");


/**
 * STORAGE
 */
define("CONF_STORAGE_DIR", "storage");
define("CONF_STORAGE_IMAGE_DIR", "images");
define("CONF_STORAGE_FILE_DIR", "files");
define("CONF_STORAGE_MEDIA_DIR", "medias");


/**
 * VIEW
 */
define("CONF_TEMPLATE_ENGINE_PATH", __DIR__."/../Views");
define("CONF_TEMPLATE_ENGINE_EXTENSION", "php");


/**
 * SEO
 */
define("CONF_SEO_FB_PAGE", "*");
define("CONF_SEO_FB_AUTHOR", "*");
define("CONF_SEO_FB_APP", "*");
define("CONF_SEO_TW_CREATOR", "@*");
define("CONF_SEO_TW_SITE", "@*");
define("CONF_SEO_IMAGE", CONF_URL_BASE."/theme/".CONF_THEME."/assets/images/logo.svg");
define("CONF_SEO_ERROR_IMAGE", CONF_URL_BASE."/theme/".CONF_THEME."/assets/images/error.svg");


/**
 * SESSION
 */
define("CONF_SESSION_LIFE_TIME", 3600);
define("CONF_SESSION_BLOCK_TIME", 600);


/**
 * HTTP HEADERS
 */
header("Content-type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");


/**
 * LOG
 */
define("CONF_TELEGRAM_KEY", "1518972587:AAGCD40vL3_xFnxO26yi6k5tSIrDMLbNc7E");
define("CONF_TELEGRAM_GROUP", "-1001359255857");
define("CONF_LOG_PATH", "storage/logs");


/**
 * CACHE
 */
define("CONF_CACHE_PATH", "storage/cache");