<?php
if (!defined('APP_ROOT')) define('APP_ROOT', dirname(dirname(__DIR__)));

return array(
    'environment'    => 'development',

    'mongo' => [
        'dbname'    => getenv('MONGO_DB_NAME'),
        'host'      => getenv('VEGAS_CMF_FORMS_MONGO_PORT_27017_TCP_ADDR'),
        'port'      => getenv('VEGAS_CMF_FORMS_MONGO_PORT_27017_TCP_PORT')
    ],

    'forms' => [
        'templates' => [
            //'default_name' => 'jquery' // if present this will be default template name loaded in all decorated elements
        ]
    ]
);