<?php

namespace BrainpopUser\Repository;

use BrainpopUser\Entity\Account;
use BrainpopUser\Mapper\AccountMapper;
use BrainpopUser\Mapper\Reports;

use Application\Repository\AbstractRepository;
use Application\Service\MapperWatcher;
use Application\Entity\DomainCollection;

class AccountRepository extends AbstractRepository
{

    // Load the repository getters method
    use \BrainpopUser\Repository\RepositoryGetterTrait;

    /** @var AccountMapper */
    protected $mapper;

    /**
     * Fetch all accounts
     * @return DomainCollection
     */
    public function fetchAll()
    {
        return parent::fetchAll();
    }

    /**
     * Return account by its ID
     * @param int $accountId
     * @return Account;
     */
    public function findById($accountId)
    {
        return parent::findById($accountId);
    }

    /**
     * Resylt set of all accounts with total groups, students in group and unassociated groups
     * @param int $accountId
     * @return Reports\AccountsWithGroupsUsersCount
     */
    public function listAccountsWithGroupsUsersCount()
    {
        return new Reports\AccountsWithGroupsUsersCount(MapperWatcher::getAdapter());
    }

    /**
     * check if account can add new group
     * @param Account|int $accountOrId
     * @return bool true if can add
     */
    public function canAddGroup($accountOrId)
    {
        /* @var $account Account */
        $account        = ($accountOrId instanceof Account) ? $accountOrId : $this->findById($accountOrId);
        $totalAccounts  = $this->getGroupRepository()->fetchAllByAccount($account->getId())->count();
        return ($totalAccounts < $account->getMaxGroups()) ? true : false;
    }

    /**
     * check if account can add new user
     * @param Account|int $accountOrId
     * @return bool true if can add
     */
    public function canAddUser($accountOrId)
    {
        /* @var $account Account */
        $account        = ($accountOrId instanceof Account) ? $accountOrId : $this->findById($accountOrId);
        $totalAccounts  = $this->getUserRepository()->fetchAllByAccount($account->getId())->count();
        return ($totalAccounts < $account->getMaxUsers()) ? true : false;
    }

    /**
     * Return the mapper that is associated to the reposetory type
     * @return AccountMapper
     */
    public function getMapper()
    {
        return parent::getMapper();
    }
}