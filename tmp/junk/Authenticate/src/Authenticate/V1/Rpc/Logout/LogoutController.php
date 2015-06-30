<?php
namespace Authenticate\V1\Rpc\Logout;

use Zend\Mvc\Controller\AbstractActionController;

class LogoutController extends AbstractActionController
{
    public function logoutAction()
    {
        /* @var $auth AuthenticationService */
        $auth = $this->getServiceLocator()->get('auth_service');
        $auth->clearIdentity();
        return $this->redirect()->toRoute('login', []);
    }
}
