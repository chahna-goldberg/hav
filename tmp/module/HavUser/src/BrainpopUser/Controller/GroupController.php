<?php
namespace BrainpopUser\Controller;

use BrainpopUser\Entity\Group;
use BrainpopUser\Form\GroupForm;

use Application\Controller\AbstractController;

class GroupController extends AbstractController
{
    // Load the repository getters method
    use \BrainpopUser\Repository\RepositoryGetterTrait;

    /**
     * process the view group request
     * @return array 
     */
    public function viewAction()
    {
        $id     = $this->params()->fromRoute('group_id');
        $group  = $this->getGroupRepository()->findById($id);
        if (!$group instanceof Group) {
            return $this->notFoundAction();
        }

        if ($this->params()->fromRoute('groupForm')) {
            $form = $this->params()->fromRoute('groupForm');
        }
        else {
            $form = new GroupForm();
            $form->setData($group->getArrayCopy());
        }

        return array(
            'account'       => $this->getAccountRepository()->findById($group->getAccountId()),
            'form'          => $form,
            'group'         => $group,
            'users'         => $this->getUserRepository()->ListUsersGroupAssociation($group->getAccountId(), $group->getId()),
        );
    }

    /**
     * Process the group update action
     * @return \Zend\Http\PhpEnvironment\Response
     */
    public function updateAction()
    {
        // If POST then redirect as GET. If no data exist in $prg then redirect to other method.
        $prg = $this->prg('group_update');
        if ($prg instanceof \Zend\Http\PhpEnvironment\Response) {
            return $prg;
        } elseif ($prg === false) {
            return $this->redirect()->toRoute('list_accounts');
        }

        $group = $this->getGroupRepository()->createNew();
        $group->exchangeArray($prg);

        if (!$group->isValid()) {
            $form = new GroupForm();
            $form->bind($group)->setMessages($group->getMessages());
            $this->flashMessenger()->addErrorMessage('The update process failed. Please try again.');
            return $this->forward()->dispatch('group', array('action' => 'view', 'group_id' => $prg['id'], 'groupForm' => $form));
        }
        $group->markDirty();
        $this->getGroupRepository()->commitAll();
        $this->flashMessenger()->addMessage("The Group was updated");
        return $this->redirect()->toRoute('group_view', array('group_id'=>$group->getId()));
    }

    /**
     * process the add group request
     * @return array 
     */
    public function addAction()
    {
        $prg = $this->prg('group_add');
        if ($prg instanceof \Zend\Http\PhpEnvironment\Response) {
            return $prg;
        } elseif ($prg === false) {
            return $this->redirect()->toRoute('list_accounts');
        }

        $group = $this->getGroupRepository()->createNew();
        $group->exchangeArray($prg);

        if (!$group->isValid()) {
            $form = new GroupForm();
            $form->bind($group)->setMessages($group->getMessages());
            return $this->forward()->dispatch('account', array('action' => 'view', 'account_id' => $prg['account_id'], 'addGroupForm' => $form));
        }
        $group->markNew();
        $this->getGroupRepository()->commitAll();
        $this->flashMessenger()->addMessage("The group {$prg['name']} was added successfully");
        return $this->redirect()->toRoute('group_view', array('group_id'=>$group->getId()));
    }

    /**
     * process the delete group request
     * @return array 
     */
    public function deleteAction()
    {
        $id     = $this->params()->fromRoute('group_id');
        $group  = $this->getGroupRepository()->findById($id);
        if (!$group instanceof Group) {
            return $this->notFoundAction();
        }
        $accountId = $group->getAccountId();
        $message = "The group {$group->getName()} was deleted successfully";
        $group->markDelete();
        $this->getGroupRepository()->commitAll();
        $this->flashMessenger()->addMessage($message);
        return $this->redirect()->toRoute('account_view', array('account_id'=>$accountId));
    }

} // End of class
