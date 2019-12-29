<?php

return [

    'default_controller'        => 'tasks',
    'debug_mode'                => false,

    'template_root'     => ROOT_DIR . 'template' . DIRECTORY_SEPARATOR,
    'application_root'  => ROOT_DIR . 'application' . DIRECTORY_SEPARATOR,

    'db' => [
        'host' => '127.0.0.1',
        'port' => 3306,
        'collate' => 'utf8',
        'user' => 'redreem',
        'password' => 'Madjaneva123',
        'base' => 'redreem',
    ],

    'administrator' => [
        'login' => 'admin',
        'password' => '123',
    ],
];