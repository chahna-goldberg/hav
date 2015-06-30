<?php

namespace Application\Service;

use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class UserAccessChecker {

    /** @var MvcEvent */
    private $event;

    /**
     * @param MvcEvent $e
     */
    public function __construct(MvcEvent $e)
    {
        $this->event = $e;
    }

    /**
     * @return ApiProblemResponse|Response
     */
    public function getResponse()
    {
        if ($this->isAuthorizedForGuest()) {
            return;
        }

        if (!$this->getAuthService()->hasIdentity()) {
            return $this->createResponse();
        }
    }

    /**
     * @return boolean
     */
    private function isApiRequest()
    {
        return preg_match('/^\/api\/.*$/i', $this->getEvent()->getRequest()->getUri()->getPath());
    }

    /**
     * @return boolean
     */
    private function isAuthorizedForGuest()
    {
        $path = $this->getEvent()->getRouteMatch()->getMatchedRouteName();
        $allowedNames   = [
            'login',
            'authenticate.rpc.login',
            'reset_password_view',
            'authenticate.rpc.send-password-reset',
            'authenticate.rpc.reset-password',
        ];

        return in_array($path, $allowedNames);
    }

    /**
     * @return Response
     */
    private function redirectToLogin()
    {
        $response   = $this->getEvent()->getResponse();
        $url        = $this->getEvent()->getRouter()->assemble( array(), array('name' => 'login'));

        $response->setStatusCode(302);
        $response->getHeaders()->addHeaderLine('Location', $url);
        return $response;
    }

    /**
     * @return MvcEvent
     */
    private function getEvent()
    {
        return $this->event;
    }

    /**
     * @return AuthenticationServiceInterface
     */
    private function getAuthService()
    {
        return $this->getEvent()->getTarget()->getServiceManager()->get('auth_service');
    }

    /**
     * @return Response|ApiProblemResponse
     */
    private function createResponse()
    {
        if ($this->isApiRequest()) {
            return new ApiProblemResponse(new ApiProblem(401, 'Login is required'));
        }
        return $this->redirectToLogin();
    }

}