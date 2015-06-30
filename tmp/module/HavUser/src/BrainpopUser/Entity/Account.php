<?php

namespace BrainpopUser\Entity;

use Application\Entity\AbstractDomainEntity;
use Application\Entity\DomainCollection;
use Application\Service\MapperWatcher;

/**
 * The Account entity
 */
class Account extends AbstractDomainEntity
{
    /** @var string */
    protected $name;

    /** @var string */
    protected $email;

    /** @var int */
    protected $maxUsers;

    /** @var int */
    protected $maxGroups;

    /** @var string */
    protected $status;

    /** @var DomainCollection */
    protected $groups;

    /** @var DomainCollection */
    protected $users;

    /**
     * Set the account name
     * @param string
     * @return Account
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get the account name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the email address
     * @param string $address
     * @return string
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
     * Set the max users allowed
     * @param int $maxUsers
     * @return Account
     */
    public function setMaxUsers($maxUsers)
    {
        $this->maxUsers = $maxUsers;
        return $this;
    }

    /**
     * Get the max users allowed
     * @return int
     */
    public function getMaxUsers()
    {
        return $this->maxUsers;
    }

    /**
     * Set the max groups allowed
     * @param int $maxGroups
     * @return Account
     */
    public function setMaxGroups($maxGroups)
    {
        $this->maxGroups = $maxGroups;
        return $this;
    }

    /**
     * Get the max groups allowed
     * @return int
     */
    public function getMaxGroups()
    {
        return $this->maxGroups;
    }

    /**
     * Set the account status
     * @param string $status
     * @return Account
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get the account status
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get the groups collection
     * @return DomainCollection
     */
    public function getGroups()
    {
        if (!$this->groups) {
            /* @var $mapper \BrainpopUser\Mapper\GroupMapper */
            $mapper = MapperWatcher::get(MapperWatcher::GROUP_MAPPER);
            $this->groups = $mapper->fetchAllByAccount($this->getId());
        }
        return $this->groups;
    }

    /**
     * Get the users collection
     * @return DomainCollection
     */
    public function getUsers()
    {
        if (!$this->users) {
            /* @var $mapper \BrainpopUser\Mapper\UserMapper */
            $mapper = MapperWatcher::get(MapperWatcher::USER_MAPPER);
            $this->users = $mapper->fetchAllByAccount($this->getId());
        }
        return $this->users;
    }

    /**
     * Exchange internal values from provided array (required by Hydrator)
     * @param  array $array
     * @return void
     */
    public function exchangeArray(array $array)
    {
        (!empty($array['id']))          ? $this->setId($array['id'])                : null;
        (!empty($array['name']))        ? $this->setName($array['name'])            : null;
        (!empty($array['email']))       ? $this->setEmail($array['email'])          : null;
        (!empty($array['max_users']))   ? $this->setMaxUsers($array['max_users'])   : null;
        (!empty($array['max_groups']))  ? $this->setMaxGroups($array['max_groups']) : null;
        (!empty($array['status']))      ? $this->setStatus($array['status'])        : null;
    }

    /**
     * Return an array representation of the object (required by Hydrator)
     * @return array
     */
    public function getArrayCopy()
    {
        return array(
            'id'            => $this->getId(),
            'name'          => $this->getName(),
            'email'         => $this->getEmail(),
            'max_users'     => $this->getMaxUsers(),
            'max_groups'    => $this->getMaxGroups(),
            'status'        => $this->getStatus(),
        );
    }

}