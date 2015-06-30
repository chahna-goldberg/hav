<?php

namespace BrainpopUser\Entity;

use Application\Entity\AbstractDomainEntity;

/*
 * The Group entity
 */
class Group extends AbstractDomainEntity
{
    /** @var int */
    protected $accountId;

    /** @var string */
    protected $name;

    /** @var int */
    protected $serial;

    /** @var int */
    protected $invitesBank;

    /**
     * Set the Account ID
     * @param int
     * @return Group
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
     * Set the account name
     * @param string
     * @return Group
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get the group name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the group invite serial
     * @param int $serial
     * @return Group
     * @throws \RuntimeException
     */
    protected function setSerial($serial)
    {
        if ($this->serial) {
            throw new \RuntimeException("Group serial is already set and is imutable");
        }
        $this->serial = $serial;
        return $this;
    }

    /**
     * Get the group invite serial
     * @return int
     */
    public function getSerial()
    {
        return $this->serial;
    }

    /**
     * Set the group total avilable invites
     * @param int $invites
     * @return Group
     */
    protected function setInvitesBank($invites)
    {
        $this->invitesBank = $invites;
        return $this;
    }

    /**
     * Get the group total avilable invites
     * @return int
     */
    public function getInvitesBank()
    {
        return $this->invitesBank;
    }

    /**
     * Get the group invite code
     * @return string
     */
    public function getInviteCode()
    {
        return sprintf('SCH%04d-GRP%04d', $this->getAccountId(), $this->serial);
    }

    /**
     * Exchange internal values from provided array (required by Hydrator)
     * @param  array $array
     * @return void
     */
    public function exchangeArray(array $array)
    {
        (!empty($array['id']))          ? $this->setId($array['id'])                    : null;
        (!empty($array['account_id']))  ? $this->setAccountId($array['account_id'])      : null;
        (!empty($array['name']))        ? $this->setName($array['name'])                : null;
        (!empty($array['serial']))      ? $this->setSerial($array['serial'])            : null;
        (!empty($array['invites_bank']))? $this->setInvitesBank($array['invites_bank']) : null;
    }

    /**
     * Return an array representation of the object (required by Hydrator)
     * @return array
     */
    public function getArrayCopy()
    {
        return array(
            'id'            => $this->getId(),
            'account_id'    => $this->getAccountId(),
            'name'          => $this->getName(),
            'serial'        => $this->getSerial(),
            'invites_bank'  => $this->getInvitesBank(),
            'invite_code'   => $this->getInviteCode(),
        );
    }

}