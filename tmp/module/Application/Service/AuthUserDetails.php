<?php

namespace Application\Service;

/**
 * Object that keep staticaly the details of the logged on user
 */
Class AuthUserDetails
{
    /** @var \stdClass */
    private static $user;

    /**
     * Set the logged in user details
     * @throws \RuntimeException
     */
    static public function set(\stdClass $user)
    {
        if(isset(self::$user)) {
            throw new \RuntimeException("User is already set and imutable", 874879);
        }
        self::$user = $user;
    }

    /**
     * Get the logged in user details
     * @return \stdClass
     * @throws \RuntimeException
     */
    static public function get()
    {
        if(!isset(self::$user)) {
            throw new \RuntimeException("User is not logged on", 309975);
        }
        return self::$user;
    }

    /**
     * Return the User ID
     * @return int
     */
    static public function getId()
    {
        return (int) self::get()->user_id;
    }

    /**
     * Return the Account ID
     * @return int
     */
    static public function getAccountId()
    {
        return (int) self::get()->account_id;
    }

    /**
     * Return the User username
     * @return string
     */
    static public function getUserName()
    {
        return (string) self::get()->username;
    }
    /**
     * Return the User role
     * @return string
     */
    static public function getRole()
    {
        return (string) self::get()->role;
    }
}