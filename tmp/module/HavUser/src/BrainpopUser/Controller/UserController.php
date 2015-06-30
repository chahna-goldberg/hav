<?php
namespace BrainpopUser\Controller;

use Application\Controller\AbstractController;

use BrainpopUser\Entity\User;
use BrainpopUser\Form\UserForm;

class UserController extends AbstractController
{
    // Load the repository getters method
    use \BrainpopUser\Repository\RepositoryGetterTrait;

    protected $messages;

    /**
     * Load the basic needs of the class
     * @todo Check if it add measureable performance overhead 
     */
    public function __construct()
    {
        $this->messages = array();
    }

    /**
     * process the Login action
     * @return array
     */
    public function loginAction()
    {
        $this->layout('layout/iframe');

        $messages   = $this->messages;
        $auth       = $this->getServiceLocator()->get('auth_service');


        // if the role allowed to enter the CMS the route to activity list. else logout
        // todo: consider moving the logic to the application and set forbiden result using headers check (html vs json)
        if ( $auth->hasIdentity() ):
            $acl                = $this->getServiceLocator()->get('acl_checker');
            $role               = $auth->getStorage()->read()->role;
            $is_role_allowed    = $acl->isAllowed($role, 'activities_list');

            if ( $is_role_allowed ) {
                return $this->redirect()->toRoute('activities_list', array());
            } else {
                return $this->redirect()->toRoute('user_logout', array());
            }

        endif;

        // Get the form object from the SM
        $form = $this->getServiceLocator()->get('login_form');

        if ( $this->flashMessenger()->hasMessages() ) {
            $messages = array_merge($messages, $this->flashMessenger()->getMessages());
        }

        return array(
            'login_form'    => $form,
            'messages'      => $messages,
        );
    }

    /**
     * process the addUser action
     * @return array
     */
    public function loginProcessAction()
    {
        // If POST then redirect as GET. If no data exist in $prg then redirect to other method.
        $prg = $this->prg('user_login_process');
        if ($prg instanceof \Zend\Http\PhpEnvironment\Response) {
            return $prg;
        } elseif ($prg === false) {
            return $this->redirect()->toRoute('user_login');
        }

        // Get the form/filter object, inject it to the form and apply the data
        $form = $this->getServiceLocator()->get('login_form');
        $filter = $this->getServiceLocator()->get('login_form_filter');
        $form->setInputFilter($filter);
        $form->setData($prg);
        
        if (!$form->isValid()) {
            $this->messages[] = "There were one or more issues with your submission. Please correct them as indicated below.";
            return $this->forward()->dispatch('user', array('action' => 'login'));
        }

        $auth = $this->getServiceLocator()->get('auth_service');
        $auth->login($prg['account_id'], $prg['username'], $prg['password']);

        if ( $auth->hasIdentity() ) {
            $name = ucfirst($prg['username']);
            $this->flashMessenger()->addMessage("Welcome back {$name}");
            return $this->redirect()->toRoute('activities_list', array());
        }

        sleep(5); // Make password guessing a bit harder

        $this->messages[] = "One of the submited parameters is invalid. Please fix and try again.";
        return $this->forward()->dispatch('user', array('action' => 'login'));
   }
    
    /**
     * process the lgout action and redirect to the Login page
     * @return array
     */
    public function logoutAction()
    {
        $auth = $this->getServiceLocator()->get('auth_service');
        
        if ( $auth->hasIdentity() ) {
            $this->flashMessenger()->addMessage("Goodbye, You have been logged out");
        }
        $auth->logout();

        $session = $this->getServiceLocator()->get('session_manager');
        $session->expireSessionCookie();

        return $this->redirect()->toRoute('user_login');
    }

    /**
     * process the View user action
     * @return array
     */
    public function viewAction()
    {
        $id     = (int) $this->params()->fromRoute('id');
        $user   = $this->getUserRepository()->findById($id);
        if (!$user instanceof User) {
            return $this->notFoundAction();
        }

        if ($this->params()->fromRoute('userForm')) {
            $form = $this->params()->fromRoute('userForm');
        }
        else {
            $form = new UserForm();
            $form->bind($user);
        }

        $account = $this->getAccountRepository()->findById($user->getAccountId());

        return array(
            'account'   => $account,
            'user'      => $user,
            'form'      => $form,
        );
    }

