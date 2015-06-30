<?php
namespace BrainpopUser\Controller;

use BrainpopUser\Entity\Account;
use BrainpopUser\Form\AccountForm;
use BrainpopUser\Form\UserForm;
use BrainpopUser\Form\GroupForm;

use Application\Controller\AbstractController;

class AccountController extends AbstractController
{
    // Load the repository getters method
    use \BrainpopUser\Repository\RepositoryGetterTrait;

    /** @var \BrainpopActivity\Model\Activity */
    private $activityModel;

    /**
     * process the list accounts
     * @return Array
     */
    public function listAction()
    {
        $messages = $this->flashMessenger()->getMessages();
        $accounts = $this->getAccountRepository()->listAccountsWithGroupsUsersCount();
        return array(
            'messages'  => $messages,
            'accounts'  => $accounts,
        );
    }

    /**
     * process the view account request
     * @return array 
     */
    public function viewAction()
    {
        $account = $this->getAccountRepository()->findById( $this->params()->fromRoute('account_id') );
        if (!$account instanceof Account) {
            return $this->notFoundAction();
        }

        if ($this->params()->fromRoute('accountForm')) {
            $form = $this->params()->fromRoute('accountForm');
        }
        else {
            $form = new AccountForm();
            $form->setData($account->getArrayCopy());
        }

        $users          = $this->getUserRepository()->listAccountUsers($account->getId());
        $addUserForm    = ($this->params()->fromRoute('addUserForm')) ? $this->params()->fromRoute('addUserForm') : new UserForm();

        $groups         = $this->getGroupRepository()->ListGroupsByAccountPlusUsersStats($account->getId());
        $addGroupForm   = ($this->params()->fromRoute('addGroupForm')) ? $this->params()->fromRoute('addGroupForm') : new GroupForm();

        return array(
            'account'       => $account,
            'form'          => $form,
            'groups'        => $groups,
            'users'         => $users,
            'userForm'      => $addUserForm,
            'groupForm'     => $addGroupForm,
        );
    }

    /**
     * process the update account request
     * @return array 
     */
    public function updateAction()
    {
        // If POST then redirect as GET. If no data exist in $prg then redirect to other method.
        $prg = $this->prg('account_update');
        if ($prg instanceof \Zend\Http\PhpEnvironment\Response) {
            return $prg;
        } elseif ($prg === false) {
            return $this->redirect()->toRoute('list_accounts');
        }

        $repo       = $this->getAccountRepository();
        $account    = $repo->createNew();
        $account->exchangeArray($prg);

        if (!$account->isValid()) {
            $form = new \BrainpopUser\Form\AccountForm();
            $form->setData($prg)->setMessages($account->getMessages());
            $this->flashMessenger()->addErrorMessage('The update process failed. Please try again.');
            return $this->forward()->dispatch('account', array('action' => 'view', 'account_id' => $prg['id'], 'accountForm' => $form));
        }
        $account->markDirty();
        $repo->commitAll();
        $this->flashMessenger()->addMessage("The Account was updated");
        return $this->redirect()->toRoute('account_view', array('account_id'=>$account->getId()));
    }

    /**
     * process the add account request
     * @return array 
     */
    public function addAction()
    {
        $prg = $this->prg('account_add');
        if ($prg instanceof \Zend\Http\PhpEnvironment\Response) {
            return $prg;
        }

        $form = new \BrainpopUser\Form\AccountForm();
        if (!empty($prg)) {
            $repo = $this->getAccountRepository();
            $account = $repo->createNew();
            $account->exchangeArray($prg);
            if ($account->isValid()) {
                $account->markNew();
                $repo->commitAll();
                return $this->redirect()->toRoute('account_view', array('account_id'=>$account->getId()));
            }
            $form->setMessages($account->getMessages());
            $form->bind($account);
        }

        return array(
            'form'  => $form,
        );
    }

    /**
     * process the delete account request
     * @return array 
     */
    public function deleteAction()
    {
        $accountId  = $this->params()->fromRoute('account_id');
        $repo       = $this->getAccountRepository();
        $account    = $repo->findById($accountId);
        if (!$account instanceof Account) {
            return $this->notFoundAction();
        }
        $message = "The account {$account->getName()} was deleted successfully";
        $account->markDelete();
        $repo->commitAll();
        $this->flashMessenger()->addMessage($message);
        return $this->redirect()->toRoute('list_accounts');
    }

    /**
     * process the clean account request
     * @return array 
     */
    public function cleanAction()
    {
        $accountId  = $this->params()->fromRoute('account_id');
        $account    = $this->getAccountRepository()->findById($accountId);
        if (!$account instanceof Account) {
            return $this->notFoundAction();
        }
        $message = "The account {$account->getName()} was cleaned successfully";
        $this->getActivityModel()->deleteAllUsersWorkByAccount($accountId);
        $this->flashMessenger()->addMessage($message);
        return $this->redirect()->toRoute('account_view', array('account_id'=>$accountId));
    }

    /**
     * Get the model object from the SM
     * @return \BrainpopActivity\Model\Activity
     */
    public function getActivityModel()
    {
        if (!$this->activityModel) {
            $this->activityModel = $this->getServiceLocator()->get('bp_activity_model');
        }
        return $this->activityModel;
    }

} // End of class
