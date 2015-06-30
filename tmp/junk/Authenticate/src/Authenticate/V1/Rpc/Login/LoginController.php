<?php
namespace Authenticate\V1\Rpc\Login;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Authentication\AuthenticationService;

use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class LoginController extends AbstractActionController
{

    /** @var AuthenticationService */
    private $authService;

    /**
     * Proccess the login request and auth the requestor
     * @return array|ApiProblemResponse
     */
    public function loginAction()
    {
        $data = $this->getEvent()->getParam('ZF\ContentValidation\InputFilter')->getValues();

        $auth = $this->getAuthService();
        if ($auth->hasIdentity()) {
            return $auth->getIdentity()->avilableGardens;
        }

        /* @var $adapter \Application\Authentication\AuthenticationAdapter */
        $adapter = $auth->getAdapter();
        $adapter->setIdentity($data['username'])->setCredential($data['password']);

        $auth->authenticate();

        if ($auth->hasIdentity()) {
            return $auth->getIdentity()->avilableGardens;
        }

        return new ApiProblemResponse(
            new ApiProblem(401, 'Login faild. The provided credentials are incorrect')
        );
    }

    /**
     * Get the Authentication Service
     * @return AuthenticationService
     */
    private function getAuthService()
    {
        if (!$this->authService instanceof AuthenticationService) {
            $this->authService = $this->getServiceLocator()->get('auth_service');
        }
        
        return $this->authService;
    }

}
