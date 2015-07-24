<?php
namespace Modules\Frontend;

use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Mvc\Dispatcher;
use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;

class Module implements ModuleDefinitionInterface
{
    /**
     * Registers the module auto-loader
     */
    public function registerAutoloaders(\Phalcon\DiInterface $dependencyInjector = null)
    {

        $loader = new Loader();

        $loader->registerNamespaces(array(
            'Modules\Frontend\Controllers' => __DIR__ . '/controllers/',
            'Modules\Models' => __DIR__ . '/../../models/',
            'Modules\Models\Entities' => __DIR__ . '/../../models/entities/',
            'Modules\Models\Services' => __DIR__ . '/../../models/services/',
            'Modules\Models\Repositories' => __DIR__ . '/../../models/repositories/'
        ));

        $loader->register();
    }

    public function registerServices(\Phalcon\DiInterface $di=null)
    {
        /**
         * Read configuration
         */
        $config = include dirname(dirname(dirname(__DIR__))) . "/apps/config/config.php";

        $di->set(
            'dispatcher',
            function() use ($di) {
                $dispatcher = new Dispatcher();
                $dispatcher->setDefaultNamespace('Modules\Frontend\Controllers');
                return $dispatcher;
            },
            true
        );
        $di->set('view', function() use ($config) {
            $view = new View();
            $view->setViewsDir(__DIR__ . '/views/');
            $view->registerEngines(array(
                '.volt' => function ($view, $di) use ($config) {

                        $volt = new VoltEngine($view, $di);

                        $volt->setOptions(array(
                            'compiledPath' => $config->application->cacheDir,
                            'compiledSeparator' => '_'
                        ));

                        return $volt;
                    },
                '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
            ));

            return $view;
        });



        /**
         * Database connection is created based in the parameters defined in the configuration file
         */
        $di['db'] = function () use ($config) {
            return new DbAdapter(array(
                "host" => $config->database->host,
                "username" => $config->database->username,
                "password" => $config->database->password,
                "dbname" => $config->database->dbname,
                'charset'   =>'utf8'
            ));
        };
    }

}
