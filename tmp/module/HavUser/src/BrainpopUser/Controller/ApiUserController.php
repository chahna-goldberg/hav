<?php

namespace BrainpopUser\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\JsonModel,
    BrainpopUser\Form\LoginForm;

class ApiUserController extends AbstractActionController
{
    protected $user_model;
    protected $activity_model;

    /**
     * Process the index action
     * @return object Zend\View\Model\JsonModel
     * @TODO the index acion is not used yet
     */
    public function indexAction()
    {
        return new JsonModel(array());
    }

    /**
     * Process the login request
     * @return  object Zend\View\Model\JsonModel
     */
    public function loginProcessAction()
    {
        if (!$this->request->isPost()) {
            return new JsonModel(array());
        }
        $post = $this->request->getPost();
        
        // Get the form object, inject the filter and set the form data
        $form = $this->getServiceLocator()->get('login_form');
        $filter = $this->getServiceLocator()->get('login_form_filter');
        $form->setInputFilter($filter);
        $form->setData($post);
        
        if (!$form->isValid()) {
            return new JsonModel(array());
        }
        
        $auth = $this->getServiceLocator()->get('auth_service');
        $auth->login($post->account_id, $post->username, $post->password);
        
        if ( $auth->hasIdentity() ) {
            return new JsonModel(array(
                'success' => true,
                'session' => session_id(),
                'unread'  => $this->getActivityModel()->countAllUnread(),
            ));
        }
        
        sleep(5); // Make password guessing a bit harder

        return new JsonModel(array( 'error' => 'Login failed' ));
   }
    
    /**
     * Process the logout action
     * @return object Zend\View\Model\JsonModel
     */
    public function logoutAction()
    {
        $auth = $this->getServiceLocator()->get('auth_service');
        $auth->logout();

        $session = $this->getServiceLocator()->get('session_manager');
        $session->expireSessionCookie();

        return new JsonModel(array(
            'success' => true,
        ));
    }

    /**
     * process the list users that are allowed to shared with
     */
    public function listShareableUsersAction()
    {
        $res = $this->getUserModel()->listShareableUsers();
        return new JsonModel( array ( 'users' => $res ) );
    }

   /**
    * Get the model
    * @return object 
    */
    public function getUserModel()
    {
        if (!$this->user_model) {
            $sm = $this->getServiceLocator();
            $this->user_model = $sm->get('bp_user_model');
        }
        return $this->user_model;
    }

    /**
     * Get the model object from the SM
     * @return object
     */
    public function getActivityModel()
    {
        if (!$this->activity_model) {
            $this->activity_model = $this->getServiceLocator()->get('bp_activity_model');
        }
        return $this->activity_model;
    }
} // End of class
