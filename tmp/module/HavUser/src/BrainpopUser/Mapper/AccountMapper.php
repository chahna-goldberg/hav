<?php

namespace BrainpopUser\Mapper;

use Application\Mapper\AbstractDbMapper;
use Application\Service\ObjectWatcher;
use Application\Entity\DomainCollection;

use BrainpopUser\Entity\Account;
use BrainpopUser\InputFilter\AccountInputFilter;

class AccountMapper extends AbstractDbMapper
{

    /**
     * Set the entity that will be used by this mapper
     * @throws \RuntimeException
     */
    protected function setEntityPrototype()
    {
        if ($this->entityPrototype instanceof Account) {
            throw new \RuntimeException('Entity prototype is already set and it is imutable');
        }
        $this->entityPrototype = new Account;
    }

    /**
     * Return account by its ID
     * @param int $id
     * @return Account
     */
    public function findById($id)
    {
        $cachedEntity   = $this->getFromMap($id);
        if ($cachedEntity) {
            return $cachedEntity;
        }
        $sql    = "SELECT account_id AS id, account_name AS name, account_email AS email, max_users, max_groups, account_status AS status FROM accounts WHERE account_id=?";
        $result = $this->getAdapter()->query($sql)->execute(array($id));
        if ($result->count() === 0) {
            return;
        }
        return $this->hydrateEntity($result->current());
    }

    /**
     * Fetch all accounts from the DB
     * @return DomainCollection
     */
    public function fetchAll()
    {
        $key            = "AccountMapper.fetchAll.0";
        $cachedObject   = ObjectWatcher::collectionGet($key);
        if ($cachedObject) {
            return $cachedObject;
        }
        $sql        = "SELECT account_id AS id, account_name AS name, account_email AS email, max_users, max_groups, account_status AS status FROM accounts ORDER BY account_name";
        $results    = $this->getAdapter()->query($sql)->execute();
        return $this->populateResultSet($results, $key);
    }

    /**
     * Add the account record to the DB
     * @param Account $account
     */
    public function doInsert(Account $account) {
        $sql = "INSERT INTO accounts (account_name, account_email, max_users, max_groups, account_status, permission_level, expiration_date) VALUES (:name, :email, :max_users, :max_groups, :status , 'view', NOW()) RETURNING account_id";
        $result = $this->getAdapter()->query($sql)->execute(array_slice($account->getArrayCopy(), 1))->current();
        $account->setId($result['account_id']);
    }

    /**
     * Update the account record in the DB
     * @param Account $account
     */
    public function doUpdate(Account $account) {
        $sql = "UPDATE accounts SET account_name=:name, account_email=:email, max_users=:max_users, max_groups=:max_groups, account_status=:status WHERE account_id=:id RETURNING account_id";
        $this->getAdapter()->query($sql)->execute($account->getArrayCopy());
    }

    /**
     * Delete the account record from the DB
     * @param Account $account
     */
    public function doDelete(Account $account) {
        $sql = "DELETE FROM accounts WHERE account_id=:id";
        $this->getAdapter()->query($sql)->execute(array($account->getId()));
    }

    /**
     * Validate the entity
     * @param Account $entity
     * @throws \RuntimeException
     */
    public function isValid($entity)
    {
        if (!$entity instanceof $this->entityPrototype) {
            throw new \RuntimeException('Validate is missing the required entity');
        }
        $inputFilter = new AccountInputFilter($this, $this->getAdapter());
        if (!$entity->getId()) {
            $inputFilter->setValidationWithoutId();
        }
        $inputFilter->setData($entity->getArrayCopy());
        if ($inputFilter->isValid()) {
            $entity->exchangeArray($inputFilter->getValues());
            return true;
        }
        $entity->setMessages($inputFilter->getMessages());
    }

    /**
     * Return true if Account name is not used
     * @param string $name
     * @return boolean
     */
    public function isNameAvilable($name, $data)
    {
        $where = null;
        if (!empty($data['id'])){
            $where = "AND account_id != {$data['id']}";
        }
         $sql       = "SELECT 1 FROM accounts WHERE account_name ILIKE ? {$where} LIMIT 1";
        $results    = $this->getAdapter()->query($sql)->execute(array($name));
        if (!$results->count()) {
            return true;
        }
    }
}