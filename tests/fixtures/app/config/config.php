<?php
if (!defined('APP_ROOT')) define('APP_ROOT', dirname(dirname(__DIR__)));

return array(
    'environment'    => 'development',

    'mongo' => [
        'db' => 'vegas_test',
    ],

    'forms' => [
        'templates' => [
            'default_name' => 'jquery'
            //'default_path' => PATH_TO_ALL_CUSTOM_TEMPLATES - optional
        ]
    ]
);