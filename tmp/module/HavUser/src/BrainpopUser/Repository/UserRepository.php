<?php

namespace BrainpopUser\Repository;

use BrainpopUser\Entity\User;
use BrainpopUser\Mapper\UserMapper;
use BrainpopUser\Mapper\Reports;

use Application\Repository\AbstractRepository;
use Application\Service\MapperWatcher;
use Application\Entity\DomainCollection;

class UserRepository extends AbstractRepository
{

    /**
     * Return user by its ID
     * @param type $id
     * @return User
     */
    public function findById($id)
    {
        return parent::findById($id);
    }

    /**
     * Fetch all users which are related to specific account
     * @return DomainCollection
     */
    public function fetchAllByAccount($accountID)
    {
        return $this->getMapper()->fetchAllByAccount($accountID);
    }

    /**
     * Fetch all Designators
     * @return DomainCollection
     */
    public function fetchDesignators()
    {
        return $this->getMapper()->fetchByRoles(['bp admin', 'bp author']);
    }

    /**
     * Get report of users with indication if in group, which are related to specific account
     * @return Reports\ListAccountUsersWithInGroupFlag
     */
    public function listAccountUsers($accountId)
    {
        return new Reports\ListAccountUsersWithInGroupFlag($accountId, MapperWatcher::getAdapter());
    }

    /**
     * Get report of account users with association flag to specific group
     * @param int $accountId
     * @param int $groupId
     * @return Reports\ListUsersGroupAssociation
     */
    public function ListUsersGroupAssociation($accountId, $groupId){
        return new Reports\ListUsersGroupAssociation($accountId, $groupId, MapperWatcher::getAdapter());
    }

    /**
     * Return the mapper that is associated to the reposetory type
     * @return UserMapper
     * @throws \RuntimeException
     */
    public function getMapper()
    {
        return parent::getMapper();
    }

}