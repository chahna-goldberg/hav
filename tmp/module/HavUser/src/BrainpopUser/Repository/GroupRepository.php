<?php

namespace BrainpopUser\Repository;

use Application\Repository\AbstractRepository;
use Application\Service\MapperWatcher;
use Application\Entity\DomainCollection;

use BrainpopUser\Entity\Group;
use BrainpopUser\Mapper\GroupMapper;
use BrainpopUser\Mapper\Reports;


class GroupRepository extends AbstractRepository
{

    /**
     * Return group by its ID
     * @param int $id
     * @return Group
     */
    public function findById($id)
    {
        return parent::findById($id);
    }

    /**
     * Fetch all groups which are related to specific account
     * @return DomainCollection
     */
    public function fetchAllByAccount($accountID)
    {
        return $this->getMapper()->fetchAllByAccount($accountID);
    }

    /**
     * Subscribe user to a group
     * @param int $groupId
     * @param array $userIds
     */
    public function subscribeUsers($groupId, $userIds)
    {
        $this->getMapper()->subscribeUsers($groupId, $userIds);
    }

    /**
     * Unsubscribe user from a group
     * @param int $groupId
     * @param array $userIds
     */
    public function unsubscribeUsers($groupId, $userIds)
    {
        $this->getMapper()->unsubscribeUsers($groupId, $userIds);
    }

    /**
     * Report of account groups with users stats (total, etc..)
     * @param int $accountId
     * @return Reports\ListGroupsByAccountPlusUsersStats
     */
    public function ListGroupsByAccountPlusUsersStats($accountId){
        return new Reports\ListGroupsByAccountPlusUsersStats($accountId, MapperWatcher::getAdapter() );
    }

    /**
     * Return the mapper that is associated to the reposetory type
     * @return GroupMapper
     * @throws \RuntimeException
     */
    public function getMapper()
    {
        return parent::getMapper();
    }

}