    /**
     * Show the add action
     * @return array
     */
    public function addAction()
    {
        /* @var $user User */
        $prg = $this->prg('user_add');
        if ($prg instanceof \Zend\Http\PhpEnvironment\Response) {
            return $prg;
        } elseif ($prg === false) {
            return $this->redirect()->toRoute('list_accounts');
        }

        $user = $this->getUserRepository()->createNew();
        $user->exchangeArray($prg);

        if (!$user->isValid()) {
            $form = new \BrainpopUser\Form\UserForm();
            $form->setData($user->getArrayCopy())->setMessages($user->getMessages());
            return $this->forward()->dispatch('account', array('action' => 'view', 'account_id' => $prg['accountId'], 'addUserForm' => $form));
        }
        $user->markNew();
        $this->getUserRepository()->commitAll();
        $this->flashMessenger()->addMessage("User account for {$user->getName()->getFullName()} was added successfully");
        return $this->redirect()->toRoute('account_view', array('account_id'=>$user->getAccountId()));
    }

    /**
     * process the update action
     * @return array
     */
    public function updateAction()
    {
        // If POST then redirect as GET. If no data exist in $prg then redirect to other method.
        $prg = $this->prg('user_update');
        if ($prg instanceof \Zend\Http\PhpEnvironment\Response) {
            return $prg;
        } elseif ($prg === false) {
            return $this->redirect()->toRoute('list_accounts');
        }

        /* @var $user User */
        $user = $this->getUserRepository()->findById($prg['id']);
        $user->exchangeArray($prg);
        if (!$user->isValid()) {
            $form = new UserForm();
            $form->bind($user)->setMessages($user->getMessages());
            return $this->forward()->dispatch('user', array('action' => 'view', 'id' => $prg['id'], 'userForm' => $form));
        }
        $user->markDirty();
        $this->getUserRepository()->commitAll();
        $this->flashMessenger()->addMessage("The user was updated successfully");
        return $this->redirect()->toRoute('user_view', array('id'=>$prg['id']));
   }

    /**
     * process the update password form action
     * @return array
     */
    public function updatePasswordAction()
    {
        $this->layout('layout/iframe');
        $id     = (int) $this->params()->fromRoute('id');
        $user   = $this->getUserRepository()->findById($id);
        if (!$user instanceof User) {
            return $this->notFoundAction();
        }

        $postData       = $this->request->getPost()->toArray();
        $form           = new \BrainpopUser\Form\UpdatePasswordForm();
        $formUrl        = $this->url()->fromRoute('user_update_password', array('id'=>$user->getId()));
        $inputFilter    = new \BrainpopUser\InputFilter\UpdatePasswordFilter();

        $form->setInputFilter($inputFilter)->setData($postData);
        $form->setAttributes(array('action'=>$formUrl, 'method'=>'post'));

        if ($this->request->isPost() && $form->isValid()) {
            $user->setPassword($form->get('password')->getValue());
            $user->markDirty();
            $this->getUserRepository()->commitAll();
            $this->flashMessenger()->addMessage("The user password was updated successfully");
            return $this->redirect()->toRoute('user_update_password', array('id'=>$user->getId()));
        }

        return array(
            'user'      => $user,
            'form'      => $form,
        );
   }

    /**
     * process the delete user action
     * @return array
     */
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id');

        $user = $this->getUserRepository()->findById($id);
        if (!$user instanceof User) {
            return $this->notFoundAction();
        }
        /* @var $auth \Application\Authentication\AuthenticationService */
        $currentUserId = $this->getServiceLocator()->get('auth_service')->getStorage()->read()->user_id;
        if ($currentUserId === $id) {
            $this->flashMessenger()->addMessage('You cant delete yourself');
            return $this->redirect()->toRoute('account_view', array('account_id'=>$user->getAccountId()));
        }

        try {
            $user->markDelete();
            $this->getUserRepository()->commitAll();
        } catch (\Exception $exc) {
            $this->flashMessenger()->addMessage('The user delete faild. Check if the user has associated resources');
            return $this->redirect()->toRoute('user_view', array('id'=>$user->getId()));
        }

        $message = "The user {$user->getName()->getFullName()} was deleted successfully";
        $this->flashMessenger()->addMessage($message);
        return $this->redirect()->toRoute('account_view', array('account_id'=>$user->getAccountId()));
   }

} // End of class
