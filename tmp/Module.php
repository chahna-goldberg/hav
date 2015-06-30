<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Service\ExceptionHandler;

use Application\Service\UserAccessChecker;
use IgnGarden\Service\SetGardenAndInjectAssets;
use Zend\Cache\StorageFactory;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Authentication\AuthenticationService;

use Zend\Session\Storage\SessionStorage;
use Zend\Session\SessionManager;

use Zend\Http\Request as HttpRequest;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream AS LogWriterStream;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getTarget()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        if ($e->getRequest() instanceof HttpRequest) {
            $eventManager->attach(MvcEvent::EVENT_DISPATCH, array(new UserAccessChecker($e), 'getResponse'), 200);
            $eventManager->attach(MvcEvent::EVENT_DISPATCH, array(new SetGardenAndInjectAssets($e), 'doAction'), 200);
        }

        $services = $e->getTarget()->getServiceManager();
        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, function ($event) use ($services) {
            $exception = $event->getResult()->exception;
            if (!$exception) {
                return;
            }
            $service = $services->get('ApplicationServiceErrorHandling');
            $service->logException($exception);
        });

        $this->injectAdapterToMapperWatcher($e);
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
                    $adapter    = new Authentication\AuthenticationAdapter( $sm->get('dbAdapter') );
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

    /**
     * @param MvcEvent $e
     */
    private function injectAdapterToMapperWatcher(MvcEvent $e)
    {
        $adapter = $e->getApplication()->getServiceManager()->get('dbAdapter');
        Service\MapperWatcher::setAdapter($adapter);
    }

}
