<?php

return [

    'default_controller'        => 'tasks',
    'debug_mode'                => true,

    'template_root'     => ROOT_DIR . 'template' . DIRECTORY_SEPARATOR,
    'application_root'  => ROOT_DIR . 'application' . DIRECTORY_SEPARATOR,

    'db' => [
        'host' => 'localhost',
        'port' => 3306,
        'collate' => 'utf8',
        'user' => 'root',
        'password' => 'supersite123',
        'base' => 'testbeejee',
    ],

    'administrator' => [
        'login' => 'admin',
        'password' => '123',
    ],
];