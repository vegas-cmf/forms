<?php
/**
 * @author Slawomir Zytko <slawomir.zytko@gmail.com>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * Date: 4/24/14
 * Time: 1:24 PM
 */

//Test Suite bootstrap
include __DIR__ . "/../vendor/autoload.php";

define('TESTS_ROOT_DIR', dirname(__FILE__));

$configArray = require_once dirname(__FILE__) . '/fixtures/app/config/config.php';

$config = new \Phalcon\Config($configArray);

// \Phalcon\Mvc\Collection requires non-static binding of service providers.
class DiProvider
{

    public function resolve(\Phalcon\Config $config)
    {
        $di = new \Phalcon\Di\FactoryDefault();

        $di->set('config', $config);

        $di->set('collectionManager', function() {
            return new \Phalcon\Mvc\Collection\Manager();
        }, true);

        $di->set('mongo', function() use ($config) {
            $mongo = new \Phalcon\Db\Adapter\MongoDB\Client();
            $mongo->selectDatabase($config->mongo->dbname)->drop();
            return $mongo->selectDatabase($config->mongo->dbname);
        }, true);

        $view = new \Phalcon\Mvc\View();
        $view->registerEngines(array(
            '.volt' => function ($view, $di) {
                $volt = new \Phalcon\Mvc\View\Engine\Volt($view, $di);
                $volt->setOptions(array(
                    'compiledPath' => TESTS_ROOT_DIR.'/fixtures/cache/',
                    'compiledSeparator' => '_'
                ));

                return $volt;
            },
            '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
        ));

        $di->set('view', $view);

        $di->set('filter', '\Vegas\Filter', true);

        $di->set('logger', function () {
            return new \Phalcon\Logger\Adapter\Stream('/dev/null');
        }, true);

        \Phalcon\Di::setDefault($di);
    }

}

(new \DiProvider)->resolve($config);