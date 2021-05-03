<?php

// SESSION LIFETIME MATURITY VERIFY
if (session()->has("auth")) {
    if (session()->maturity < time()) {
        session()->maturity = time() + CONF_SESSION_LIFE_TIME;
    } else {
        session()->destroy();
    }
}


// ATTEMPTS LIMIT TIME VERIFY
if (session()->has("block") && session()->block < time()) {
    session()->unset("attempts");
    session()->unset("block");
}


// SETTING DEBUG MODE
if (CONF_DEBUG) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}


// MINIFYING ASSETS
if (CONF_ENVIRONMENT == 'development') {
    //CSS MINIFY
    $minCSS = new \MatthiasMullie\Minify\CSS();
    $minCSS->add(__DIR__."/../../theme/".CONF_THEME."/assets/css/bootstrap.min.css");
    $cssDir = scandir(__DIR__."/../../theme/".CONF_THEME."/assets/css");
    foreach ($cssDir as $item) {
        $cssFile = __DIR__."/../../theme/".CONF_THEME."/assets/css/{$item}";
        if (is_file($cssFile) && pathinfo($cssFile)["extension"] == "css") {

            $minCSS->add($cssFile);
        }
    }
    $minCSS->minify(__DIR__."/../../theme/".CONF_THEME."/assets/style.min.css");

    //JS MINIFY
    $minJS = new \MatthiasMullie\Minify\JS();
    $minJS->add(__DIR__."/../../theme/".CONF_THEME."/assets/js/jquery.js");
    $minJS->add(__DIR__."/../../theme/".CONF_THEME."/assets/js/bootstrap.min.js");
    $jsDir = scandir(__DIR__."/../../theme/".CONF_THEME."/assets/js");
    foreach ($jsDir as $item) {
        $jsFile = __DIR__."/../../theme/".CONF_THEME."/assets/js/{$item}";
        if (is_file($jsFile) && pathinfo($jsFile)["extension"] == "js") {
            $minJS->add($jsFile);
        }
    }
    $minJS->minify(__DIR__."/../../theme/".CONF_THEME."/assets/script.min.js");
}