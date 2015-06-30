<?php

namespace BrainpopUser\Entity;

use Application\Entity\AbstractDomainEntity;
use BrainpopUser\Entity\ValueObject\Name;

/**
 * The user entity
 */
class User extends AbstractDomainEntity
{
    /** @var int */
    protected $accountId;

    /** @var string */
    protected $username;

    /** @var string */
    protected $password;

    /** @var string */
    protected $email;

    /** @var Name */
    protected $name;

    /** @var string */
    protected $role;

    /** @var string */
    protected $status = 'active';

    /**
     * Set the Account ID
     * @param int
     * @return User
     * @throws \RuntimeException
     */
    public function setAccountId($id)
    {
        if ($this->accountId === $id) {
            return $this;
        }
        if ($this->accountId) {
            throw new \RuntimeException("Account ID is already set and is imutable");
        }
        $this->accountId = $id;
        return $this;
    }

    /**
     * Get the Account ID
     * @return int
     */
    public function getAccountId()
    {
        return $this->accountId;
    }

    /**
     * Set the username
     * @param string
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * Get the username
     * @return int
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the user password
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Get the user password
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the email address
     * @param string $address
     * @return User
     */
    protected function setEmail($address)
    {
        $this->email = $address;
        return $this;
    }

    /**
     * Get the email address
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the name
     * @param string $privateName
     * @param string $familyName
     * @return User
     */
    public function setName($privateName, $familyName) {
        $this->name = new Name($privateName, $familyName);
        return $this;
    }

    /**
     * Get the user name
     * @return Name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the user role
     * @param string $role
     * @return User
     */
    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }

    /**
     * Get the user role
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set the user status
     * @param string $status
     * @return User
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get the user status
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Exchange internal values from provided array (required by Hydrator)
     * @param  array $array
     * @return void
     */
    public function exchangeArray(array $array)
    {
        (!empty($array['id']))          ? $this->setId($array['id'])                                : null;
        (!empty($array['accountId']))   ? $this->setAccountId($array['accountId'])                  : null;
        (!empty($array['username']))    ? $this->setUsername($array['username'])                    : null;
        (!empty($array['password']))    ? $this->setPassword($array['password'])                    : null;
        (isset($array['email']))        ? $this->setEmail($array['email'])                          : null;
        (!empty($array['firstName']))   ? $this->setName($array['firstName'], $array['lastName'])   : null;
        (!empty($array['role']))        ? $this->setRole($array['role'])                            : null;
        (!empty($array['status']))      ? $this->setStatus($array['status'])                        : null;
    }

    /**
     * Return an array representation of the object (required by Hydrator)
     * @return array
     */
    public function getArrayCopy()
    {
        return array(
            'id'            => $this->getId(),
            'accountId'     => $this->getAccountId(),
            'username'      => $this->getUsername(),
            'password'      => $this->getPassword(),
            'email'         => $this->getEmail(),
            'firstName'     => ($this->getName() instanceof Name) ? $this->getName()->getPrivateName() : '',
            'lastName'      => ($this->getName() instanceof Name) ? $this->getName()->getFamilyName() : '',
            'role'          => $this->getRole(),
            'status'        => $this->getStatus(),
        );
    }

}