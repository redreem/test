<?php

class Core
{
    public static $config = [];

    public static $config_name;

    public static $config_path  = ROOT_DIR . 'config' . DIRECTORY_SEPARATOR;
    public static $core_path    = ROOT_DIR . 'core' . DIRECTORY_SEPARATOR;
    public static $library_path = ROOT_DIR . 'core' . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR;

    private static final function getConfig()
    {
        $config_file = self::$config_path . self::$config_name;

        if (file_exists($config_file)) {

            $config = include $config_file;

            foreach ($config as $key => $val) {

                self::$config[$key] = $val;
            }
        }
    }

    public static final function setConfigPostfix()
    {
        if (
            $_SERVER['SERVER_ADDR'] == '127.0.0.1'
            ||
            strpos($_SERVER['HTTP_HOST'], 'local') !== false
        ) {

            self::$config_name = 'config.local.php';
        } else {

            self::$config_name = 'config.prod.php';
        }
    }

    public static final function execute()
    {
        self::setConfigPostfix();

        self::getConfig();

        if (self::$config['debug_mode']) {

            ini_set('error_reporting', E_ALL);
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);

        } else {

            ini_set('error_reporting', 0);
            ini_set('display_errors', 0);
            ini_set('display_startup_errors', 0);
        }

        include_once ROOT_DIR . 'core' . DIRECTORY_SEPARATOR . 'Application.php';

        Application::execute();
    }
}