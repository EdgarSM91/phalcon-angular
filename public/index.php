<?php
date_default_timezone_set('America/Mexico_City');
ini_set('display_errors',true);
error_reporting(E_ALL);
$di = new \Phalcon\DI\FactoryDefault();

$di->set('url', function(){
    $url = new \Phalcon\Mvc\Url();
    $url->setBaseUri("http://".$_SERVER["SERVER_NAME"]."/");
    return $url;
});

$di->set('router', function(){

    $router = new \Phalcon\Mvc\Router();

    $router->setDefaultModule("frontend");

    $router->add("/", array(
        'module'=>'frontend',
        'controller' => 'index',
        'action' => 'index',
    ));
    $router->add("/contactanos", array(
        'module'=>'frontend',
        'controller' => 'index',
        'action' => 'contactanos',
    ));
    $router->add("/token", array(
        'module'=>'frontend',
        'controller' => 'index',
        'action' => 'token',
    ));
    $router->notFound(array(
        'module' => 'frontend',
        'controller' => 'index',
        'action' => 'show404'
    ));
    /* Dashboard */
    $router->add("/dashboard", array(
        'module'=>'dashboard',
        'controller' => 'index',
        'action' => 'index',
    ));
    $router->add("/login", array(
        'module'=>'dashboard',
        'controller' => 'login',
        'action' => 'index',
    ));
    $router->add("/logout",array(
        'module'=>'dashboard',
        'controller' => 'login',
        'action' => 'logout',
    ));
    $router->add('/dashboard/([a-zA-Z\-]+)/([a-zA-Z\-]+)', array(
        'module'=>'dashboard',
        'controller' => 1,
        'action' => 2,
    ))->setName("controllers")->convert('action', function($action) {
            return \Phalcon\Text::lower(\Phalcon\Text::camelize($action));
        });
    $router->removeExtraSlashes(true);
    return $router;
});
/**
 * Start the session the first time some component request the session service
 */
$di->set('dispatcher', function() use ($di){
    $dispatcher = new \Phalcon\Mvc\Dispatcher();
    $eventsManager = $di->getShared('eventsManager');
    $security = new Security($di);

    $eventsManager->attach('dispatch', $security);
    $dispatcher->setEventsManager($eventsManager);

    return $dispatcher;
});

$di->set('session', function () {
    $session = new Phalcon\Session\Adapter\Files();
    $session->start();

    return $session;
});
$application = new \Phalcon\Mvc\Application();

//Pass the DI to the application
$application->setDI($di);

//Register the installed modules
$application->registerModules(array(
            'frontend' => array(
                'className' => 'Modules\Frontend\Module',
                'path' =>'../apps/modules/frontend/Module.php'
            ),
            'dashboard' => array(
                'className' => 'Modules\Dashboard\Module',
                'path' =>'../apps/modules/dashboard/Module.php'
            )
        ));
echo $application->handle()->getContent();