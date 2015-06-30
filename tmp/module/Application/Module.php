<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2014 Zend Technologies USA Inc. (http://www.zend.com)
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Application\Service\UserAccessChecker;
use Zend\Http\Request as HttpRequest;
use Zend\Authentication\AuthenticationService;

use Zend\Cache\StorageFactory;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;

use Zend\Session\Storage\SessionStorage;
use Zend\Session\SessionManager;

use Zend\Log\Logger;
use Zend\Log\Writer\Stream AS LogWriterStream;



class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        if ($e->getRequest() instanceof HttpRequest) {
            $eventManager->attach(MvcEvent::EVENT_DISPATCH, array(new UserAccessChecker($e), 'getResponse'), 200);
        }

    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
        );
    }
    /**
     * Return the service config
     * @return array
     */
    public function getServiceConfig()
    {
        return [
            'factories' => [
                'session' => function () {
                    $storage    = new SessionStorage();
                    $manager    = new SessionManager(null, $storage);
                    return $manager->rememberMe(604800);
                },
                'auth_service' => function ($sm) {
                    $adapter    = new Authentication\AuthenticationAdapter(  ); //"$sm->get('dbAdapter')"
                    return new AuthenticationService(null, $adapter);
                },
                'ApplicationServiceErrorHandling' =>  function($sm) {
                    $logger     = $sm->get('ExceptionLogger');
                    $service    = new ExceptionHandler($logger);
                    return $service;
                },
                'ExceptionLogger' => function () {
                    $filename   = 'exception_log.txt';
                    $log        = new Logger();
                    $writer     = new LogWriterStream('./data/logs/' . $filename);
                    $log->addWriter($writer);

                    return $log;
                },
                'ResetPasswordCache' => function($sm) {
                    $c  = $sm->get('Config');
                    return StorageFactory::factory($c['reset_password_cache']);

                },
                'smtp' => function ($sm) {
                    $config     = $sm->get('Configuration');
                    $options    = new SmtpOptions($config['google_smtp']);
                    return new Smtp($options);
                },
            ],
        ];
    }

}
