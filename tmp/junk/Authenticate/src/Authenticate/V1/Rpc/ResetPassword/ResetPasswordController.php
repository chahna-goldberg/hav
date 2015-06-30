<?php
namespace Authenticate\V1\Rpc\ResetPassword;

use IgnUser\Entity\User;
use Zend\Cache\Storage\StorageInterface;
use Zend\Http\PhpEnvironment\Response;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Db\Adapter\Adapter;
use Authenticate\V1\Service\ChangePassword;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class ResetPasswordController extends AbstractActionController
{
    use \IgnUser\Repository\RepositoryGetterTrait;

    /** @var Adapter */
    private $dbAdapter;

    /** @var StorageInterface */
    private $cacheService;

    /** @var string */
    private $username;

    /**
     * @return Response
     */
    public function resetPasswordAction()
    {
        $data = $this->getValuesFromInputFilter();

        if ($this->isInvalidToken()) {
            $url = $this->url()->fromRoute('login');
            return $this->getHalResponse(400, $this->getTranslator()->translate("Token expired"), $url);
        }

        $action = new ChangePassword($this->getDbAdapter(), $this->getTranslator());

        if ($action->changeTo($this->getUser(), $data['newPassword'])) {
            $this->getCacheService()->removeItem($this->getToken());
            return $this->getHalResponse(200, "Ok");
        }
        return $this->getHalResponse($action->getHttpErrorCode(), $action->getLastErrorMessage());
    }

    /**
     * @param int $code
     * @param string $message
     * @param $link
     * @return Response
     */
    private function getHalResponse($code, $message, $link = null)
    {
        $a = ($link) ? ['link' => $link] : [];
        return new ApiProblemResponse(new ApiProblem($code, $message, null, null, $a));
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
     * @return StorageInterface
     */
    private function getCacheService()
    {
        if (!$this->cacheService instanceof StorageInterface) {
            $this->cacheService = $this->getServiceLocator()->get('reset_password_cache');
        }
        return $this->cacheService;
    }

    /**
     * @return TranslatorInterface
     */
    private function getTranslator()
    {
        return $this->getServiceLocator()->get('translator');
    }

    /**
     * @return array
     */
    private function getValuesFromInputFilter()
    {
        return $this->getEvent()->getParam('ZF\ContentValidation\InputFilter')->getValues();
    }

    /**
     * @return User
     */
    private function getUser()
    {
        return $this->getUserRepository()->findByUsername($this->username);
    }

    private function isInvalidToken()
    {
        if (!$this->getCacheService()->hasItem($this->getToken())) {
            return true;
        }
        $this->username = $this->getCacheService()->getItem($this->getToken());
    }

    /**
     * @return string
     */
    private function getToken()
    {
        $data = $this->getValuesFromInputFilter();
        return $data['token'];

    }
}
