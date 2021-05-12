<?php

use Toniette\Router\Request;


/**
 * DATABASE
 */
const DATA_LAYER_CONFIG = [
    "driver" => "mysql",
    "host" => "db",
    "port" => "3306",
    "dbname" => "development_db",
    "username" => "user",
    "passwd" => "pass",
    "options" => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_CASE => PDO::CASE_NATURAL
    ]
];


/**
 * PROJECT URLs
 */
const CONF_URL_BASE = "http://localhost:8000";
define("CONF_BASE_DIR", realpath(""));
const CONF_THEME = "toniette";


/**
 * ENVIRONMENT
 */
const CONF_ENVIRONMENT = "development"; // development || production
const CONF_DEBUG = true;


/**
 * DATES
 */
date_default_timezone_set('America/Sao_Paulo');
const CONF_DATETIME_BR = "d/m/Y H:i:s";
const CONF_DATE_BR = "d/m/Y";
const CONF_TIME_BR = "H:i:s";
const CONF_DATETIME_APP = "Y-m-d H:i:s";
const CONF_DATE_APP = "Y-m-d";
const CONF_TIME_APP = "H:i:s";


/**
 * PASSWORD
 */
const CONF_PASSWD_MIN_LEN = 8;
const CONF_PASSWD_MAX_LEN = 128;
const CONF_PASSWD_ALGO = PASSWORD_DEFAULT;
const CONF_PASSWD_OPTIONS = ["cost" => 10];
define("AUTHORIZATION", (new Request())->header("Authorization"));
const CONF_ATTEMPT_LIMIT = 5;


/**
 * CONTACTS
 */
const CONF_WEBMASTER_CONTACT = "foo@bar.com";


/**
 * MAIL
 */
const CONF_MAIL_HOST = "";
const CONF_MAIL_PORT = "";
const CONF_MAIL_USER = "";
const CONF_MAIL_PASS = "";
const CONF_MAIL_SENDER = ["name" => "", "address" => ""];
const CONF_MAIL_OPTION_LANG = "en";
const CONF_MAIL_OPTION_HTML = true;
const CONF_MAIL_OPTION_AUTH = true;
const CONF_MAIL_OPTION_SECURE = "tls";
const CONF_MAIL_OPTION_CHARSET = "utf-8";


/**
 * STORAGE
 */
const CONF_STORAGE_DIR = "storage";
const CONF_STORAGE_IMAGE_DIR = "images";
const CONF_STORAGE_FILE_DIR = "files";
const CONF_STORAGE_MEDIA_DIR = "medias";


/**
 * VIEW
 */
const CONF_TEMPLATE_ENGINE_PATH = __DIR__ . "/../Views";
const CONF_TEMPLATE_ENGINE_EXTENSION = "php";


/**
 * SEO
 */
const CONF_SEO_FB_PAGE = "*";
const CONF_SEO_FB_AUTHOR = "*";
const CONF_SEO_FB_APP = "*";
const CONF_SEO_TW_CREATOR = "@*";
const CONF_SEO_TW_SITE = "@*";
const CONF_SEO_IMAGE = CONF_URL_BASE . "/theme/" . CONF_THEME . "/assets/images/logo.svg";
const CONF_SEO_ERROR_IMAGE = CONF_URL_BASE . "/theme/" . CONF_THEME . "/assets/images/error.svg";


/**
 * SESSION
 */
const CONF_SESSION_LIFE_TIME = 3600;
const CONF_SESSION_BLOCK_TIME = 600;


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
const CONF_TELEGRAM_KEY = "";
const CONF_TELEGRAM_GROUP = "";
const CONF_LOG_PATH = "storage/logs";


/**
 * CACHE
 */
const CONF_CACHE_PATH = "storage/cache";
