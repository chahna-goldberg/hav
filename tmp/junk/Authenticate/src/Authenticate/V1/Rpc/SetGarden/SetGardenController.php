<?php
namespace Authenticate\V1\Rpc\SetGarden;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Authentication\AuthenticationService;

use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

use Zend\View\Model\JsonModel;

/**
 * Manage the relationship between the authenticated user and a selected Garden
 */
class SetGardenController extends AbstractActionController
{
    /** @var AuthenticationService */
    private $authService;

    /**
     * Set the default garden for this session
     * @return JsonModel|ApiProblemResponse
     */
    public function setGardenAction()
    {
        $data = $this->getEvent()->getParam('ZF\ContentValidation\InputFilter')->getValues();
        $auth = $this->getAuthService();

        if (!$auth->hasIdentity()) {
            return new ApiProblemResponse( new ApiProblem(401, 'Login is required') );
        }

        $identity = $auth->getIdentity();

        foreach ($identity->avilableGardens as $gardenId => $gardenName) {
            if ($gardenId == $data['id']) {
                $identity->gardenId     = $gardenId;
                $identity->gardenName   = $gardenName;
                $auth->getStorage()->write($identity);
                return $this->getResponse()->setStatusCode(204);
            }
        }
        
        return new ApiProblemResponse( new ApiProblem(401, 'Requestor is not a manager in the requested garden') );
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
