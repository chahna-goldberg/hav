<?php
namespace Authenticate\V1\Rpc\SetPassword;

use IgnUser\Entity\User;
use Zend\Http\PhpEnvironment\Response;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Db\Adapter\Adapter;

use Authenticate\V1\Service\ChangePassword;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class SetPasswordController extends AbstractActionController
{
    use \IgnUser\Repository\RepositoryGetterTrait;

    /** @var User */
    private $user;

    /** @var Adapter */
    private $dbAdapter;

    /**
     * @return Response
     */
    public function setPasswordAction()
    {
        $data   = $this->getEvent()->getParam('ZF\ContentValidation\InputFilter')->getValues();

        if (!$this->isOldPassValid($this->getUser()->getId(), $data['oldPassword'])) {
            return $this->getHalResponse(400, $this->getTranslator()->translate('The old password is invalid'));
        }

        $action = new ChangePassword($this->getDbAdapter(), $this->getTranslator());

        if ($action->changeTo($this->getUser(), $data['newPassword'])) {
            return $this->getHalResponse(200, "Ok");
        }
        return $this->getHalResponse($action->getHttpErrorCode(), $action->getLastErrorMessage());
    }

    /**
     * @return User
     */
    private function getUser()
    {
        if (!$this->user instanceof User) {
            $authService    = $this->getServiceLocator()->get('auth_service');
            $id             = $authService->getIdentity()->id;
            $this->user     = $this->getUserRepository()->findById($id);
        }
        return $this->user;
    }

    /**
     * @param int $id
     * @param string $old
     * @return boolean
     * @throws \RuntimeException
     */
    private function isOldPassValid($id, $old)
    {
        $sql  = "SELECT password FROM ign_users WHERE id=?";
        try {
            $result = $this->getDbAdapter()->query($sql)->execute([$id])->current();
        }
        catch (\Exception $e) {
            throw new \RuntimeException('SetPassword failed to select from DB', 125901, $e);
        }

        $passParts      = explode(':', $result["password"]);
        $storedPassword = $passParts[0];
        $salt           = $passParts[1];
        $concat         = $old.$salt;
        $givenPassword  = md5($concat);

        if ($givenPassword == $storedPassword) {
            return true;
        }
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
     * @param int $code
     * @param string $message
     * @return Response
     */
    private function getHalResponse($code, $message)
    {
        return new ApiProblemResponse(new ApiProblem($code, $message));
    }

    /**
     * @return TranslatorInterface
     */
    private function getTranslator()
    {
        return $this->getServiceLocator()->get('translator');
    }
}
