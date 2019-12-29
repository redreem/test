<?php

class Application
{
    static $model;
    static $view;
    static $controller;

    static $common_model;
    static $common_view;
    static $common_controller;

    static $action = 'default';
    static $id = 0;

    static $application_name;
    static $application_path;

    static $protocol;

    static $header_http_code = 200;

    static $router_send_abort = false;

    static $db;

    public static function setHttpCode($code)
    {
        self::$header_http_code = $code;
    }

    public static function redirect($url, $code = false)
    {
        if (self::$db) {

            self::$db->close();
        }

        if ($code && array_key_exists($code, OutContent::$http_codes)) {

            header('HTTP/1.1 ' . OutContent::$http_codes[$code], false);
        }

        if ($url) {

            header('Location: ' . $url);
        }
        exit();
    }

    public static function execute()
    {
        self::$protocol = 'http://';

        include_once Core::$core_path . 'AbstractController.php';
        include_once Core::$core_path . 'AbstractModel.php';
        include_once Core::$core_path . 'AbstractView.php';

        include_once Core::$core_path . 'Template.php';
        include_once Core::$core_path . 'FE.php';
        include_once Core::$core_path . 'Router.php';
        include_once Core::$core_path . 'OutContent.php';

        include_once Core::$library_path . 'Db.php';

        self::$db = new Db();
        self::$db->connect(Core::$config['db']);
        self::$db->setDatabaseName(Core::$config['db']['base']);

        Router::routing();

        if (!empty(Router::$route[1])) {

            self::$action = Router::$route[1];

        } elseif (!empty($_REQUEST['action'])) {

            self::$action = $_REQUEST['action'];
        }

        if (!empty(Router::$route[2])) {

            self::$id = Router::$route[2];

        } elseif (!empty($_REQUEST['id'])) {

            self::$id = $_REQUEST['id'];
        }

        self::$application_path = Core::$config['application_root'] . Router::$route[0] . DIRECTORY_SEPARATOR;

        self::$application_name = ucfirst(Router::$route[0]);

        $model_name         = self::$application_name . 'Model';
        $view_name          = self::$application_name . 'View';
        $controller_name    = self::$application_name . 'Controller';

        include_once self::$application_path . $model_name . '.php';
        include_once self::$application_path . $view_name . '.php';
        include_once self::$application_path . $controller_name . '.php';

        self::$model      = new $model_name;
        self::$view       = new $view_name;
        self::$controller = new $controller_name(self::$model, self::$view);

        if (empty($_REQUEST['ajax'])) {

            self::$view->content .= FE::setFEData();

            $common_path = Core::$config['application_root'] . 'common' . DIRECTORY_SEPARATOR;

            include_once $common_path . 'CommonModel.php';
            include_once $common_path . 'CommonView.php';
            include_once $common_path . 'CommonController.php';

            self::$common_model         = new CommonModel();
            self::$common_view          = new CommonView();
            self::$common_controller    = new CommonController(self::$common_model, self::$common_view, 'default');

            OutContent::execute(self::$common_view->content, 'html', self::$header_http_code);

        } else {

            if (empty($_REQUEST['request_data_type'])) {

                $_REQUEST['request_data_type'] = 'html';
            }

            if (!empty($_REQUEST['fe'])) {

                if($_REQUEST['request_data_type'] == 'html')
                {
                    $content = &self::$view->content;
                } else {

                    $content = json_encode([

                        'fe_data'   => FE::setFEData('json'),
                        'content'   => self::$view->content,
                    ]);
                }
            } else {

                $content = &self::$view->content;
            }

            OutContent::execute($content, $_REQUEST['request_data_type'], self::$header_http_code);
        }

        self::$db->close();
    }
}