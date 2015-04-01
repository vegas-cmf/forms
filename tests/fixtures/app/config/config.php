<?php
if (!defined('APP_ROOT')) define('APP_ROOT', dirname(dirname(__DIR__)));

return array(
    'environment'    => 'development',

    'mongo' => [
        'db' => 'vegas_test',
    ],

    'forms' => [
        'templates' => [
            //'default_name' => 'jquery' // if present this will be default template name loaded in all decorated elements
        ]
    ]
);