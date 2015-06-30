<?php
namespace Authenticate\V1\Rpc\SendPasswordReset;

use IgnUser\Entity\User;
use Zend\Http\PhpEnvironment\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Db\Adapter\Adapter;
use Zend\Cache\Storage\StorageInterface;
use Zend\Mail\Transport\Smtp;
use Zend\I18n\Translator\TranslatorInterface;


use Authenticate\V1\Service\SendPasswordReset;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class SendPasswordResetController extends AbstractActionController
{
    use \IgnUser\Repository\RepositoryGetterTrait;

    /** @var Smtp */
    private $smtpService;

    /** @var StorageInterface */
    private $fsCacheService;

    /** @var Adapter */
    private $dbAdapter;

    /**
     * @return Response
     */
    public function sendPasswordResetAction()
    {
        $data = $this->getEvent()->getParam('ZF\ContentValidation\InputFilter')->getValues();
        $action = new SendPasswordReset($this->getCacheService(), $this->getSmtpService(), $this->getDbAdapter(), $this->getTranslator(), $this->url());

        if ($action->sendTo($this->getUser($data['username']))) {
            return $this->getResponse()->setStatusCode(204);
        }
        return $this->getHalResponse($action->getHttpErrorCode(), $action->getLastError());
    }

    /**
     * @param int $code
     * @param string $message
     * @return Response
     */
    private function getHalResponse($code, $message)
    {
        return new ApiProblemResponse(new ApiProblem($code, $message));
    }

    /**
     * @return Smtp
     */
    private function getSmtpService()
    {
        if (!$this->smtpService instanceof Smtp) {
            $this->smtpService = $this->getServiceLocator()->get('smtp');
        }
        return $this->smtpService;
    }

    /**
     * @return StorageInterface
     */
    private function getCacheService()
    {
        if (!$this->fsCacheService instanceof StorageInterface) {
            $this->fsCacheService = $this->getServiceLocator()->get('reset_password_cache');
        }
        return $this->fsCacheService;
    }

    /**
     * @return Adapter
     */
    private function getDbAdapter()
    {
        if (!$this->dbAdapter instanceof Adapter) {
            $this->dbAdapter = $this->getServiceLocator()->get('dbAdapter');
        }
        return $this->dbAdapter;
    }

    /**
     * @return TranslatorInterface
     */
    private function getTranslator()
    {
        return $this->getServiceLocator()->get('translator');
    }

    /**
     * @param string $username
     * @return User
     */
    private function getUser($username)
    {
        return $this->getUserRepository()->findByUsername($username);
    }

}
