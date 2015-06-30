<?php
namespace Application\Authentication;

use Zend\Authentication\AuthenticationService as ZfAuthService,
    BrainpopUser\Model\UserMapper;

class AuthenticationService
{
    protected $auth;
    protected $mapper;

    public function __construct(ZfAuthService $auth, UserMapper $mapper)
    {
        $this->auth     = $auth;
        $this->mapper   = $mapper;
    }

    /**
     * Authenticate the user and create the auth session
     * @param int $accout_id
     * @param string $user_name
     * @param string $password
     * @return type 
     */
    public function login($account_id, $username, $password)
    {
        if ( empty($username) OR empty($password) or empty($account_id) ) {
            return;
        }

        $res = $this->mapper->authUser($account_id, $username, $password);

        if (is_object($res)) {
            $this->getStorage()->write($res);
        }

        return TRUE;
    }

    /**
     * Logout the user and clear the session
     */
    public function logout()
    {
        if (!$this->hasIdentity()) {
            return;
        }
        $this->clearIdentity();
    }
    
    /**
     * Returns true if and only if an identity is available from storage
     *
     * @return boolean
     */
    public function hasIdentity()
    {
        return !$this->getStorage()->isEmpty();
    }
    
    /**
     * Returns the persistent storage handler
     * @return Zend\Authentication\Storage
     */
    public function getStorage()
    {
        return $this->auth->getStorage();
    }

    /**
     * Clears the identity from persistent storage
     * @return void
     */
    public function clearIdentity()
    {
        $this->getStorage()->clear();
    }

}
