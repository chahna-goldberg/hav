<?php

namespace BrainpopUser\Mapper;

use Application\Mapper\AbstractDbMapper;
use Application\Service\ObjectWatcher;
use Application\Entity\DomainCollection;

use BrainpopUser\Entity\Group;
use BrainpopUser\InputFilter\AddGroupFilter;
use BrainpopUser\InputFilter\UpdateGroupFilter;

use Zend\Db\Adapter\Driver\Pdo\Result as PdoResult;

class GroupMapper extends AbstractDbMapper
{

    /**
     * Set the entity that will be used by this mapper
     * @throws \RuntimeException
     */
    protected function setEntityPrototype()
    {
        if ($this->entityPrototype instanceof Group) {
            throw new \RuntimeException('Entity prototype is already set and it is imutable');
        }
        $this->entityPrototype = new Group;
    }

    /**
     * Return group by its ID
     * @param int $id
     * @return Group;
     */
    public function findById($id)
    {
        $cachedEntity   = $this->getFromMap($id);
        if ($cachedEntity) {
            return $cachedEntity;
        }
        $sql    = "SELECT id, account_id, name, serial, invites_bank from groups WHERE id=?";
        $result = $this->getAdapter()->query($sql)->execute(array($id));
        if ($result->count() === 0) {
            return;
        }
        return $this->hydrateEntity($result->current());
    }

    /**
     * Fetch all groups which are related to specific account from the DB
     * @return DomainCollection
     */
    public function fetchAllByAccount($accountID)
    {
        $key            = "GroupMapper.fetchAllByAccount.{$accountID}";
        $cachedObject   = ObjectWatcher::collectionGet($key);
        if ($cachedObject) {
            return $cachedObject;
        }
        $sql        = "SELECT id, name, serial, invites_bank from groups WHERE account_id=?";
        $results    = $this->getAdapter()->query($sql)->execute(array($accountID));
        return $this->populateResultSet($results, $key);
    }

    /**
     * Add the group data to the DB
     * @param Group $group
     */
    public function doInsert(Group $group) {
        $sql    = "INSERT INTO groups (account_id, name, invites_bank) VALUES (:account_id, :name, :invites_bank) RETURNING id";
        $var    = self::getSelectiveArrayFromEntity($group, array('account_id', 'name', 'invites_bank'));
        $result = $this->getAdapter()->query($sql)->execute($var)->current();
        $group->setId($result['id']);
    }

    /**
     * Update the group record in the DB
     * @param Group $group
     */
    public function doUpdate(Group $group) {
        $sql = "UPDATE groups SET name=:name, invites_bank=:invites_bank WHERE id=:id";
        $var = self::getSelectiveArrayFromEntity($group, array('name', 'invites_bank', 'id'));
        $this->getAdapter()->query($sql)->execute($var);
    }

    /**
     * Delete the group record from the DB
     * @param Group $group
     */
    public function doDelete(Group $group) {
        $sql = "DELETE FROM groups WHERE id=:id";
        $this->getAdapter()->query($sql)->execute(array($group->getId()));
    }

    /**
     * Subscribe users to a group
     * @param int $groupId
     * @param array $userIds
     */
    public function subscribeUsers($groupId, $userIds)
    {
        $this->getAdapter()->getDriver()->getConnection()->beginTransaction();
        $sql    = "INSERT INTO group_users (group_id, user_id) VALUES (?, ?)";
        foreach ($userIds as $userId) {
            $affectedRows = $this->getAdapter()->query($sql)->execute(array($groupId, $userId))->getAffectedRows();
            if ($affectedRows <> 1) {
                $this->getAdapter()->getDriver()->getConnection()->rollback();
            }
        }
        $this->getAdapter()->getDriver()->getConnection()->commit();
    }

    /**
     * Delete users association to a group
     * @param int $groupId
     * @param array $userIds
     */
    public function unsubscribeUsers($groupId, $userIds)
    {
        $this->getAdapter()->getDriver()->getConnection()->beginTransaction();
        $sql    = "DELETE FROM group_users WHERE group_id=? AND user_id=?";
        foreach ($userIds as $userId) {
            $affectedRows = $this->getAdapter()->query($sql)->execute(array($groupId, $userId))->getAffectedRows();
            if ($affectedRows <> 1) {
                $this->getAdapter()->getDriver()->getConnection()->rollback();
            }
        }
        $this->getAdapter()->getDriver()->getConnection()->commit();
    }

    /**
     * Return true if group name is unique in the current account
     * @param string $name
     * @return boolean
     */
    public function isNameAvilable($name, $data)
    {
        $id         = ($data['id']) ? $data['id'] : '0';
        $sql        = "SELECT 1 FROM groups WHERE name=? AND account_id=? AND id!=?";
        $results    = $this->getAdapter()->query($sql)->execute(array($name, $data['account_id'], $data['id']));
        if (!$results->count()) {
            return true;
        }
    }

    /**
     * 
     * @param Group $entity
     * @throws \RuntimeException
     */
    public function isValid($entity)
    {
        if (!$entity instanceof $this->entityPrototype) {
            throw new \RuntimeException('Validate is missing the required entity');
        }
        $inputFilter = $this->getInputFilter($entity);

        $inputFilter->setData($entity->getArrayCopy());
        if ($inputFilter->isValid()) {
            $entity->exchangeArray($inputFilter->getValues());
            return true;
        }
        $entity->setMessages($inputFilter->getMessages());
    }

    /**
     * Return the aproperiate InputFilter
     * @param Group $group
     * @return AddGroupFilter|UpdateGroupFilter
     */
    private function getInputFilter($group)
    {
        $accountRepo = new \BrainpopUser\Repository\AccountRepository();
        if ($group->getId()) {
            return new UpdateGroupFilter($this->getAdapter(), $group->getId(), $group->getAccountId());
        }
        return new AddGroupFilter($this->getAdapter(), $accountRepo, $group->getAccountId());
    }
}
