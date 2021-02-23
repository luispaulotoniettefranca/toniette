<?php


namespace Source\Support;


use Monolog\Formatter\LineFormatter;
use Monolog\Handler\BrowserConsoleHandler;
use Monolog\Handler\NativeMailerHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\TelegramBotHandler;
use Monolog\Logger;

/**
 * Class Log
 * @package Source\Support
 */
class Log extends Logger
{
    /**
     * Log constructor.
     */
    public function __construct()
    {
        parent::__construct("log");
        if (CONF_DEBUG) {
            $this->pushHandler(new BrowserConsoleHandler(Logger::DEBUG));
            $this->pushHandler(new StreamHandler(CONF_LOG_PATH."/info_log.log", Logger::INFO));
        } else {
            $this->pushHandler(new StreamHandler(CONF_LOG_PATH."/error_log.log", Logger::WARNING));
            $this->pushHandler(new NativeMailerHandler(CONF_WEBMASTER_CONTACT,
                "Critical Failure on ".CONF_URL_BASE, CONF_WEBMASTER_CONTACT, Logger::WARNING));
            $tlgHandler = new TelegramBotHandler(CONF_TELEGRAM_KEY, CONF_TELEGRAM_GROUP,
                Logger::EMERGENCY);
            $tlgHandler->setFormatter(new LineFormatter("%level_name%: %message% \n\n %context%"));
            $this->pushHandler($tlgHandler);
        }
    }
}